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
         $dataProvider = new ActiveDataProvider([
            'query' => ClientsRecord::find()->where(['or',['exception_5'=>1],['exception_21'=>1],['exception_42'=>1]]),
            'sort' => [ // сортировка по умолчанию
                'defaultOrder' => ['name' => SORT_ASC],
            ],
            'pagination' => [ // постраничная разбивка
                'pageSize' => 10, // 10 новостей на странице
            ],
        ]);
        return $dataProvider;
    }

    public function attributeLabels()
    {
        return
            [
                'name'=>Yii::t('app','Имя клиента'),
                'phone'=>Yii::t('app','Номер телефона'),
                'exception_5'=>Yii::t('app','СМС через 5 дней'),
                'exception_21'=>Yii::t('app','СМС через 21 день'),
                'exception_42'=>Yii::t('app','СМС через 42 дня'),
            ]
        ;
    }

    public function setExcept($post)
    {
        if (!$post) return false;
        $client=ClientsRecord::findOne(['id'=>$post['id']]);
        $client->exception_5=$post['exception_5'];
        $client->exception_21=$post['exception_21'];
        $client->exception_42=$post['exception_42'];
        return $client->update();
    }

    public static function deleteFromExcept($uid)
    {
        $client=ClientsRecord::findOne(['uid'=>$uid]);
        $client->exception_5=0;
        $client->exception_21=0;
        $client->exception_42=0;
        return $client->update();
    }

    public static function getPages()
    {
        $rez=[];
        $id=ClientsRecord::find()->select('city')->distinct()->all();
        foreach($id as $i)
        {
            $rez[]=['id'=>$i->city,'name'=>CityRecord::findOne($i->city)->name];
        }
        return $rez;
    }
}