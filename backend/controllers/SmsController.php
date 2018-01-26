<?php
namespace backend\controllers;

use app\models\CityRecord;
use app\models\ClientsRecord;
use app\models\SMSSettings;
use Yii;
use yii\web\Controller;
use yii\filters\AccessControl;


/**
 * Site controller
 */
class SmsController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['config','except','clients','exceptadd'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
        ];
    }

    public function actionConfig()
    {
        $data=Yii::$app->request->post();
        if (isset($data['r'])) SMSSettings::saveData($data['r']);
        $pages=SMSSettings::getPages();
        return $this->render('config',['pages'=>$pages,'test'=>$data]);
    }

    public function actionExcept()
    {
        $pages=null;
        return $this->render('except',['pages'=>$pages]);
    }

    public function actionClients($term)
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $rs=ClientsRecord::find()->where(['like','phone',$term])->select(['name','phone','id'])->orderBy('name')->limit(15)->all();
        if($rs !=null){
            $row_set = [];
            foreach ($rs as $row)
            {
                $row_set[] = ['label'=>$row->phone.' '.$row->name,'value'=>$row->phone,'id'=>$row->id]; //build an array
            }
            return $row_set;
        }else{
            false;
        }
    }
    public function actionExceptadd()
    {
        $clientid=yii::$app->request->post("idclient");
        $client=ClientsRecord::findOne(['id'=>$clientid]);
        return $this->renderPartial('except.add.php',['client'=>$client]);
    }
}
