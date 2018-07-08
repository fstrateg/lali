<?php

use frontend\models\WorkStudioDetails;
/**
 * @var $this yii\web\View;
 * @var $model WorkStudioDetails;
 */
$this->title = 'La Letty';
?>
<p>Настройка индивидуальных напоминаний по услугам ВОСК-ШУГАРИНГ клиента:</p>
<p><b><?= $model->klientName ?></b></p>
<div class="form-check">
    <input type="checkbox" class="form-check-input" id="d21" <?= $model->sms_21?> value="1">
    <label class="form-check-label" for="d21">Напоминание 21 день</label>
</div>
<div class="form-group">
    <input id="d21val" type="number" value="<?= $model->sms_21val?>">
</div>
<div class="form-check">
    <input type="checkbox" class="form-check-input" id="d42" <?= $model->sms_42?> value="1">
    <label class="form-check-label" for="d42">Напоминание 42 дня</label>
</div>
<div class="form-group">
    <input id="d42val" type="number" value="<?= $model->sms_42val?>">
</div>
<div class="form-check">
    <input type="checkbox" class="form-check-input" id="d0" disabled="disabled" value="1" checked>
    <label class="form-check-label" style="color: #c9c9c9" for="d0">1-ая SMS (при записи)</label>
</div>
<div class="form-check">
    <input type="checkbox" class="form-check-input" id="d1" <?= $model->sms_01?> value="1">
    <label class="form-check-label" for="d1">2-ая SMS (напоминание)</label>
</div>
<div class="form-check">
    <input type="checkbox" class="form-check-input" id="d5" <?= $model->sms_05?> value="1">
    <label class="form-check-label" for="d5">3-я SMS (скрабирование)</label>
</div>
<input id="cid" type="hidden" value="<?= $model->id ?>">
<script type="application/javascript">
    function savenote(){
        var data={
            id: -1,
            d21: 0,
            d21val: 21,
            d42: 0,
            d42val: 42,
            d0: 1,
            d1: 0,
            d5: 0
        };
        data.id=document.getElementById('cid').value;
        if (document.getElementById('d21').checked) data.d21=1;
        var vl=document.getElementById('d21val').value;
        if (vl>0) data.d21val=vl;
        vl=document.getElementById('d42val').value;
        if (document.getElementById('d42').checked) data.d42=1;
        if (vl>0) data.d42val=vl;
        if (document.getElementById('d1').checked) data.d1=1;
        if (document.getElementById('d5').checked) data.d5=1;
        $.ajax({
            url: 'savenote',
            type: 'POST',
            data: {data: JSON.stringify(data)}
        });
    }
</script>