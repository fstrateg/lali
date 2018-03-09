<?php
namespace frontend\models;

use common\models\ClientsRecord;
use common\models\RecordsRecord;
use \yii\db\ActiveRecord;
use \common\models\ServicesRecord;

class YclientsLogRecord extends ActiveRecord
{
    public static $cfg=['company'=>31224];
    public static function tableName()
    {
        return 'yclientslog';
    }

    public static function doParse()
    {
        $rws=YclientsLogRecord::find()->where(['done'=>0])->all();
        foreach($rws as $rw)
        {
            $rw->done=1;
            if ($rw->data) {
                try {
                    $global = json_decode(stripslashes($rw->data), true);
                    $company_id = $global['company_id'];
                    $resource = $global['resource']; // record, service, client
                    $resource_id = $global['resource_id'];
                    $status = $global['status']; // create, update, delete
                    $data = $global['data'];
                    if ($company_id == YclientsLogRecord::$cfg['company']) {
                        $table = null;
                        if ($resource == 'client') {
                            $table = ClientsRecord::initRec($resource_id, $data);
                        } elseif ($resource == 'record') {
                            $table = RecordsRecord::initRec($resource_id, $data);
                        } elseif ($resource == 'service') {
                            $table = ServicesRecord::initRec($resource_id, $data);
                        }
                        $table->status = $status;
                        if ($status=='delete')
                            $table->deleted=1;
                        $table->save();
                    }
                }catch(\Exception $e)
                {
                    \common\components\Telegram::instance()->sendMessage('Alex',$e->getTraceAsString(),$e->getMessage());
                    $rw->done=2;
                }
            }
            $rw->save();
        }
    }
}