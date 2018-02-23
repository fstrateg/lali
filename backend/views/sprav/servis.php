<?php
use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use yii\helpers\Url;


/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Услуги';
$this->params['breadcrumbs'][] = $this->title;
?>
    <div class="settings-record-index">

        <h1><?= Html::encode($this->title) ?></h1>

        <div id='editform'></div>

        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'columns' => [
                'title',
                [
                    'attribute'=>'scrubbing',
                    'format' => 'raw',
                    'value' => function ($model, $index, $widget) {
                        return Html::checkbox('scrubbing[]',$model->scrubbing,['onclick'=>'return false;']);
                    }
                ],
                [
                    'attribute'=>'remind',
                    'format' => 'raw',
                    'value' => function ($model, $index, $widget) {
                        return Html::checkbox('remind[]',$model->remind,['onclick'=>'return false;']);
                    }
                ],
                ['class' => 'yii\grid\ActionColumn',
                    'template' => '{update}',
                    'buttons' => [
                        'update' => function ($url, $model, $key) {
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

<?
$js2=<<<JS
$('.edit').on('click', function(event){
 event.preventDefault();
    var idkey=$(this).attr('idkey');
    $.ajax({
        url: '/admin/sprav/servis?m=update&id='+idkey,
        type: 'GET',
        success: function(res)
        {
            $('#editform').html(res);
            $('#modal').modal('show');
        }
    });
    });
JS;

$this->registerJs($js2);?>