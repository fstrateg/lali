<?php
namespace backend\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use app\models\City;
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
                        'actions' => ['cityindex'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }


    public function actionCityindex()
    {
        $citys=City::find()->all();
        $dataProvider = new ActiveDataProvider([
            'query' => City::find(),
            'sort' => [ // сортировка по умолчанию
                'defaultOrder' => ['name' => SORT_DESC],
            ],
            'pagination' => [ // постраничная разбивка
                'pageSize' => 10, // 10 новостей на странице
            ],
        ]);
        return $this->render('city',['citys'=>$citys,'data'=>$dataProvider]);
    }
}
