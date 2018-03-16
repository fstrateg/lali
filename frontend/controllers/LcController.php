<?php
namespace frontend\controllers;

use common\models\Access;
use common\models\RecordsRecord;
use frontend\models\LcWatsApp;
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
        if (!(Access::isOper()||Access::isAdmin()))
            throw new ForbiddenHttpException('Доступ к этому разделу запрещен!');
        $this->layout="lc";
        return parent::beforeAction($action);
    }

    public function actionIndex()
    {
        return $this->render('index');
    }
}
