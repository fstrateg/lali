<?php
use yii\widgets\ActiveForm;
use common\models\SettingsRecord;
use \yii\bootstrap\Alert;
/**
 * @var $this yii\web\View
 */
$this->title="Контроль качества. Настройка сообщений.";
$form=ActiveForm::begin();

?>
<h3><?= $this->title ?></h3>
<?
    if (yii::$app->getSession()->hasFlash('ok'))
    {
        echo Alert::widget([
            'options' => [
                'class' => 'alert-success'
            ],
            'body' => Yii::$app->session->getFlash('ok')
        ]);
    }
    $s=SettingsRecord::findOne(['group'=>'quality','param'=>'lasermsg']);
    echo '<label>'.$s->getAttribute('name').'</label>';
    echo $form->field($s,'val')->textarea(['class'=>'form-control','rows'=>'2','name'=>"lasermsg"])->label(false);
    $s=SettingsRecord::findOne(['group'=>'quality','param'=>'waxmsg']);
    echo '<label>'.$s->getAttribute('name').'</label>';
    echo $form->field($s,'val')->textarea(['class'=>'form-control','rows'=>'2','name'=>"waxmsg"])->label(false);
?>
<input type="submit" class="btn btn-success" value="Сохранить">
<?
ActiveForm::end();
?>