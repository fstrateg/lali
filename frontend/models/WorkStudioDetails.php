<?php
namespace frontend\models;

use common\models\ClientsRecord;
use common\models\RecordsRecord;

class WorkStudioDetails
{
    public $klientName;
    public $id;
    public $sms_00;
    public $sms_01;
    public $sms_05;
    public $sms_21;
    public $sms_42;
    public $sms_21val;
    public $sms_42val;

    public function initRecord($id)
    {
        $this->id=$id;
        $record=RecordsRecord::findOne(['resource_id'=>$id]);
        $client=ClientsRecord::findOne(['id'=>$record->client_id]);
        $this->klientName=$client->name;
        //$this->sms_00=$client->exception_0==1?0:1;
        $this->sms_01=$client->exception_1==1?"":"checked";
        $this->sms_05=$client->exception_5==1?"":"checked";
        $this->sms_21=$client->exception_21==1?"":"checked";
        $this->sms_42=$client->exception_42==1?"":"checked";
        $this->sms_21val=$client->sms_21_sent;
        $this->sms_42val=$client->sms_42_sent;
    }

    /**
     * @param $data array
     * @return bool
     */
    public function saveData($data)
    {
        /**
         * @var $client ClientsRecord
         */
        $this->id=$data->id;
        $record=RecordsRecord::findOne(['resource_id'=>$data->id]);
        $client=ClientsRecord::findOne(['id'=>$record->client_id]);
        if ($client)
        {
            $client->setAttribute('exception_1',$data->d1==0?1:0);
            $client->setAttribute('exception_5',$data->d5==0?1:0);
            $client->setAttribute('exception_21',$data->d21==0?1:0);
            $client->setAttribute('exception_42',$data->d42==0?1:0);
            $client->setAttribute('sms_21_sent',$data->d21val);
            $client->setAttribute('sms_42_sent',$data->d42val);
            $client->save();
        }
        return true;
    }
}