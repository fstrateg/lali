<?php
namespace common\models;

use Yii;
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
}