<?php
use yii\grid\GridView;
use yii\helpers\Html;
use \backend\models\Users;
use yii\helpers\Url;

/**
 * @var $this yii\web\View
 */
$this->title="Мастер подвязка пользователя";
$users=Users::getUsersList();
$susers=\common\models\StaffUserRecord::getUsersForStaff();
$dd=isset($susers[$model->id])?$susers[$model->id]['user_id']:0;
if ($model->iswork=='Y')
{
    $checked='checked';
    $uncheck='';
}
else
{
    $checked='';
    $uncheck='checked';
}
?>
<form class="form-vertical" method="post">
    <div class="form-group">
        <b>Мастер: </b></br>
        <?= $model->name ?>
    </div>
    <div class="form-group">
        <div class="row">
            <label class="col-sm-4">Филиал:</label>
        </div>
        <div class="row">
            <div class="col-sm-4">
                <?= Html::textInput("fil",$model->fil,['class'=>'form-control']); ?>
            </div>
        </div>
    </div>
    <div class="form-group">
        <div class="row">
            <label class="col-sm-4">Видео:</label>
        </div>
        <div class="row">
            <div class="col-sm-4">
                <?= Html::textInput("video",$model->video,['class'=>'form-control']); ?>
            </div>
        </div>
    </div>
    <div class="form-group">
        <div class="row">
            <label class="col-sm-4">Работает:</label>
        </div>
        <div class="row">
            <div class="col-sm-2">
                <input id="iswork" type="radio" name="iswork" value="Y" <?= $checked?>>
                <label for="iswork">Да</label>
            </div>
            <div class="col-sm-2">
                <input id="nowork" type="radio" name="iswork" value="N" <?= $uncheck?>>
                <label for="nowork">Нет</label>
            </div>
        </div>
    </div>
    <div class="form-group">
        <div class="row">
            <label class="col-sm-4">Подвязать пользователя:</label>
        </div>
        <div class="row">
            <div class="col-sm-4">
                <?= Html::dropDownList('userid',$dd,$users,['class'=>'form-control']) ?>
            </div>
        </div>

    </div>
    <input type="hidden" name="m" value="save">
    <input type="hidden" name="id" value="<?= $model->id; ?>">
    <input type="hidden" name="_csrf-backend" value="<?=Yii::$app->request->getCsrfToken()?>" />
    <button type="submit" class="btn btn-success" style="width:100px">Сохранить</button>
    <button type="button" class="btn btn-default" style="width:100px;margin-left: 10px" onclick="window.history.back()">Отмена</button>
</form>
