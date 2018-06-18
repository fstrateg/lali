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

    public static function doParse($rw)
    {
        //$rws=YclientsLogRecord::find()->where(['done'=>0])->all();
        //foreach($rws as $rw)
        //{
        $op=['create'=>'AP','update'=>'ED','delete'=>'DE'];
            if ($rw->data) {
                try {
                    $global = json_decode(stripslashes($rw->data), true);
                    $company_id = $global['company_id'];
                    $resource = $global['resource']; // record, service, client
                    $resource_id = $global['resource_id'];
                    $status = $global['status']; // create, update, delete
                    $data = $global['data'];

                    $rw->resource=$resource;
                    $rw->resource_id=$resource_id;
                    $rw->oper=$op[$status];
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
                        $table->num=$rw->id;
                        if ($status=='delete')
                            $table->deleted=1;
                        else
                            $table->deleted=0;
                        $table->save();
                    }
                    $rw->done=1;
                }catch(\Exception $e)
                {
                    \common\components\Telegram::instance()->sendMessage('Alex',$e->getTraceAsString(),$e->getMessage());
                    $rw->done=2;
                }
            }
            else
            {
                $rw->done=3;
            }
            $rw->save();
        //}
    }
}