<?php
use common\models\StaffRecord;
use common\models\SettingsRecord;
use yii\widgets\ActiveForm;
/**
 * @var $this yii\web\View
 */
$this->title="Контроль качества";

$ldays=SettingsRecord::findValue('quality','laser');
$vdays=SettingsRecord::findValue('quality','wax');

function outListStaff($name)
{
    $rs=StaffRecord::find()->orderBy('name')->where(['deleted'=>0])->all();
    echo '<ul class="list">';
    foreach($rs as $s) {
        $ck="<input type='checkbox' name='{$name}[{$s['id']}]' value='1'> {$s['name']}";
        $hd="<input type='hidden' name='{$name}[{$s['id']}]' value='0'>";
        echo "<li class='$name'>$hd $ck</li>";
    }
    echo '</ul>';
}
$url=\yii\helpers\Url::to('/admin/app/qualitysave');
$form = ActiveForm::begin(['action'=>$url]);
?>
<p><a id="staffrefresh" class="btn btn-primary" href='#'>Обновить штат</a></p>
<div class="panel panel-default">
    <div class="panel-heading">Лазерная эпиляция</div>
    <div class="panel-body">
        <div class="form-group">
        <input id="ldays" class="days" type="number" name="ldays" value="<?= $ldays ?>" size="5"/> <label for="ldays">Количество дней назад</label>
        </div>
        <div class="form-group">
            <input id="lall" type="checkbox" name="lall"> <label for="lall">Все клиенты указанных мастеров</label>
        </div>
        <? outListStaff('laser') ?>
    </div>
</div>
<div class="panel panel-default">
    <div class="panel-heading">Шугаринг/Воск эпиляция</div>
    <div class="panel-body">
        <div class="form-group">
        <input id="vdays" class="days" type="number" name="vdays" value="<?= $vdays?>" size="5"/> <label for="vdays">Количество дней назад</label>
        </div>
        <div class="form-group">
            <input id="vall" type="checkbox" name="vall" /> <label for="vall">Все клиенты указанных мастеров</label>
        </div>
        <? outListStaff('vosk') ?>
    </div>
</div>
<p>
    <input type="submit" class="btn btn-success" value="Сохранить">
    <a class="btn btn-warning" href='#' onclick="window.location.reload()">Отмена</a>
</p>
<?php
ActiveForm::end();

$url=\yii\helpers\Url::to('/admin/sprav/staffrefresh');
$js = <<< JS
    $(document).ready(function(){
        $('#staffrefresh').on('click',function(){
            $.ajax("$url")
            .done(function(){
                window.location.reload();
            });
        });
        $('#lall').on('click',function(e){
            $('li.laser input').attr('checked',this.checked);
        });
        $('#vall').on('click',function(e){
            $('li.vosk input').attr('checked',this.checked);
        });
    });
JS;
$this->registerJs($js);

$css = <<< CSS
ul.list{
    list-style-type: none;
}
input.days{
    width: 40px;
}
CSS;
$this->registerCss($css);
