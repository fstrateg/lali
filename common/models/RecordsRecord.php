<?php
namespace common\models;
use common\components\Date;
use common\components\Telegram;
use common\components\SMS;
use common\components\SMSNikita;

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
            'client_phone'=>$data['client']['phone'],
            'services_id'=>implode(',',$srv),
            'attendance'=>$data['attendance'],
        ];
        $rec['client_id']=(isset($data['client']['id']))?$data['client']['id']:'-1';
        return $rec;
    }

    public function afterSave($insert, $changedAttributes)
    {
            if ($insert)
            {
                if (Date::fromMysql($this->appointed)->get() < Date::now()) return true;
                if (empty($this->getAttribute('client_phone')))
                {
                    Telegram::instance()
                        ->sendMessageAll("Мастер: {$this->getAttribute('staff_name')}\r\nЗапись:{$this->getAttribute('appointed')}",
                            "Нет номера телефона!");
                    return true;
                }
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