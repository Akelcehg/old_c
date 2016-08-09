<?php
/**
 * Created by PhpStorm.
 * User: alexey
 * Date: 31.03.16
 * Time: 16:41
 */

namespace app\modules\prom\components;


use app\components\Prom\PromReportParts;
use app\models\Diagnostic;
use app\models\Intervention;
use yii\base\Widget;

class InterventionWidget extends Widget
{
    public $id;
    public $year=false;
    public $month=false;

   function run()
   {
       if(!$this->year)$this->year=date('y');
       if(!$this->month)$this->month=date('n');
       $dt=new \DateTime($this->year.'-'.$this->month.'-01');

        $intv=Intervention::find()
            ->where(['all_id'=>$this->id, 'month' => $dt->format('n'), 'year' =>$dt->format('y')])
            ->orderBy(['id'=>SORT_ASC])
            ->all();
       $this->renderWidget($intv);
   }
    function renderWidget($intv){


        echo '<div id=\'interventionTable\'>';
if($intv){?>
    <table  class='table-striped table-hover table-bordered emergDataTable' id='intervDataTable'>
        <thead>
        <tr><th>Дата</th><th>Время</th><th>Наименование параметра</th><th colspan="2">Значение параметра</th></tr>
        <tr><th></th><th></th><th></th><th>Старое</th><th>Новое</th></tr>
        </thead>
        <tbody>
        <?php

        foreach($intv as $hour){

            ?>
            <tr>
                <td><?=sprintf("%'02d",$hour->day).'.'.sprintf("%'02d",$hour->month).'.'.$hour->year?></td>
                    <td><?=sprintf("%'02d",$hour->hour).':'.sprintf("%'02d",$hour->minutes).':'.sprintf("%'02d",$hour->seconds)?></td>
                    <td> <?=\app\components\Prom\PromReportParts::getIntervLabel($hour->params)?> </td>
                    <td><?=rtrim(number_format(round($hour->old_params,4),4,'.',''),'0')?></td>
                    <td><?=rtrim(number_format(round($hour->new_params,4),4,'.',''),'0')?></td></tr>
            </tr>
        <?php } ?>
        </tbody>
    </table>

<?php  }else{ echo 'нет данных';};
        echo '</div>';

    }
}