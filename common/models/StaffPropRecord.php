<?php
namespace common\models;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * Class ServicesRecord
 * @package common\models
 * @property $id
 * @property $name
 * @property $created
 */
class StaffPropRecord extends \yii\db\ActiveRecord
{
    public static function tableName()
    {
        return '{{%staff_prop}}';
    }

    public static function getPropForStaff($prop_id)
    {
        $rws=self::findAll(['prop_id'=>$prop_id]);
        $rez=array();
        foreach($rws as $rw) $rez[$rw['staff_id']]=$rw['staff_id'];

        return $rez;
    }
}