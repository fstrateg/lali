<?php
/**
 * @var $this yii\web\View
 */
?>
<p><a class="btn btn-primary" href='#'>Обновить штат</a></p>
<div class="panel panel-default">
    <div class="panel-heading">Лазерная эпиляция</div>
    <div class="panel-body">
        <div class="form-group">
        <input type="number" name="ldays" value="21" size="5"/> Количество дней назад
        </div>
        <div class="form-group">
        Все клиенты указанных мастеров
        </div>
    </div>
</div>
<div class="panel panel-default">
    <div class="panel-heading">Шугаринг/Воск эпиляция</div>
    <div class="panel-body">
        <div class="form-group">
        <input type="number" name="ldays" value="1" size="5"/> Количество дней назад
        </div>
        <div class="form-group">
            Все клиенты указанных мастеров
        </div>
    </div>
</div>
<p>
    <a class="btn btn-success" href='#'>Сохранить</a>
    <a class="btn btn-warning" href='#'>Отмена</a>
</p>