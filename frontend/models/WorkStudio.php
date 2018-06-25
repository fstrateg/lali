<?php
namespace frontend\models;

use common\models\StaffRecord;
use common\models\StaffUserRecord;
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
}