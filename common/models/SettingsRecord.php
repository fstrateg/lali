<?php
namespace common\models;


use yii\db\ActiveRecord;

/**
 * @property val
 * @property group
 * @property param
 */
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

    public static function setValue($group,$param,$value)
    {
        $r=self::findOne(['group'=>$group,'param'=>$param]);
        $r->val=$value;
        $r->save();
    }
}