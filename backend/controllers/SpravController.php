<?php
namespace backend\controllers;

use app\models\CityRecord;
use Yii;
use yii\helpers\Url;
use yii\web\Controller;
use yii\filters\AccessControl;
use yii\helpers\Html;
use yii\data\ActiveDataProvider;

/**
 * Site controller
 */
class SpravController extends Controller
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
                        'actions' => ['login', 'error'],
                        'allow' => true,
                    ],
                    [
                        'actions' => ['city'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }


    public function actionCity($m='index')
    {
        switch($m)
        {
            case 'index';
                return $this->cityIndex();
            case 'add':
                return $this->cityAdd();
            case 'update':
                $id=yii::$app->request->get('id',1);
                return $this->cityUpdate($id);
            case 'delete':
                return $this->cityDelete();
        }
    }

    private function cityIndex()
    {
        $citys=CityRecord::find()->all();
        $dataProvider = new ActiveDataProvider([
            'query' => CityRecord::find(),
            'sort' => [ // сортировка по умолчанию
                'defaultOrder' => ['name' => SORT_DESC],
            ],
            'pagination' => [ // постраничная разбивка
                'pageSize' => 10, // 10 новостей на странице
            ],
        ]);
        return $this->render('city',['citys'=>$citys,'data'=>$dataProvider]);
    }

    private function cityAdd()
    {
        $form=new CityRecord();
        if ($form->load(Yii::$app->request->post(),'CityRecord'))
        {
            if ($form->save())
                return $this->redirect(Url::to(['city','m'=>'index']));
            else
                Yii::$app->getSession()->setFlash('error', Html::errorSummary($form));
        }
        return $this->render('cityForm',['model'=>$form]);
    }

    private function cityUpdate($id)
    {
        $form=CityRecord::findOne($id);
        if ($form->load(Yii::$app->request->post(),'CityRecord'))
        {
            if ($form->save())
                return $this->redirect(Url::to(['city','m'=>'index']));
            else
                Yii::$app->getSession()->setFlash('error', Html::errorSummary($form));
        }
        return $this->render('cityForm',['model'=>$form]);
    }

    private function cityDelete()
    {
        $id=yii::$app->request->get('id',-1);
        CityRecord::findOne((int)$id)->delete();
        return $this->redirect(Url::to(['city','m'=>'index']));
    }
}
