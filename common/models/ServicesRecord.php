<?php
namespace common\models;

class ServicesRecord extends \yii\db\ActiveRecord
{
    public static function tableName()
    {
        return '{{%services}}';
    }

    public static function initRec($resource_id,$data)
    {
        $rez=self::findOne($resource_id);
        $rez=$rez!=null?$rez:new ServicesRecord();
        $rez->id=$resource_id;
        $rez->title=$data['title'];
        return $rez;
    }
}