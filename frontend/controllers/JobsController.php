<?php
namespace frontend\controllers;

use common\components\SMS;
use frontend\models\YclientsLogRecord;
use frontend\models\YclientsImport;
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

    public function actionYclients()
    {
        $log=new YclientsLogRecord();
        $curdate=new \DateTime('now',new \DateTimeZone('Asia/Bishkek'));
        $log->dat=$curdate->format('Y-m-d H:i:s');
        $log->ip= $_SERVER["REMOTE_ADDR"];
        $jsonPostData = file_get_contents("php://input");
        $log->data=$jsonPostData;
        $log->insert();
        $this->actionDoyclients();
        return 'OK';
    }

    public function actionDoyclients()
    {
        YclientsLogRecord::doParse();
    }

    public function actionTest()
    {
        \common\components\Telegram::instance()->sendMessage('Alex','Тест прошел успешно!');
    }

    public function actionSendreminder()
    {
        // запускаем каждые 5 мин, по идее не должно тормазить
        SMS::sendReminder();
    }

    public function actionLoadclients()
    {

        /*$m=new YclientsImport();
        $m->import();*/
        return 'Закрыто чтобы случайно не потереть';
    }
}
