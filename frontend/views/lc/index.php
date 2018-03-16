<?php
use frontend\models\LcWatsApp;
/* @var $this yii\web\View */
$this->title = 'La Letty';

$list0=LcWatsApp::getRecords();
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
                       <li>
                           <?= $item['title'] ?>
                       </li>
                   </ul>
               </div>
           </div>
       </div>
<?
}?></div>
<?}?>