<?php
/**
 * Created by PhpStorm.
 * User: alexey
 * Date: 25.03.16
 * Time: 13:22
 */

namespace app\components\Prom\views;

use app\components\Prom\PromReportParts;

if($emsit){?>
    <div style="width: 100%;text-align: center; margin-top:20px">
        <p>АВАРИЙНЫЕ СИТУАЦИИ</p>
        <table style="width: 100%; border-bottom-color: #0a0a0a;border: 1; border-bottom-style: dashed;border-top-style: dashed;border-left: none;border-right: none ">
            <thead>
            <tr><th>Время первого </th><th>Аварийный признак</th><th>Длительность</th><th colspan="2" >Объем </th><th >Кол.</th></tr>
            <tr><th>появления</th><th></th><th>ЧЧ:ММ:CC</th><th>р.у м3</th><th > с.у м3</th><th > появ.</th></tr>
            </thead>
            <tbody>
            <?php
            $V_sca=0;
            $V_rca=0;
            $V_pmdba=0;
            $Time=0;

            foreach($emsit as $hour){
                $V_sca+=$hour->vsc;
                $V_rca+=$hour->vrc;
                $V_pmdba+=$hour->var1;
                $Time+=$hour->time;
                ?>
                <tr>
                    <td><?=$hour->timestamp?></td>
                    <td><?= PromReportParts::getEmergLabel($hour->params)?></td>
                    <td> <?=sprintf("%'02d:%'02d:%'02d",$hour->time/3600,$hour->time%3600/60,$hour->time%3600%60)?> </td>
                    <?php  if($dimtype=="Quantity"){ ?>
                    <td><?=number_format(round($hour->vrc,2),2,'.','')?></td>
                    <?php }?>

                    <td><?=number_format(round($hour->vsc,1),1,'.','')?></td>
                    <td><?=number_format($hour->count_p,0,'.','')?></td>
                </tr>
            <?php } ?>
            </tbody>
        </table>

    </div>
    <div style="width: 100%;text-align: left; margin-top:20px">
        <table style="width: 100%;">
            <tr>
                <td>Итого:</td><td width="273px"><?=sprintf("%s"," ")?></td><td width="140px"><?=sprintf("%'02d:%'02d:%'02d",$Time/3600,$Time%3600/60,$Time%3600%60)?></td><td><?=sprintf("%s",number_format($V_rca,2,'.',''))?></span></td><td><?=sprintf(" %s",number_format($V_sca,1,'.',''))?></span></td><td></td>
            </tr>
        </table>

        <span>Аварийный объем  при р.у. помешенный в основную базу:<?=sprintf("%s",number_format($V_rca-$dayData->vav_rc,2,'.',''))?></span><br>
        <span>Аварийный объем  при c.у. помешенный в основную базу:<?=sprintf("%s",number_format($V_sca-$dayData->vav_sc,1,'.',''))?></span><br>
    </div>


    <div style="width: 100%;text-align: center; margin-top:20px">
        <p>АВАРИЙНЫЕ ПРИЗНАКИ</p>
        <table style="width: 100%; border-bottom-color: #0a0a0a;border: 1; border-bottom-style: dashed;border-top-style: dashed;border-left: none;border-right: none ">
            <thead>
            <tr><th> Основной признак </th><th>Длительность</th><th colspan="2">Объем</th><th >Кол.</th></tr>
            <tr><th> аварийной ситуации</th><th></th><th > р.у м3</th><th > с.у м3</th><th >прояв.</th></tr>
            </thead>
            <tbody>
            <?php
            $V_sca=0;
            $V_rca=0;
            $V_pmdba=0;
            $Time=0;
            foreach($emsign as $hour){

                $V_sca+=$hour['vsc'];
                $V_rca+=$hour['vrc'];
                $V_pmdba+=$hour['var1'];
                $Time+=$hour['duration'];

                ?>
                <tr>
                    <td><?=PromReportParts::getEmergSignLabel($hour['params'])?></td>
                    <td><?=sprintf("%'02d:%'02d:%'02d",$hour['duration']/3600,$hour['duration']%3600/60,$hour['duration']%3600%60)?> </td>
                    <td><?=number_format(round($hour['vrc'],2),2,'.','')?></td>
                    <td><?=number_format(round($hour['vsc'],2),1,'.','')?></td>
                    <td><?=number_format($hour['countP'],0,'.','')?></td>
                </tr>
                <tr><th></th><th></th><th ></th><th ></th><th ></th></tr>
            <?php } ?>
            </tbody>
        </table>
        <table style="width: 100%;">

            <tr>
                <td width="273px">Итого:</td>
                <td width="145px"><?=sprintf("%'02d:%'02d:%'02d",$Time/3600,$Time%3600/60,$Time%3600%60)?></td>
                <td><?=sprintf("%s",number_format($V_rca,2,'.',''))?></span></td>
                <td><?=sprintf("%s",number_format($V_sca,1,'.',''))?></span></td>
                <td><?=sprintf("%s"," ")?></td>
            </tr>

        </table>

    </div><br>


<?php }; ?>
