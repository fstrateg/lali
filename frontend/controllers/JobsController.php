<?php
namespace frontend\controllers;

use backend\models\SettingsRecord;
use common\components\Date;
use common\components\SMS;
use common\models\SMSSettings;
use frontend\models\YclientsImport;
use frontend\models\YclientsLogRecord;
use frontend\models\JobsModel;
use Yii;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;


/**
 * Site controller
 */
class JobsController extends Controller
{
    public function beforeAction($action)
    {
        if (in_array($action->id, ['yclients'])) {
            $this->enableCsrfValidation = false;
        }
        return parent::beforeAction($action);
    }
    /**
     * @inheritdoc
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    /**
     * Прием данных с хука
     */
    public function actionYclients()
    {
        $log=new YclientsLogRecord();
        $curdate=new \DateTime('now',new \DateTimeZone('Asia/Bishkek'));
        $log->dat=$curdate->format('Y-m-d H:i:s');
        $log->ip= $_SERVER["REMOTE_ADDR"];
        $jsonPostData = file_get_contents("php://input");
        $log->data=$jsonPostData;
        $log->insert();
        YclientsLogRecord::doParse($log);
        //$this->actionDoyclients();
        return 'OK';
    }

    /**
     * Парсинг данных
     */
    public function actionDoyclients()
    {
        $rws=YclientsLogRecord::find()->where(['done'=>0])->all();
        foreach($rws as $rw)
        {
            YclientsLogRecord::doParse($rw);
        }
        //YclientsLogRecord::doParse();
    }

    /**
    * Отправка тестовой СМС в телеграм
    */
    public function actionTestsms()
    {
        \common\components\Telegram::instance()->sendMessage('Alex','Тест прошел успешно!','test');
    }

    /**
     * СМС напоминание о необходимости прийти на сеанс
     */
    public function actionSendreminder()
    {
        // запускаем каждые 5 мин, по идее не должно тормазить
        SMS::sendReminder();
    }

    /**
    * Импорт справочника клиентов
    */
    public function actionLoadclients()
    {

        /*$m=new YclientsImport();
        $m->import();*/
        return 'Закрыто чтобы случайно не потереть';
    }

    /**
    * Тестовый метод
    */
    public function actionTest()
    {
        $id=4533;
        $sms=new SMS();
        $sms->setNumber(1);
        $sms->setRecord($id);
        echo $sms->getMessageText();

    }

    /**
    * Напоминание через N дней
    */
    public function actionSendday($day)
    {
        if (empty($day)||!in_array($day,['5','21','42'])) return;
        ini_set('max_execution_time', 600);
        SMS::sendSmsNumber($day);
    }

    public function actionGetlastvisit()
    {
        JobsModel::GetLastVisit();
    }
}
