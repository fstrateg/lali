<?php
use frontend\models\LcWatsApp;
use common\models\SettingsRecord;
use common\components\Date;
use yii\helpers\Html;
/**
 * @var $this yii\web\View;
 * @var $calcdat common\components\Date;
 * @var $model LcWatsApp;
 */
$this->title = 'La Letty';

$list1=$model->findLaserRecords();
$list2=$model->findWaxRecords();

$i=1;
$j=1;
$stat=['0'=>'-','1'=>'Проведен','2'=>'Ошибка'];


?>
<p>
<div class="btn-toolbar" role="toolbar" aria-label="...">
    <div class="btn-group mr-2" role="group" aria-label="First group">
        <a class="btn btn-default" href="/lc/index">Сегодня</a>
    </div>
    <div class="btn-group mr-2" role="group" aria-label="First group">
        <a class="btn btn-default" href="/lc/index?dat=<?= $model->getParamPrior() ?>">&lt;</a>
        <a class="btn btn-default" href="/lc/index?dat=<?= $model->getParamNext() ?>">&gt;</a>
    </div>
    <div class="btn mr-2">
        <?= $model->getCaclDate(); ?>
     </div>
    </div>

</p>
<div id="msgok1" class="alert-success alert fade in hidden">
<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
Данные успешно внесены в базу!
</div>
<hr>
<div class="row mb-20">
    <div class="col-md-7" style="line-height: 34px">
        Лазерная эпиляция. Клиенты посетившие студию <?=$model->days_laser; ?> дней назад. <?= $model->getDateLaser() ?>
    </div>
    <div class="col-md-5">
        <div class="pull-right">
            Установить всем:
            <?= HTML::dropDownList('list','0',$stat,['class'=>'form-control','style'=>'width:auto;display:inline-block']); ?>
            <a id="laser-all" href="#" class="btn btn-default">Сохранить</a>
        </div>
    </div>
</div>
    <div class="table-responsive">
<table class="table table-hover table-bordered">
    <thead>
    <tr>
        <th>#</th>
        <th>Клиент</th>
        <th>Мед. обход</th>
        <th>Статус мед. обхода</th>
        <th>Номер тел.</th>
        <th>Услуги</th>
        <th>Мастер</th>

    </tr>
    </thead>
    <?
    foreach ($list1 as $item) {
    ?>
<tr>
    <td><?= $i ?></td>
    <td><?= $item['name'] ?></td>
    <td><a class="mr-10 btn btn-primary" data-typ="1" data-id="<?= ($i-1); ?>">
            <span class="glyphicon whatsapp"></span>
            WhatsApp
        </a></td>
    <td><?= HTML::dropDownList('list',$item['stat'],$stat, ['class'=>'statlist form-control','data-id'=>$item['resource_id'], 'data-typ'=>'1']); ?></td>
    <td><?= $item['client_phone'] ?></td>
    <td><ul>
        <? foreach(LcWatsApp::getServices($item['services_id']) as $ss)
        {
        echo '<li>'.$ss['title'].'</li>';
        }
        ?>
        </ul>
    </td>
    <td><?= $item['staff_name'] ?></td>

</tr>
    <?
        $i++;
    }
    ?>
</table></div>
<div id="msgok2" class="alert-success alert fade in hidden">
<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
Данные успешно внесены в базу!
</div>
<hr>
    <div class="row mb-20">
        <div class="col-md-7" style="line-height: 34px">
            Воск/шугаринг эпиляция. Клиенты посетившие студию <?=$model->days_wax ?> дней назад. <?= $model->getDateWax(); ?>
            </div>
    <div class="col-md-5">
        <div class="pull-right">
            Установить всем:
            <?= HTML::dropDownList('list','0',$stat,['class'=>'form-control','style'=>'width:auto;display:inline-block']); ?>
            <a id="wax-all" href="#" class="btn btn-default">Сохранить</a>
        </div>
    </div>
