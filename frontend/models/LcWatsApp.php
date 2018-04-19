<?php
namespace frontend\models;

use common\components\Date;
use common\models\SettingsRecord;
use yii;

/**
 * Class LcWatsApp
 * @package frontend\models
 */
class LcWatsApp
{
    /**
     * @var $dat Date
     */
    var $dat;
    /**
     * @var $dat_laser Date
     */
    var $dat_laser;
    /**
     * @var $dat_laser Date
     */
    var $dat_wax;

    var $cfg;

    public $days_laser;
    public $days_wax;

    public function init($sdat)
    {
        $this->dat=new Date();
        if ($sdat) $this->dat->set($sdat,'Ymd');
        $this->cfg=SettingsRecord::getValuesGroup('quality');
        $day=$this->cfg['laser']; //SettingsRecord::findValue('quality',);
        $this->days_laser=$day;
        $dd=clone $this->dat;
        $dd->subDays($day);
        $this->dat_laser=$dd;

        $dd=clone $this->dat;
        $day=$this->cfg['wax'];
        $this->days_wax=$day;
        $dd->subDays($day);
        $this->dat_wax=$dd;
    }

    public function getLaserMsg()
    {
        return $this->cfg['lasermsg'];
    }

    public function getWaxMsg()
    {
        return $this->cfg['waxmsg'];
    }

    public function getCaclDate()
    {
       return $this->dat->format();
    }

    public function getDateLaser()
    {
        return $this->dat_laser->format();
    }

    public function getDateWax()
    {
        return $this->dat_wax->format();
    }

    public function getParamNext()
    {
        /* @var $dd Date */
        $dd=clone $this->dat;
        $dd->addDays(1);
        return $dd->format('Ymd');
    }

    public function getParamPrior()
    {
        /* @var $dd Date */
        $dd=clone $this->dat;
        $dd->subDays(1);
        return $dd->format('Ymd');
    }

    public static function getServices($services_id)
    {
        $cmd= yii::$app->db->createCommand("
select title from services a
where a.id in ($services_id)");
        return $cmd->queryAll();
    }
/**
 * @param $dat string
 */
    public function findLaserRecords()
    {
        /*$day=SettingsRecord::findValue('quality','laser');
        $dd=new Date();
        $dd->set($dat);
        $dd->subDays($day);
        //$dat->subDays($day+60);*/
        $p1=$this->dat_laser->toMySqlRound();
        $cmd= yii::$app->db->createCommand('
SELECT a.resource_id,a.staff_name,a.appointed,a.services_id,a.client_phone,b.name,c.title,ifnull(q.status,0) stat
FROM records a left join clients b on b.id=a.client_id
	left join services c on trim(c.id)=a.services_id
    left join quality q on a.resource_id=q.record_id and q.typ=1
WHERE a.id IN (
SELECT DISTINCT a.id
FROM (
SELECT a.*
FROM records a
WHERE date(appointed)=\''.$p1.'\' AND a.attendance=1 and a.deleted=0
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

    /**
     * @param $dat string
     * @return array
     */
    public function findWaxRecords()
    {
       /* $day=SettingsRecord::findValue('quality','wax');
        $dat=new Date();
        $dat->set($pdat);
        $dat->subDays($day);
        //$dat->subDays($day+60);*/
        $p1=$this->dat_wax->toMySqlRound();
        $cmd= yii::$app->db->createCommand('
SELECT a.resource_id,a.staff_name,a.appointed,a.services_id,a.client_phone,b.name,c.title,ifnull(q.status,0) stat,a.client_id
FROM records a
    inner join staff_prop s on a.staff_id=s.staff_id and s.prop_id=2
    left join clients b on b.id=a.client_id
	left join services c on trim(c.id)=a.services_id
    left join quality q on a.resource_id=q.record_id  and q.typ=2
WHERE a.id IN (
SELECT DISTINCT a.id
FROM (
SELECT a.*
FROM records a
WHERE date(appointed)=\''.$p1.'\' AND a.attendance=1 and a.deleted=0
) a,services b
WHERE INSTR(a.services_id,b.id)>0 AND b.scrubbing=1
)
order by a.appointed
    ');
        //echo $cmd->sql;
        $list=$cmd->queryAll();
        foreach($list as $k=>$rw)
        {
            $name = str_replace('ё', 'е', $rw['name']);
            preg_match("/([a-z]|[а-я])+/ui", $name, $matches);
            if (preg_match("/([a-z]|[а-я])+/ui", $name, $matches))
                $list[$k]['name']=$matches[0];
            else
            {
                unset($list[$k]);
            }
        }
        if ($this->cfg['onnew']) $list=$this->filter_vax_onlynew($list,$p1);
        return $list;
    }

    private function filter_vax_onlynew($list,$dat)
    {
        $ids=[];
        foreach ($list as $item) $ids[]=$item['client_id'];
        $usl=implode(',',$ids);
        if ($usl) $usl=" where client_id in ($usl) and date(appointed)<='".$dat."' AND attendance=1 and deleted=0";
        $cmd= yii::$app->db->createCommand('
Select client_id,count(1) cnt
from records'
            .$usl.' group by client_id');
        $table=$cmd->queryAll();
        $table=yii\helpers\ArrayHelper::index($table,'client_id');
        foreach ($list as $k=>$item)
        {
            if (((int)$table[$item['client_id']]['cnt'])>1) unset($list[$k]);
        }
        return $list;
    }
}