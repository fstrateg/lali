<?php
namespace frontend\controllers;

use common\models\Access;
use frontend\models\WorkStudio;
use frontend\models\WorkStudioDetails;
use yii;
use yii\web\Controller;
use yii\filters\AccessControl;
use yii\web\ForbiddenHttpException;

/**
 * Studio controller
 */

class StController extends Controller
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
                        'actions' => ['index','note'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }

    public function beforeAction($action)
    {
        if (!(Access::isMaster()||Access::isAdmin()))
            throw new ForbiddenHttpException('Доступ к этому разделу запрещен!');
        $this->layout="lc";
        return parent::beforeAction($action);
    }

    public function actionIndex()
    {
        $dat=yii::$app->request->get('dat');
        $model=new WorkStudio();
        $model->init($dat);
        return $this->render('index',['model'=>$model]);
    }

    public function actionNote()
    {
        $id=yii::$app->request->post('id',-1);
        $model=new WorkStudioDetails();
        $model->initRecord($id);
        return $this->renderAjax('note',['model'=>$model]);
    }
}
