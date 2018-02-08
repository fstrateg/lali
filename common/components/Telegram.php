<?php
namespace common\components;

use common\models\User;
use yii\base\Component;

class Telegram extends Component
{
    public $class = '';
    public $token = '';
    public $apiUrl = '';

    function __construct(array $config=[])
    {
        $config=\Yii::$app->components['telegram'];
        parent::__construct($config);
    }

    /**
     * @param $chat_id
     * @param $msg
     */
    public function sendMessage($userName, $msg, $forinfo='')
    {
        $user=User::findOne(['username'=>$userName]);
        if (!$user&&$user->telegram) return;

        $data=[];
        $data['parse_mode']='html';
        $data['chat_id']=$user->telegram;
        $data['text'] = $forinfo?"<b>{$forinfo}</b>\r\n".$msg:$msg;


        $options = array(
            'http' => array(
                'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
                'method'  => 'POST',
                'content' => http_build_query($data),
            ),
        );
        $context  = stream_context_create($options);

        $result = file_get_contents("{$this->apiUrl}bot{$this->token}/sendMessage", false, $context);
        //print_r($result);
        //$query = "{$this->apiUrl}{$this->token}/{$api_method}";
    }

    /**
     * @return Telegram
     */
    public static function instance()
    {
        return new Telegram();
    }
}