<?php
use yii\grid\GridView;
/* @var $this yii\web\View
 * @var $citys app\models\City
 */

$this->title = 'La-Litty';
?>
<div class="site-index">
    <?
    echo GridView::widget([
        // полученные данные
        'dataProvider' => $data,
        // Отображать 5 страниц
        'pager' => ['maxButtonCount' => 5],
        // колонки с данными
        'columns' => [
            [
                'label' =>"ID", // название столбца
                'attribute' => 'id', // атрибут
                'value'=>function($data){return $data->id;} // объявлена анонимная функция и получен результат
            ],
            [
                'label' => 'Заголовок',
                'attribute' => 'name',
                'value' => function($data) { return $data->name; },
            ],
            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]);
    ?>
</div>