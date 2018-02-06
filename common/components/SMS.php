<?php
namespace common\components;

use common\models\SMSSettings;
use common\models\ClientsRecord;
use common\models\RecordsRecord;
use Faker\Provider\DateTime;
use yii\base\BaseObject;

class SMS extends BaseObject
{
    private $message;
    private $msg_noname;
    private $record;
    public $Dontsend;
    public $client_phone;
    public $HasError=false;
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
    }

    private function _prepare()
    {
        $appointed=$this->record->appointed;
        $appointed = \DateTime::createFromFormat('Y-m-d H:i:s',$appointed);
        $appointed = $appointed->format('d.m.Y H:i');
        $appointed = explode(' ', $appointed);
        $date = $appointed[0];
        $time = $appointed[1];
        // MASTER
        $staff=$this->record->staff_name;
        // NAME
        $client=ClientsRecord::findOne(['id'=>$this->record->client_id]);
        $msg=$this->message;
        $name='';
        if (!$client)
           $msg=$this->msg_noname;
        else $name=$client->shortName();

        $msg=str_replace('%DATE%',$date,
                str_replace('%TIME%',$time,
                    str_replace('%NAME%',$name,
                        str_replace('%MASTER%',$staff,
                        $msg)
                    )
                )
        );

        return $msg;
    }

    public function __get($name)
    {
        if ($name=='HasError') return !empty($this->error);
        return parent::__get($name);
    }
}