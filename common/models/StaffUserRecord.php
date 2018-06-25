<?php
namespace common\models;
use Yii;
/**
 * Class ServicesRecord
 * @package common\models
 * @property $id
 * @property $staff_id
 * @property $user_is
 */
class StaffUserRecord extends \yii\db\ActiveRecord
{
    public static function tableName()
    {
        return '{{%staff_user}}';
    }
}
