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
    }
}