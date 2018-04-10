<?php
namespace frontend\models;

use common\components\Date;
use Yii;
use yii\db\ActiveRecord;

/**
 * Class QualityRecord
 * @package common\models
 * @property $record_id
 * @property $status
 * @property $dat
 *
 */
class QualityRecord extends ActiveRecord
{
    public static function tableName()
    {
        return '{{%quality}}';
    }

    public static function SaveVal($id,$stat)
    {
        $obj=QualityRecord::findOne(['record_id'=>$id]);
        if ($obj!=null)
        {
            $obj->setAttribute('status',$stat);
        }
        else
        {
            $obj=new QualityRecord();
            $obj->setAttribute('record_id',$id);
            $obj->setAttribute('status',$stat);
        }
        $obj->setAttribute('dat',(new Date())->toMySql());
        $obj->save();
    }
}