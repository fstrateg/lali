<?php
namespace common\models;
use Yii;
/**
* Class ServicesRecord
* @package common\models
* @property $id
* @property $name
* @property $created
*/
class StaffRecord extends \yii\db\ActiveRecord
{
    public static function tableName()
    {
        return '{{%staff}}';
    }

    public function rules()
    {
        return [
        [['id'],'integer'],
        [['staff'],'string'],
        ];
    }

    public static function RefreshAll()
    {
        ini_set('max_execution_time', 900);
        $cfg=SettingsRecord::getValuesGroup('yclients');

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, "http://api.yclients.com/api/v1/staff/{$cfg['company']}");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_HEADER, FALSE);

        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            "Content-Type: application/json",
            "Authorization: Bearer {$cfg["token"]}"
        ));

        $response = curl_exec($ch);
        curl_close($ch);
        $response=json_decode($response,true);
        print_r($response);
    }
}
