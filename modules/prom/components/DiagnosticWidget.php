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
use yii\base\Widget;

class DiagnosticWidget extends Widget
{
    public $id;
    public $year=false;
    public $month=false;

   function run()
   {
       if(!$this->year)$this->year=date('y');
       if(!$this->month)$this->month=date('n');
       $dt=new \DateTime($this->year.'-'.$this->month.'-01');

        $diag=Diagnostic::find()
            ->where(['all_id'=>$this->id, 'month' => $dt->format('n'), 'year' =>$dt->format('y')])
            ->orderBy(['id'=>SORT_ASC])
            ->all();
       $this->renderWidget($diag);
   }
    function renderWidget($diag){
echo '<div id=\'diagnosticTable\'>';
if($diag){?>
    <table  class='table-striped table-hover table-bordered emergDataTable' id='diagDataTable'>
        <thead>
        <tr><th>Дата</th><th>Время</th><th>Сообщение</th><th>Объем с начала Суток(с.у) м3</th></tr>
        </thead>
        <tbody>
        <?php

        foreach($diag as $hour){

            ?>
            <tr>
                <td><?=sprintf("%'02d",$hour->day).'.'.sprintf("%'02d",$hour->month).'.'.$hour->year?></td>
                <td><?=sprintf("%'02d",$hour->hour).':'.sprintf("%'02d",$hour->minutes).':'.sprintf("%'02d",$hour->seconds)?></td>
                <td> <?=\app\components\Prom\PromReportParts::getDiagnostLabel($hour->params)?> </td>
                <td><?=number_format(round($hour->vsc,2),2,'.','')?></td>
            </tr>
        <?php } ?>
        </tbody>
    </table>

<?php  }else{ echo 'нет данных';};

echo '</div>';
    }
}