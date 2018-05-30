<?php
namespace frontend\controllers;

use common\models\Access;
use common\models\RecordsRecord;
use frontend\models\LcWatsApp;
use frontend\models\QualityRecord;
use yii;
use yii\web\Controller;
use yii\filters\AccessControl;
use yii\web\ForbiddenHttpException;

/**
 * Lc controller
 */

class LcController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout', 'signup'],
                'rules' => [
                    [
                        'actions' => ['index','qualitysave','qualitysaves'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }

    public function beforeAction($action)
    {
        if (!(Access::isOper()||Access::isAdmin()))
            throw new ForbiddenHttpException('Доступ к этому разделу запрещен!');
        $this->layout="lc";
        return parent::beforeAction($action);
    }

    public function actionIndex()
    {
        $dat=yii::$app->request->get('dat');
        $model=new LcWatsApp();
        $model->init($dat);
        return $this->render('index',['model'=>$model]);
    }

    public function actionQualitysave($typ,$id,$status)
    {
        QualityRecord::SaveVal($typ,$id,$status);
        return 'OK';
    }

    public function actionQualitysaves()
    {
        $req=Yii::$app->request;
        $ids=$req->post('ids');
        $ids=json_decode($ids);
        $vl=$req->post('vl');
        $typ=$req->post('typ');
        if (QualityRecord::SaveVals($typ,$ids,$vl))
            return 'OK';
        return 'FALSE';
    }
}
