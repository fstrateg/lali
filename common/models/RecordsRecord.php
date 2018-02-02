<?php
namespace common\models;
use app\components\Telegram;
use common\components\SMS;

class RecordsRecord extends \yii\db\ActiveRecord
{
    public static function tableName()
    {
        return '{{%records}}';
    }

    public static function initRec($resource_id,$data)
    {
        $rez=self::findOne(['resource_id'=>$resource_id]);
        $rez=$rez!=null?$rez:new RecordsRecord();
        $rez->resource_id=$resource_id;
        $rw=self::getRec($data);
        foreach($rw as $k=>$v)
            $rez->setAttribute($k,$v);
        return $rez;
    }

    public static function getRec($data)
    {
        print_r($data['services']);
        $srv=[];
        foreach($data['services'] as $item) $srv[]=$item['id'];
        $rec=[
            'staff_name'=>$data['staff']['name'],
            'appointed'=>$data['date'],
            'services_id'=>implode(',',$srv),
            'client_id'=>$data['client']['id'],
            'client_phone'=>$data['client']['phone'],
        ];
        return $rec;
    }

    public function afterSave($insert, $changedAttributes)
    {
        if (parent::afterSave($insert, $changedAttributes))
        {
            if ($insert)
            {
                $sms=new SMS();
                $sms->setNumber(0);
                $sms->setRecord($this);
                $t=\common\components\Telegram::instance();
                $t->sendMessage('Alex',$sms->getMessageText());
            }
            return true;
        }
        return false;
    }
}