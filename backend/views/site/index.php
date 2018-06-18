<?php
use common\components\cpanel\CPanelBtn;
/* @var $this yii\web\View */

$this->title = 'La-Litty';
?>
<div class="site-index">
    <? $panel=CPanelBtn::begin();
    $panel->addPanelBtn([
        'text'=>'Пользователи',
        'url'=>\yii\helpers\Url::toRoute('/users'),
        'img'=>'user',
    ]);
    $panel->end();
    ?>
</div>
