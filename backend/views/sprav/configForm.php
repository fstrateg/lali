<?php
use yii\widgets\ActiveForm;
use yii\helpers\Html;
/* @var $this yii\web\View
 * @var $model backend\models\SettingsRecord
 */
$form=ActiveForm::begin();
echo $model->getAttribute('name');
echo $form->field($model,'val')->textInput()->label(false);
?>
<div class="form-group">
    <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
    <?= Html::a('Отменить','#',['class'=>'btn btn-danger']) ?>
</div>
<?
ActiveForm::end();
?>