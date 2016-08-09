<?php
/**
 * Created by PhpStorm.
 * User: alexey
 * Date: 25.03.16
 * Time: 14:25
 */

if($intervention){?>
    <div style="width: 100%;text-align: center; margin-top:20px">
        <p>ВМЕШАТЕЛЬСВА В РАБОТУ ВЫЧИСЛИТЕЛЯ</p>
        <table style="width: 100%; border-bottom-color: #0a0a0a;border: 1; border-bottom-style: dashed;border-top-style: dashed;border-left: none;border-right: none ">
            <thead>
            <tr><th>Дата</th><th>Время</th><th>Наименование параметра</th><th colspan="2">Значение параметра</th></tr>
            <tr><th></th><th></th><th></th><th>Старое</th><th>Новое</th></tr>
            </thead>
            <tbody>
            <?php foreach($intervention as $hour){?>
                <tr>
                    <td><?=sprintf("%'02d",$hour->day).'.'.sprintf("%'02d",$hour->month).'.'.$hour->year?></td>
                    <td><?=sprintf("%'02d",$hour->hour).':'.sprintf("%'02d",$hour->minutes).':'.sprintf("%'02d",$hour->seconds)?></td>
                    <td> <?=\app\components\Prom\PromReportParts::getIntervLabel($hour->params)?> </td>
                    <td><?=rtrim(number_format(round($hour->old_params,4),4,'.',''),'0')?></td>
                    <td><?=rtrim(number_format(round($hour->new_params,4),4,'.',''),'0')?></td></tr>
            <?php } ?>
            </tbody>
        </table>
    </div>
<?php }; ?>