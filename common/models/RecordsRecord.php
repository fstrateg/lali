<?php
namespace common\models;
use common\components\SMS;
use common\components\Date;

/**
 * Class RecordsRecord
 * @package common\models
 * @property $attendance
 */
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
        if ($rez->isNewRecord) {
            $ct = new \DateTime('now', new \DateTimeZone(\yii::$app->timeZone));
            $rez->created = $ct->format('Y-m-d H:i:s');
        }
        $rw=self::getRec($data);
        foreach($rw as $k=>$v)
            $rez->setAttribute($k,$v);
        return $rez;
    }

    public static function getRec($data)
    {
        $srv=[];
        foreach($data['services'] as $item) $srv[]=$item['id'];
        $rec=[
            'staff_name'=>$data['staff']['name'],
            'appointed'=>$data['date'],
            'services_id'=>implode(',',$srv),
            'client_id'=>$data['client']['id'],
            'client_phone'=>$data['client']['phone'],
            'attendance'=>$data['attendance'],
        ];
        return $rec;
    }

    public function afterSave($insert, $changedAttributes)
    {
            if ($insert)
            {
                $dt1=new Date();
                $dt2=Date::fromMysql($this->getAttribute('appointed'));
                if ($dt2->get()<$dt1->get()) return true;
                $sms=new SMS();
                $sms->setNumber(0);
                $sms->setRecord($this);
                $sms->send();
                /*$t=Telegram::instance();
                $t->sendMessageAll($sms->getMessageText(),$sms->client_phone);*/
                /*$msg=$sms->getMessageText();

                $t->sendMessage('Alex',$sms->getMessageText($msg),$sms->client_phone);
                $t->sendMessage('nikvoit',$sms->getMessageText($msg),$sms->client_phone);*/

                //Telegram::instance()->sendMessage('Alex','Добавилась новая запись на клиента: '.$this->client_id);
            }
            return true;
    }
}