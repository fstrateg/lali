<?php
namespace common\models;

use Yii;
use yii\db\ActiveRecord;

class SMSSettings extends ActiveRecord
{
    public static function tableName()
    {
        return '{{settings_sms}}';
    }
}