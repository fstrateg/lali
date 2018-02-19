<?php
/**
 * Created by PhpStorm.
 * User: Alexey
 * Date: 16.02.2018
 * Time: 16:44
 */

namespace frontend\models;

use yii;
use common\components\Date;

class JobsModel extends \stdClass
{

    public static function GetLastVisit()
    {
        $dt=new Date();
        $dt->subDays('42');
        $db=yii::$app->getDb();
        $query=$db->createCommand("SELECT a.appointed,b.id,b.last_visit FROM records a,clients b
WHERE (a.appointed>:dt) AND (a.attendance=1) AND (a.deleted=0)
 and b.id=a.client_id and b.deleted=0 and b.last_visit<a.appointed",['dt'=>$dt->toMySqlRound()]);
        $rs=$query->queryAll();
        foreach($rs as $rw)
        {
            $db->createCommand('update clients set last_visit=:lv where id=:id',['lv'=>$rw['appointed'],'id'=>$rw['id']])->execute();
            $db->createCommand('delete from sms_done where client_id=:id',['id'=>$rw['id']])->execute();
        }
        echo 'OK';
    }
}