</div>
    <div class="table-responsive">
        <table class="table table-hover table-bordered">
            <thead>
            <tr>
                <th>#</th>
                <th>Клиент</th>
                <th>Мед. обход</th>
                <th>Статус мед. обхода</th>
                <th>Номер тел.</th>
                <th>Услуги</th>
                <th>Мастер</th>
            </tr>
            </thead>
            <?
            foreach ($list2 as $item) {
                ?>
                <tr>
                    <td><?= $j++ ?></td>
                    <td><?= $item['name'] ?></td>
                    <td><a class="mr-10 btn btn-primary" data-typ="2" data-id="<?= ($i-1); ?>">
                            <span class="glyphicon whatsapp"></span>
                            WhatsApp
                        </a></td>
                    <td><?= HTML::dropDownList('list',$item['stat'],$stat,['class'=>'statlist form-control','data-id'=>$item['resource_id'],'data-typ'=>2]); ?></td>
                    <td><?= $item['client_phone'] ?></td>
                    <td><ul>
                            <? foreach(LcWatsApp::getServices($item['services_id']) as $ss)
                            {
                                echo '<li>'.$ss['title'].'</li>';
                            }
                            ?>
                        </ul>
                    </td>
                    <td><?= $item['staff_name'] ?></td>

                </tr>
                <?
                $i++;
            }
            ?>
        </table>
    </div>

<script>
    window.watsappmsg=['<?= $model->getLaserMsg() ?>',
    '<?= $model->getWaxMsg() ?>'];

</script>
<?
$js=<<< JS
    function setVL(typ,btn)
    {
        var i=0;
        var vl=[];
        var ss=$(btn).parent().find('select').val();
        if (ss==0) return;
        var rs=$('select.statlist');
        $.each(rs,function(ind,vv){
            if ($(vv).attr('data-typ')==typ&&$(vv).val()==0)
            {
                id=$(vv).attr('data-id');
                vl.push(id);
            }

        });
        $.ajax({
            url:'/lc/qualitysaves',
            type: 'POST',
            data: {ids:JSON.stringify(vl),vl:ss,typ:typ}
        })
                        .fail(function(xhr, ajaxOptions, thrownError){
                            alert(xhr.responseText);
                            })
                        .done(function(data){
                            if (data=='OK')
                            {
                                $.each(rs,function(ind,vv){
                                    if ($(vv).attr('data-typ')==typ&&$(vv).val()==0)
                                    {

                                        $(vv).val(ss);
                                    }
                                });
                                $('#msgok'+typ).removeClass('hidden');
                            }
                        });
    }

    $(document).ready(function() {
        $('a.mr-10').on('click',
            function(e){
                var list=getlist();
                var bt=$(e.target);
                var dt=bt.attr('data-id');
                var typ="1";
                if (dt==null)
                {
                    bt=$(e.target).parent();
                    dt=bt.attr('data-id');
                    typ=bt.attr('data-typ');
                }
                else
                {
                    typ=bt.attr('data-typ');
                }

                var msg=window.watsappmsg[0];
                if (typ=="2")
                    msg=window.watsappmsg[1];
                msg=msg.replace('%NAME%',list[dt].name)
                var url="https://api.whatsapp.com/send?phone="+list[dt].phone+"&text="+msg;
                window.open(url, '_blank');
                bt.removeClass('btn-primary').addClass('btn-seren');
                //alert(msg);
            }
         );

         $('select.statlist').on('change',
            function(e){
                var id=$(this).attr('data-id');
                var type=$(this).attr('data-typ');
                var status=$(this).val();
                $.ajax('/lc/qualitysave?typ='+type+'&id='+id+'&status='+status)
                        .fail(function(xhr, ajaxOptions, thrownError){
                        alert(xhr.responseText);
                        });
            }
         );

         $('#wax-all').on('click',function(){
           setVL(2,this);
         });

         $('#laser-all').on('click',function(){
            setVL(1,this);
         });
    });
JS;
$list='function getlist(){ return [';
$r='';
foreach($list1 as $item)
{
    $list.="$r{name:'".$item["name"]."',phone:'".str_replace('+','',$item['client_phone'])."'}";
    $r=',';
}
foreach($list2 as $item)
{
    $list.="$r{name:'".$item["name"]."',phone:'".str_replace('+','',$item['client_phone'])."'}";
    $r=',';
}
$list.='];}';
$this->registerJs($list);
$this->registerJs($js);
?>