<?php
namespace frontend\models;

use yii;

class LcWatsApp
{
    public static function getRecords()
    {
       $cmd= yii::$app->db->createCommand('
SELECT a.resource_id,a.staff_name,a.appointed,a.services_id,a.client_phone,b.name,c.title
FROM records a left join clients b on b.id=a.client_id
	left join services c on trim(c.id)=a.services_id
WHERE a.id IN (
SELECT DISTINCT a.id
FROM (
SELECT a.*
FROM records a
WHERE appointed between \'2018-02-01\' and \'2018-04-01\' AND a.attendance=1 and a.deleted=0
) a,services b
WHERE INSTR(a.services_id,b.id)>0 AND b.laser=\'Y\'
)
    ');
        $list=$cmd->queryAll();
        foreach($list as $k=>$rw)
        {
            $name = str_replace('ё', 'е', $rw['name']);
            preg_match("/([a-z]|[а-я])+/ui", $name, $matches);
            $list[$k]['name']=$matches[0];
        }
        return $list;
    }
}