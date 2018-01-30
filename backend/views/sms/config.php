<?php
use app\models\SMSSettings;
use yii\widgets\ActiveForm;
/* @var $this yii\web\View
 *  @var $pages array
 */

$this->title = 'Настройка СМС рассылок';
?>
<ul class="nav nav-tabs">
<?
$class=' class="active"';
foreach($pages as $page)
{?>
    <li<?= $class;?>><a data-toggle="tab" href="#page<?= $page['id']?>"><?= $page['name'] ?></a></li>
 <?
    $class='';
} ?>
</ul>
<div class="tab-content tab-bordered">
<?
    $class=' in active';
    foreach($pages as $page)
    {
        $form=ActiveForm::begin();
        ?>
        <div id="page<?= $page['id'] ?>" class="panel-group pt1 tab-pane fade<?= $class ?>">
            <?
            $set=SMSSettings::find(['city'=>$page['id']])->orderBy('days')->all();
            foreach($set as $s) {
                $id=$s['id'];?>
                <div class="panel panel-default">
                    <div class="panel-heading"><?= $s->name; ?></div>
                    <div class="panel-body">
                            <div class="checkbox">
                                <label class="form-check-label">
                                    <input type="hidden" name="r[<?= $id ?>][sms_on]" value="0">
                                    <input type="checkbox" name="r[<?= $id ?>][sms_on]" class="form-check-input" value="1"<?= $s['sms_on']?' checked':'';?>>Включено</label>
                                    <? //$form->field($s,'sms_on')->checkbox(['class'=>'form-check-input','label'=>null,'name'=>"r[$id][sms_on]"]); ?>
                            </div>
                            <span id="helpBlock" class="help-block">Используйте вырожение %NAME% для подстановки имени в SMS</span>
                            <?= $form->field($s,'sms_text')->textarea(['class'=>'form-control','rows'=>'3','name'=>"r[$id][sms_text]"])->label(false); ?>
                            <!-- textarea class="form-control" rows="3" name="r[?= $id ?][sms_text]">?= $s['sms_text']; ?</textarea-->
                    </div>
                </div>
            <? }
            ?>
            <button type="submit" class="mt2 btn btn-success">Сохранить изменения</button>
        </div>
    <?
        ActiveForm::end();
    $class='';
    } ?>
</div>
