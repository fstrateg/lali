<?php
namespace common\components;

use common\models\ServicesRecord;
use common\models\SettingsRecord;
use common\models\Sms_doneRecord;
use common\models\SMSSettings;
use common\models\ClientsRecord;
use common\models\RecordsRecord;
use yii\base\BaseObject;

/**
 * Class SMS
 * @package common\components
 * @property $transaction_id;
 */
class SMS extends BaseObject
{
    private $transaction_id;
    private $message;
    private $msg_noname;
    /**
     * @var RecordsRecord
     */
    private $record;
    public $Dontsend=false;
    public $client_phone;
    public $HasError=false;
    /**
     * @var ClientsRecord
     */
    public $client;
    public $error;

    public function getMessageText()
    {
        return $this->_prepare();
    }

    public function setNumber($day)
    {
        $s=SMSSettings::findOne(['days'=>$day]);
        if (!$s->sms_on) $this->Dontsend=true;
        $this->message=$s->sms_text;
        $this->msg_noname=$s->sms_text_noname;
    }


    /**
     * @param $record RecordsRecord|integer
     */
    public function setRecord($record)
    {
        if (is_numeric($record))
        {
            $record=RecordsRecord::findOne($record);
        }
        $this->record=$record;
        $this->client_phone=$record->client_phone;
        $this->transaction_id='n'.$record->resource_id;
        $this->client=ClientsRecord::findOne(['id'=>$this->record->client_id]);
    }

    private function _prepare()
    {
        $appointed=$this->record->appointed;
        $appointed = \DateTime::createFromFormat('Y-m-d H:i:s',$appointed,new \DateTimeZone(\yii::$app->timeZone));
        $app = $appointed->format('d.m.Y H:i');
        $app = explode(' ', $app);
        $date = $app[0];
        $time = $app[1];
        // MASTER
        $staff=$this->record->staff_name;
        // NAME
        $msg=$this->message;
        if (strpos($msg,'%NAME%')>=0)
        {
            if (!$this->client)
                $msg=$this->msg_noname;
            else {
                $msg = str_replace('%NAME%', $this->client->shortName(), $msg);
            }
        }
        if (strpos($msg,'%HH%'))
        {
            $r=$appointed->diff(self::getCurDate());
            $r=self::roundDateTime($r);
            $hh=$r->format('H:i');
            $msg=str_replace('%HH%',$hh,$msg);
        }

        $msg=str_replace('%DATE%',$date,
                str_replace('%TIME%',$time,
                    str_replace('%MASTER%',$staff, $msg)
                )
        );

        return $msg;
    }

    public function __get($name)
    {
        if ($name=='HasError') return !empty($this->error);
        return parent::__get($name);
    }

    public static function sendReminder()
    {
        // Выбираем время
        $min=SettingsRecord::findValue('sms','second');
        $time=self::getCurDate();
        $c=self::getCurDate();
        $p="PT{$min}M";
        $time->add(new \DateInterval($p));
        // Выбираем клиентов кому нужно отправить SMS
        $query=RecordsRecord::find()
            ->where(['and','sms_second=0','deleted=0',
                ['<=','appointed',$time->format('Y-m-d H:i:s')],
                ['>','appointed',$c->format('Y-m-d H:i:s')]
            ]);
        $records=$query->all();
        if (count($records)==0) return 'Нет SMS';
        // Формируем текст сообщения
        foreach($records as $r)
        {
            $sms = new SMS();
            $sms->setNumber(1);
            $sms->setRecord($r);
            // Смотрим когда была создана заявка
            if ($sms->checkForSecond($min)) {
                // Отправляем
                $msg = $sms->getMessageText();
                //echo $msg;
                if (!$sms->Dontsend) {
                    Telegram::instance()->sendMessageAll($msg, $sms->client_phone);
                }
                $r->sms_second=1;
            }
            else
                $r->sms_second=3;
            $r->save();
        }

    }

    private function checkForSecond($min)
    {
        $ta=\DateTime::createFromFormat('Y-m-d H:i:s',$this->record->getAttribute('appointed'));
        $tc=\DateTime::createFromFormat('Y-m-d H:i:s',$this->record->getAttribute('created'));
        $ti=(($tc->getTimestamp()-$ta->getTimestamp())/60)+$min;
        return $ti<0;  //true SMS напоминание нужно
    }

    public static function sendSmsNumber($day)
    {
        $dat=new Date();
        $dat->subDays($day+1);
        $p1=$dat->toMySql();
        $dat=new Date();
        $dat->subDays($day);
        $p2=$dat->toMySql();

        $sql="select a.*
        from  clients a left join sms_done b on (a.id=b.client_id and a.last_record=b.record_id and b.type={$day})
        where a.deleted=0 and a.exception_{$day}<>1 and a.last_visit between '$p1' and '$p2' and b.type is null";
        $clients=\yii::$app->db->createCommand($sql)->queryAll();
        foreach($clients as $c)
        {
            $needsms=true;
            $rec=RecordsRecord::findOne(['resource_id'=>$c['last_record']]);
            if ($day==5)
            {
                /* проверка на скраббинг */
                $cm=ServicesRecord::find()->where(['and',"deleted<>1","id in ({$rec['services_id']})"])->sum('scrubbing');
                if ($cm==0) $needsms=false;
            }
            if ($day==21||$day==42)
            {
                /* Клиент уже записался */
                $cnt=RecordsRecord::find()->where([
                    'and',
                    'client_id='.$c['id'],
                    "appointed>'{$c['last_visit']}'"
                ])->count();
                if ($cnt>0)
                {
                    $needsms=false;
                }
                else{
                    /* Проверка на напоминание */
                    $cm=ServicesRecord::find()->where(['and',"deleted<>1","id in ({$rec['services_id']})"])->sum('remind');
                    if ($cm==0) $needsms=false;
                }
            }
            if ($needsms) {
                $sms = new SMS();
                $sms->setNumber($day);
                $sms->setRecord($rec);
                $sms->send();
                //Telegram::instance()->sendMessageAll($sms->getMessageText(), $sms->client_phone);
            }
            $done = new Sms_doneRecord();
            $done->setAttribute('type', $day);
            $done->setAttribute('client_id', $c['id']);
            $done->setAttribute('record_id', $c['last_record']);
            $done->save();
        }
        echo 'Обработано: '.count($clients).' клиентов';
    }

    public static function getCurDate()
    {
        return new \DateTime('now',new \DateTimeZone('Asia/Bishkek'));
    }

    /*
     * @param $time  \DateInterval
     * @param $min int
     */
    public static function roundDateTime($time, $min=5)
    {
        $s=$min*60;
        $t=\DateTime::createFromFormat('H:i',$time->format('%H:%I'));
        $t->setTimestamp($s * round($t->getTimestamp() / $s));
        return $t;
    }

    public function send()
    {
        $t=Telegram::instance();
        $t->sendMessageAll($this->getMessageText(),$this->client_phone);
        $sms=new SMSNikita();
        $sms->sendSMS($this->client_phone,$this->getMessageText(),$this->transaction_id);
    }
}