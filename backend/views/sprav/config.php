<?php
use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use yii\helpers\Url;


/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Конфигурация';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="settings-record-index">

    <h1><?= Html::encode($this->title) ?></h1>


    <?php Pjax::begin(); ?>
    <a href='/admin/sprav/config?m=update&id=1'>test</a>
    <?php Pjax::end(); ?>
    <?= GridView::widget([
    'dataProvider' => $dataProvider,
    'columns' => [
        'name',
        'val',
        ['class' => 'yii\grid\ActionColumn',
            'template' => '{update}',
            'buttons' => [
                'update' => function ($url, $model, $key) {
                    $url=Url::to(['config','m'=>'update','id'=>$key]);
                    return Html::a('<span class="glyphicon glyphicon-pencil"></span>', "#", [
                        'title' => 'Редактировать',
                        'data-pjax' => '0',
                        'idkey'=>$key,
                        'class'=>'edit',
                    ]);
                },
            ]
        ],
    ],
]); ?>
</div>
    <?= $this->registerJs("
    $('.edit').on('click', function(event){
    event.preventDefault();
    var idkey=$(this).attr('idkey');
    alert(idkey);
    });
    ");?>