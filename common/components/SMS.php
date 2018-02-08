<?php
namespace common\components;

use common\models\SettingsRecord;
use common\models\SMSSettings;
use common\models\ClientsRecord;
use common\models\RecordsRecord;
use Faker\Provider\DateTime;
use yii\base\BaseObject;

class SMS extends BaseObject
{
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
        $this->client=ClientsRecord::findOne(['id'=>$this->record->client_id]);
    }

    private function _prepare()
    {
        $appointed=$this->record->appointed;
        $appointed = \DateTime::createFromFormat('Y-m-d H:i:s',$appointed,new \DateTimeZone('Asia/Bishkek'));
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
            ->where(['and','sms_second=0',
                ['<=','appointed',$time->format('Y-m-d H:i:s')],
                ['>','appointed',$c->format('Y-m-d H:i:s')]
            ]);
        $records=$query->all();
        if (count($records)==0) return 'Нет SMS';
        // Формируем текст сообщения
        foreach($records as $r)
        {
            $sms=new SMS();
            $sms->setNumber(1);
            $sms->setRecord($r);
            // Отправляем
            $msg=$sms->getMessageText();
            //echo $msg;
            if (!$sms->Dontsend) {
                Telegram::instance()->sendMessage('Alex', $msg);
            }
            $r->sms_second=1;
            $r->save();
        }

    }

    public static function getCurDate()
    {
        return new \DateTime('now',new \DateTimeZone('Asia/Bishkek'));
    }

    /**
     * @param \DateInterval $time
     * @param int $min
     */
    public static function roundDateTime($time, $min=5)
    {
        $s=$min*60;
        $t=\DateTime::createFromFormat('H:i',$time->format('%H:%I'));
        $t->setTimestamp($s * round($t->getTimestamp() / $s));
        return $t;
    }
}