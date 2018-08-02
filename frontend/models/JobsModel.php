<?php
/**
 * Created by PhpStorm.
 * User: Alexey
 * Date: 16.02.2018
 * Time: 16:44
 */

namespace frontend\models;

use common\models\RecordsRecord;
use yii;
use common\components\Date;

class JobsModel extends \stdClass
{

    public static function GetLastVisit()
    {
        $dt=new Date();
        $dt->subDays('2');
        $db=yii::$app->getDb();
        $query=$db->createCommand("SELECT r.*,a.resource_id last_record
FROM (
SELECT MAX(a.appointed) appointed,b.id, MAX(b.last_visit) last_visit
FROM records a,clients b
WHERE (a.appointed>:dt) AND (a.attendance=1)
AND (a.deleted=0) AND b.id=a.client_id AND b.deleted=0
AND b.last_visit<a.appointed
GROUP BY b.id) r,records a
WHERE a.client_id=r.id AND a.appointed=r.appointed",['dt'=>$dt->toMySqlRound()]);
        $rs=$query->queryAll();
        foreach($rs as $rw)
        {
            $db->createCommand('update clients set last_visit=:lv,last_record=:lr where id=:id',
                    [   'lv'=>$rw['appointed'],
                        'lr'=>$rw['last_record'],
                        'id'=>$rw['id']
                    ])->execute();
            $db->createCommand('delete from sms_done where client_id=:id',['id'=>$rw['id']])->execute();
        }
        echo count($rs);
        echo '<br>OK';
    }

    public static function getNaprav()
    {
       $recs=RecordsRecord::find()->where(['naprav'=>'N'])->limit(500)->all();
        if ($recs==null)
        {
            echo 'Нет записей для апдейта!';
            return;
        }
        foreach($recs as $rw)
        {
            $sid=$rw->getAttribute('services_id');
            $rw->naprav=self::findNaprav($sid);
            $rw->save();
        }
        echo 'Записей '.count($recs);
    }

    public static function findNaprav($services_id)
    {
        if (empty($services_id)) return 'A';
        $cmd= yii::$app->db->createCommand("
select title,laser,scrubbing from services a
where a.id in ($services_id)");
        $rows=$cmd->queryAll();
        if ($rows==null) return 'A';
        $scrub=false;
        foreach($rows as $rw)
        {
            if ($rw['laser']=='Y') return 'L';
            if ($rw['scrubbing']==1) $scrub=true;
        }
        if ($scrub) return 'W';
        return 'A';
    }
}