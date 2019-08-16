<?php
namespace backend\controllers;

use backend\models\MessagesSearch;
use common\models\SettingsRecord;
use common\models\StaffRecord;
use common\models\StaffUserRecord;
use common\models\SysMessagesRecord;
use Yii;
use yii\data\ActiveDataProvider;
use yii\helpers\Url;
use yii\web\Controller;
use yii\filters\AccessControl;
use yii\web\ForbiddenHttpException;
use common\models\Access;

class AppController extends Controller
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
                        'actions' => ['quality','qualitysave','qualitymsg','masters','msg'],
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

    public function beforeAction($action)
    {
        if (!Access::isAdmin())
            throw new ForbiddenHttpException('Доступ к этому разделу запрещен!');
        return parent::beforeAction($action);
    }

    public function actionMsg()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => SysMessagesRecord::find(),
            'sort' => [ // сортировка по умолчанию
                'defaultOrder' => ['id' => SORT_DESC],
            ],
            'pagination' => [ // постраничная разбивка
                'pageSize' => 50, // 10 новостей на странице
            ],
        ]);
        $searchModel = new MessagesSearch();

        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render("msglog",['dataProvider'=>$dataProvider,'searchModel'=>$searchModel]);
    }

    public function actionQualitymsg()
    {
        $post=Yii::$app->request->post();
        if($post)
        {
            SettingsRecord::setValue('quality','lasermsg',$post['lasermsg']);
            SettingsRecord::setValue('quality','waxmsg',$post['waxmsg']);
            SettingsRecord::setValue('quality','electro1',$post['electro1']);
            SettingsRecord::setValue('quality','electro2',$post['electro2']);
            SettingsRecord::setValue('quality','electro3',$post['electro3']);
            Yii::$app->getSession()->setFlash('ok','Сохранено');
        }
        return $this->render('qualitymsg');
    }

    public function actionQuality()
    {
        return $this->render('quality');
    }

    public function actionQualitysave()
    {
        \app\models\Quality::qualitySave(yii::$app->request);
        return $this->redirect('quality');
    }

    public function actionMasters($m='index')
    {
        $html="";
        if (isset($_POST['m'])) $m=$_POST['m'];
        switch($m)
        {
            case 'update':
                $html=$this->MastersUpdate();
                break;
            case 'save':
                $html=$this->MastersSave();
                break;
            default:
                $html=$this->MastersIndex();
                break;
        }
        return $html;
    }

    private function MastersIndex()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => StaffRecord::find()->where("deleted=0"),
            'sort' => [ // сортировка по умолчанию
                'defaultOrder' => ['name' => SORT_DESC],
            ],
            'pagination' => [ // постраничная разбивка
                'pageSize' => 20, // 10 новостей на странице
            ],
        ]);

        return $this->render('masters',['data'=>$dataProvider]);
    }

    private function MastersUpdate()
    {
        $id=yii::$app->request->get('id',-1);
        $model=StaffRecord::findOne($id);
        return $this->render('mastersed',['model'=>$model]);
    }

    private function MastersSave()
    {
        if (!StaffUserRecord::saveForm($_POST['id'],$_POST['userid']))
        {
            return $this->MastersUpdate();
        }
        Yii::$app->session->setFlash('success', 'Изменения сохранены!');
        $this->redirect(Url::to(['masters']));
    }
}