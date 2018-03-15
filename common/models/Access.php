<?php
namespace common\models;

use yii;
use yii\base\BaseObject;

class Access extends BaseObject
{
    public static $isOper;

    public static function isOper()
    {
        if (self::isGuest()) return false;
        return self::getRole()=='oper'?true:false;
    }

    public static function isGuest()
    {
        return yii::$app->user->isGuest;
    }

    public static function isAdmin()
    {
        if (self::isGuest()) return false;
        return self::getRole()=='admin'?true:false;
    }

    private static function getRole()
    {
        return yii::$app->user->identity->role;
    }

}