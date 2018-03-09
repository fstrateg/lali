<?php
namespace frontend\models;

use common\models\ClientsRecord;
use common\models\SettingsRecord;
use yii;

class YclientsImport
{
    var $token;
    var $user;
    var $company;
    var $count;

    function __construct()
    {
        ini_set('max_execution_time', 900);
        $cfg=SettingsRecord::getValuesGroup('yclients');
        //$cfg=yii::$app->components['yclients'];
        $this->token=$cfg['token'];
        $this->user=$cfg['user'];
        $this->company=31224;//$cfg['company'];
    }

    public function import()
    {
        yii::$app->db->createCommand('truncate table clients')->execute();
        $page=1;
        while ($this->getpage($page))
        {
            $page++;

        }

    }

    public function getpage($page)
    {
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, "http://api.yclients.com/api/v1/clients/{$this->company}?page={$page}&count=50");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_HEADER, FALSE);

        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            "Content-Type: application/json",
            "Authorization: Bearer {$this->token}, User {$this->user}"
        ));

        $response = curl_exec($ch);
        curl_close($ch);
        $response=json_decode($response,true);
        $this->loadClients($response);
        return count($response['data'])>0;
    }

    private function loadClients($response)
    {
        foreach ($response['data'] as $item)
        {
            $cl=new ClientsRecord();
            $cl->id=$item['id'];
            $cl->name=$item['name'];
            $cl->phone=$item['phone'];
            $cl->status='import';
            $cl->save();
        }
    }

    public function getKlient($id)
    {
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, "http://api.yclients.com/api/v1/client/{$this->company}/{$id}");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_HEADER, FALSE);

        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            "Content-Type: application/json",
            "Authorization: Bearer {$this->token}, User {$this->user}"
        ));

        $response = curl_exec($ch);
        curl_close($ch);
        $item=json_decode($response,true);
        if (is_array($item))
        {
            $cl=ClientsRecord::findOne('id='.$id);
            if ($cl) return true;
            $cl=new ClientsRecord();
            $cl->id=$item['id'];
            $cl->name=$item['name'];
            $cl->phone=$item['phone'];
            $cl->status='import';
            $cl->save();
            return true;
        }
        return false;
    }
}