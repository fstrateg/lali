<?php
use yii\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;

/**
 * @var $this yii\web\View
 */
$this->title="Мастер подвязка пользователя";
$users=['1'=>'Alexey','2'=>'Alex'];
?>
<form class="form-vertical">
    <div class="form-group">
        <b>Мастер: </b></br>
        <?= $model->name ?>
    </div>
    <div class="form-group">
        <div class="row">
            <label class="col-sm-3">Подвязать пользователя:</label>
        </div>
        <div class="row">
            <div class="col-sm-3">
                <?= Html::dropDownList('userid','2',$users,['class'=>'form-control']) ?>
            </div>
        </div>

    </div>
    <button type="submit" class="btn btn-success" style="width:100px">Сохранить</button>
    <button type="button" class="btn btn-default" style="width:100px;margin-left: 10px" onclick="window.history.back()">Отмена</button>
</form>
