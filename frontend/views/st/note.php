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
    <input type="number" value="21">
</div>
<div class="form-check">
    <input type="checkbox" class="form-check-input" id="d42" <?= $model->sms_42?> value="1">
    <label class="form-check-label" for="d42">Напоминание 42 дня</label>
</div>
<div class="form-group">
    <input type="number" value="42">
</div>
<div class="form-check">
    <input type="checkbox" class="form-check-input" id="d0" disabled="disabled" value="1" checked>
    <label class="form-check-label" for="d0">1-ая SMS (при записи)</label>
</div>
<div class="form-check">
    <input type="checkbox" class="form-check-input" id="d1" <?= $model->sms_01?> value="1">
    <label class="form-check-label" for="d1">2-ая SMS (напоминание)</label>
</div>
<div class="form-check">
    <input type="checkbox" class="form-check-input" id="d5" <?= $model->sms_05?> value="1">
    <label class="form-check-label" for="d5">3-я SMS (скрабирование)</label>
</div>
