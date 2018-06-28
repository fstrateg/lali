<?php
use frontend\models\WorkStudio;
use common\components\Date;
use yii\helpers\Html;
/**
 * @var $this yii\web\View;
 * @var $model WorkStudio;
 */
$this->title = 'La Letty';
echo $model->staffname;
?>
<p>Страница в разработке!</p>
    <? //print_r($model->getRecordsForStaff()) ?>
<div class="table-responsive">
    <table class="table table-hover table-bordered">
        <thead>
        <tr>
            <th>#</th>
            <th>Время</th>
            <th>Клиент</th>
            <th>Услуги</th>
        </tr>
        </thead>
        <?
        $i=0;
        foreach($model->getRecordsForStaff() as $items)
        {
            ?>
            <tr data-id="<?= $items['id']?>">
                <td><?= ++$i ?></td>
                <td><?= $items['appointed'] ?></td>
                <td><?= $items['client']?></td>
                <td><ul><? foreach($model->getServices($items['services_id']) as $s)
                    {
                        echo '<li>'.$s['title'].'</li>';
                    }
                     ?></ul></td>
            </tr>
            <?
        }
?>
        </table>
    <div id="frmedit" class="wrap" style="width:0; background: #CCC">
    <div class="container">
        <div id="panel">
            same text
        </div>
    </div>
    </div>
    <?
$js=<<< JS
    $(document).ready(function() {
        $('tr td').on('click',
            function(e){
            document.getElementById("frmedit").style.width = "100%";
            document.getElementById("frmedit").style.background = "#FFF";
            $('#panel').text($(this).parent().attr('data-id'));
            });
    });
JS;
$css= <<< CSS
#frmedit{
position: fixed;
background: white;
top: 0;
left: 0;
width: 0;
min-height: 100%;
z-index: 1;
overflow-x: hidden;
transition: 1.0s;
box-shadow: 0 0 10px #888;
}
CSS;

    $this->registerJs($js);
    $this->registerCss($css);
?>