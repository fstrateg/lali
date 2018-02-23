<?php
namespace common\models;
use Yii;
/**
 * Class ServicesRecord
 * @package common\models
 * @property $status
 * @property $title
 * @property $scrubbing
 * @property $remind
 * @property $deleted
 */
class ServicesRecord extends \yii\db\ActiveRecord
{
    public static function tableName()
    {
        return '{{%services}}';
    }

    public function rules()
    {
        return [
            [['scrubbing'],'integer'],
            [['remind'],'integer'],
        ];
    }

    public function attributeLabels()
    {
        return [

            'title'=>Yii::t('app','Название Услуги'),
            'scrubbing'=>Yii::t('app','СМС скрабироваться'),
            'remind'=>Yii::t('app','Напомнить о себе (21,42)'),

        ];
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