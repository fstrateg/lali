<?php
namespace app\models;

use common\models\StaffPropRecord;
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

        self::sinchro_staff($req->post('laser'),1);
        self::sinchro_staff($req->post('vosk'),2);


    }

    public static function sinchro_staff($rws, $prop)
    {
        $keys=StaffPropRecord::findAll(['prop_id'=>$prop]);
        $c=count($keys);
        $i=0;
        foreach($rws as $key=>$vl)
        {
            if ($vl) {
                $pp = ($i < $c) ? $keys[$i++] : new StaffPropRecord();
                $pp->setAttribute('staff_id', $key);
                $pp->setAttribute('prop_id', $prop);
                $pp->setAttribute('vl',1);
                $pp->save();
            }
        }
        while($i<$c)
        {
            $keys[$i++]->delete();
        }

    }
}