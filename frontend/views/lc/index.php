<?php
use frontend\models\LcWatsApp;
use common\models\SettingsRecord;
use common\components\Date;
/* @var $this yii\web\View */
$this->title = 'La Letty';

$list1=LcWatsApp::getRecords(1);
$list2=LcWatsApp::getRecords(2);
//
$i=1;
$j=1;
$days=SettingsRecord::findValue('quality','laser');
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
    foreach ($list1 as $item) {
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
$days=SettingsRecord::findValue('quality','wax');
$data=new Date();
$data->subDays($days);
?>
    <p>Восковая/шугаринг эпиляция. Клиенты посетившие студию <?=$days ?> дней назад. <?= $data->format(); ?></p>
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
            foreach ($list2 as $item) {
                ?>
                <tr>
                    <td><?= $j++ ?></td>
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
        </table>
    </div>

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
foreach($list1 as $item)
{
    $list.="$r{name:'".$item["name"]."',phone:'".$item['client_phone']."'}";
    $r=',';
}
foreach($list2 as $item)
{
    $list.="$r{name:'".$item["name"]."',phone:'".$item['client_phone']."'}";
    $r=',';
}
$list.='];}';
$this->registerJs($list);
$this->registerJs($js);
?>