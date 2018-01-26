<?php
namespace app\models;

use Yii;
use yii\db\ActiveRecord;
use yii\data\ActiveDataProvider;

class ClientsRecord extends ActiveRecord
{
    public static function tableName()
    {
        return '{{clients}}';
    }

    public static function getDataProvider()
    {
        //$data=ClientsRecord::find();
        $dataProvider = new ActiveDataProvider([
            'query' => ClientsRecord::find()->orWhere(['exception_5'=>1,'exception_21'=>1,'exception_42'=>1]),
            'sort' => [ // сортировка по умолчанию
                'defaultOrder' => ['name' => SORT_DESC],
            ],
            'pagination' => [ // постраничная разбивка
                'pageSize' => 10, // 10 новостей на странице
            ],
        ]);
        return $dataProvider;
    }
}