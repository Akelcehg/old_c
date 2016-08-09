<?php
/**
 * Created by PhpStorm.
 * User: alexey
 * Date: 25.03.16
 * Time: 14:33
 */

if($diag){?>
    <div style="width: 100%;text-align: center; margin-top:20px">
        <p>ДИАГНОСТИЧЕСКИЕ СООБЩЕНИЯ</p>
        <table style="width: 100%; border-bottom-color: #0a0a0a;border: 1; border-bottom-style: dashed;border-top-style: dashed;border-left: none;border-right: none ">
            <thead>
            <tr><th>Дата</th><th>Время</th><th>Сообщение</th><th>Объем с начала Суток(с.у) м3</th></tr>
            </thead>
            <tbody>
            <?php foreach($diag as $hour){?>
                <tr>
                    <td><?=sprintf("%'02d",$hour->day).'.'.sprintf("%'02d",$hour->month).'.'.$hour->year?></td>
                    <td><?=sprintf("%'02d",$hour->hour).':'.sprintf("%'02d",$hour->minutes).':'.sprintf("%'02d",$hour->seconds)?></td>
                    <td> <?=\app\components\Prom\PromReportParts::getDiagnostLabel($hour->params)?> </td>
                    <td><?=number_format(round($hour->vsc,2),2,'.','')?></td>
                </tr>
            <?php } ?>
            </tbody>
        </table>
    </div><br>


<?php }; ?>