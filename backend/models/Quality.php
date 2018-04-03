<?php
namespace app\models;

use Yii;
use yii\db\ActiveRecord;
use yii\web\Request;
use common\models\SettingsRecord;

class Quality extends ActiveRecord
{
    /**
     * @param $req Request
     */
    public static function qualitySave($req)
    {
        SettingsRecord::setValue('quality','laser',$req->post('ldays'));
        SettingsRecord::setValue('quality','wax',$req->post('vdays'));

        // берем все из свойств

    }
}