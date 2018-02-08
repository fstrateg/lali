<?php
namespace common\models;


use yii\db\ActiveRecord;

class SettingsRecord extends ActiveRecord
{
    public static function tableName()
    {
        return '{{settings}}';
    }

    public static function findValue($group,$param)
    {
        return self::findOne(['group'=>$group,'param'=>$param])->val;
    }
}