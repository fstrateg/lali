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
            <tr>
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
