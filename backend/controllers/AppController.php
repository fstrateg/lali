<?php
namespace backend\controllers;

use Yii;
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
                        'actions' => ['quality','qualitysave','qualitymsg'],
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

    public function actionQualitymsg()
    {
        $post=Yii::$app->request->post();
        if($post)
        {
            \common\models\SettingsRecord::setValue('quality','lasermsg',$post['lasermsg']);
            \common\models\SettingsRecord::setValue('quality','waxmsg',$post['waxmsg']);
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
}