<?php
namespace frontend\controllers;

use common\models\Access;
use frontend\models\WorkStudio;
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
                        'actions' => ['index'],
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
}
