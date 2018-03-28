<?php
use frontend\models\LcWatsApp;
use common\models\SettingsRecord;
use common\components\Date;
/* @var $this yii\web\View */
$this->title = 'La Letty';

$list0=LcWatsApp::getRecords();
//
$i=1;
$days=SettingsRecord::findValue('laser','daywhatsap');
$data=new Date();
$data->subDays($days);
?>
<p>Лазерная эпиляция. Клиенты посетившие студию <?=$days ?> дней назад. <?= $data->format(); ?></p>
    <div class="table-responsive">
<table class="table table-hover table-bordered">
    <thead>
    <tr>
        <th>#</th>
        <th>Клиент</th>
        <th>Номер тел.</th>
        <th>Услуги</th>
        <th>Мастер</th>
        <th>Мед. обход</th>
        <th>Статус мед. обхода</th>
    </tr>
    </thead>
    <?
    foreach ($list0 as $item) {
    ?>
<tr>
    <td><?= $i ?></td>
    <td><?= $item['name'] ?></td>
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
    <td><a class="mr-10 btn btn-warning" data="<?= ($i-1); ?>">
            <span class="glyphicon whatsapp"></span>
            WhatsApp
        </a></td>
    <td></td>

</tr>
    <?
        $i++;
    }
    ?>
</table></div>

<?
$list=array_chunk($list0,4);
foreach($list as $row)
{?>
    <div class="row">
<?  foreach($row as $item)
{?>

       <div class="col-sm-6 col-md-3">
           <div class="cbox">
           <div class="cbox-title"><b><?= $item['name'] ?> <?= $item['client_phone'] ?></b></div>
               <div class="cbox-content">
                   <div><p><?= $item['appointed'] ?></p></div>
                   <div><p><?= $item['staff_name'] ?></p></div>
                   <ul>
                       <? if ($item['title'])
                            echo '<li>'.$item['title'].'</li>';
                       else
                       {
                           foreach(LcWatsApp::getServices($item['services_id']) as $ss)
                           {
                               echo '<li>'.$ss['title'].'</li>';
                           }
                       }
                       ?>
                   </ul>
                   <div class="row">
                       <div class="span-3">
                       <a class="mr-10 pull-right btn btn-warning" data="<?= $i++; ?>">
                           <span class="glyphicon whatsapp"></span>
                           WhatsApp
                       </a>
                       </div>
                   </div>
               </div>
           </div>
       </div>
<?
}?></div>
<?}
?>
<script>
    window.watsappmsg='<?= \common\models\SettingsRecord::findValue('laser','watsapp') ?>';
</script>
<?
$js=<<< JS
    $(document).ready(function() {
        $('a.mr-10').on('click',
            function(e){
                var list=getlist();
                var dt=$(e.target).attr('data');
                if (dt==null)
                    dt=$(e.target).parent().attr('data');
                var msg=window.watsappmsg.replace('%NAME%',list[dt].name);
                var url="https://api.whatsapp.com/send?phone="+list[dt].phone+"&text="+msg;
                window.open(url, '_blank');
                //alert(msg);
            }
         );
    });
JS;
$list='function getlist(){ return [';
$r='';
foreach($list0 as $item)
{
    $list.="$r{name:'".$item["name"]."',phone:'".$item['client_phone']."'}";
    $r=',';
}
$list.='];}';
$this->registerJs($list);
$this->registerJs($js);
?>