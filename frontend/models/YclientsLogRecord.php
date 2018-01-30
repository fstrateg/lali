<?php
namespace frontend\models;

use common\models\RecordsRecord;
use \yii\db\ActiveRecord;
use \common\models\ServicesRecord;

class YclientsLogRecord extends ActiveRecord
{
    public static $cfg=['ip'=>'88.99.145.254','company'=>31224];
    public static function tableName()
    {
        return 'yclientslog';
    }

    public static function doParse()
    {
        $rws=YclientsLogRecord::find()->where(['done'=>0])->all();
        foreach($rws as $rw)
        {
            if ($rw->ip==YclientsLogRecord::$cfg['ip']) {
                $global = json_decode($rw->data, true);
                $company_id = $global['company_id'];
                $resource = $global['resource']; // record, service, client
                $resource_id = $global['resource_id'];
                $status = $global['status']; // create, update, delete
                $data = $global['data'];
                if ($company_id == YclientsLogRecord::$cfg['company']) {
                    $table = null;
                    if ($resource == 'client') {

                    } elseif ($resource == 'record') {
                        $table = RecordsRecord::initRec($resource_id, $data);
                    } elseif ($resource == 'service') {
                        $table = ServicesRecord::initRec($resource_id, $data);
                    }
                    $table->status = $status;
                    $table->save();
                }
            }
            $rw->done=1;
            $rw->save();
        }
    }
}