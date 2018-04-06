<?php
namespace frontend\models;

use common\components\Date;
use common\models\SettingsRecord;
use yii;

class LcWatsApp
{
    public static function getRecords($prop_id)
    {
        $day=SettingsRecord::findValue('laser','daywhatsap');
        $dat=new Date();
        //$dat->subDays($day+1);
        $dat->subDays($day+60);
        $p1=$dat->toMySqlRound();
        $dat=new Date();
        $dat->subDays($day);
        $p2=$dat->toMySqlRound();

       $cmd= yii::$app->db->createCommand('
SELECT a.resource_id,a.staff_name,a.appointed,a.services_id,a.client_phone,b.name,c.title
FROM records a left join clients b on b.id=a.client_id
	left join services c on trim(c.id)=a.services_id
WHERE a.id IN (
SELECT DISTINCT a.id
FROM (
SELECT a.*
FROM records a
WHERE appointed between \''.$p1.'\' and \''.$p2.'\' AND a.attendance=1 and a.deleted=0
) a,services b
WHERE INSTR(a.services_id,b.id)>0 AND b.laser=\'Y\'
)
order by a.appointed
    ');
        //echo $cmd->sql;
        $list=$cmd->queryAll();
        foreach($list as $k=>$rw)
        {
            $name = str_replace('ё', 'е', $rw['name']);
            preg_match("/([a-z]|[а-я])+/ui", $name, $matches);
            $list[$k]['name']=$matches[0];
        }
        return $list;
    }

    public static function getServices($services_id)
    {
        $cmd= yii::$app->db->createCommand("
select title from services a
where a.id in ($services_id)");
        return $cmd->queryAll();
    }

}