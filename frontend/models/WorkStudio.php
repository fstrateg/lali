<?php
namespace frontend\models;

use common\models\StaffRecord;
use common\models\StaffUserRecord;
use common\models\RecordsRecord;
use yii;

class WorkStudio
{
    /**
     * @var string $staffname
     */
    public $staffname;
    /**
     * @var int $staffid;
     */
    public $staffid=-1;

    public function __construct()
    {
        $this->staffname="";
        $rw=StaffUserRecord::findOne(['user_id'=>yii::$app->user->id]);
        if ($rw) {
            $st=StaffRecord::findOne($rw->staff_id);
            if ($st) {
                $this->staffname=$st->name;
                $this->staffid=$st->id;
            }
        }


    }

    public function init($dat)
    {

    }

    public function getRecordsForStaff()
    {
        $db=yii::$app->db;
        return $db->createCommand("Select a.resource_id id,a.appointed,a.client_id,b.name client,a.services_id
        from records a,clients b where a.staff_id={$this->staffid}
        and a.appointed like '2018-03-06%'
        and a.deleted=0
        and b.id=a.client_id
        and b.deleted=0
        order by a.appointed")
            ->queryAll();
    }

    public static function getServices($services_id)
    {
        $cmd= yii::$app->db->createCommand("
select title from services a
where a.id in ($services_id)");
        return $cmd->queryAll();
    }
}