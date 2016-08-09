
<div style="width: 100%;height: 100%;" xmlns="http://www.w3.org/1999/html">

    <div style="float: right; width:150px">
        <p >Коммерческий отчет</p>
    </div>


    <div style="width: 100%;text-align: center; margin-top:20px">
        <p>СУТОЧНЫЙ ОТЧЕТ</p>
        <p>за <?=$datetext?></p>
        <p>Составлен программой <?=$programName?> по данным <?=$time?> <?=$date?></p>
    </div>
    <div style="width: 100%;text-align: left; margin-top:10px">
        <?php if($counter->prog!="MConCore"){ ?>
        <span>Наименование Комплекса:<?=$correctorName?></span><br>
        <?php } ?>
        <span>Заводской номер Вычислителя:<?=$serialNumber?></span><br>
        <span style="width:100%;"><span><?=$complexName?></span><span style="mar">Т/п 1: <?=$tubeName?></span></span><br>
        <span>Версия ПО:<?=$poVersion?></span><br>
    </div>

    <div style="width: 100%;text-align: left; margin-top:10px;margin-left:-3px">
        <p>Метод расчета:<?=$metodName?></p>
        <?php if($counter->prog!="MConCore"){ ?>
        <p>Наименоване счетчика:<?=$counterName?></p>
        <?php } ?>
        <table width="100%">
            <tr>
                <td><p>Метод измерений:</p></td>
                <td><p><?=$metodDimName?></p></td>
                <td width="50px"></td>
                <td><p>Контрактный час:</p></td>
                <td><p><?=$contractTime?></p></td>
            </tr>
            <tr>
                <td><p>Плотность, кг/м3:</p></td>
                <td><p><?=$density?></p></td>
                <td width="50px"></td>
                <td><p>Молярная доля СО2,%:</p></td>
                <td><p><?=$molCO2?></p></td>
            </tr>
            <tr>
                <td><p>Тип давления:</p></td>
                <td><p><?=$pressureType?></p></td>
                <td width="50px"></td>
                <td><p>Молярная доля N2,%:</p></td>
                <td><p><?=$molN2?></p></td>
            </tr>
              <tr>
                <td><p>НПИ давления , кгс/см2:</p></td>
                <td><p><?=number_format($pressureNPI,4,'.','')?></p></td>
                <td width="50px"></td>
                <td><p>ВПИ давления , кгс/см2:</p></td>
                <td><p><?=number_format($pressureVPI,4,'.','')?></p></td>
            </tr>
            <tr>
                <td><p>НПИ температуры , гр.Целс:</p></td>
                <td><p><?=number_format($tempNPI,4,'.','')?></p></td>
                <td width="50px"></td>
                <td><p>ВПИ температуры , гр.Целс</p></td>
                <td><p><?=number_format($tempVPI,4,'.','')?></p></td>
            </tr>
        </table>
        <div style="width: 100%;text-align: left; margin-top:0px">
            <?php if($counter->prog=="MConCore"){ ?>
                <span>Коэффициент сжимаемости :<?=sprintf("%'.97s",number_format($Ksgim,5,'.',''))?></span><br>
            <?php } ?>
            <span>Количесво импульсов счетчика на 1 м3:<?=sprintf("%'.97s",number_format($impulseOnM3,4,'.',''))?></span><br>
            <span>Верхний предел измерений при  рабочих условиях (Qmax), м3/ч: <?=sprintf("%'.55s",number_format($qVPI,4,'.',''))?></span><br>
            <span>Миниальный расход при рабочих условиях(Qmin), м3/ч: <?=sprintf("%'.70s",number_format($qNPI,4,'.',''))?></span><br>
            <span>Расход при котором счетчик останавливается, м3/ч: <?=sprintf("%'.76s",number_format($qStop,3,'.',''))?></span><br>
            <span>При Qстоп < Qv < Qmin принимается Qv = Q min и, если нет  других аварийных признаков , накопленный объем газа добавляется к безаварийному объему </span><br>
            <span>Аварийный Объем наращиваеся за время выключения питания </span><br>
        </div>
    </div>

    <div style="width: 100%;text-align: center; margin-top:20px">
        <p>ЧАСОВЫЕ ИЗМЕРИТЕЛЬНЫЕ ДАННЫЕ</p>
        <table style="width: 100%; border-bottom-color: #0a0a0a;border: 1; border-bottom-style: dashed;border-top-style: dashed;border-left: none;border-right: none ">
            <thead>
            <tr><th>Дата</th><th colspan="2">Время</th><th colspan="2"> Объем,м3</th><th>Ср.давл.</th><th>Ср.темп.</th><th>АВ</th></tr>
            <tr><th></th><th>Начало</th><th>Конец</th><th> р.у</th><th> с.у</th><th>кгс/см2</th><th>гр.Целс</th><th></th></tr>
            </thead>
            <tbody>
            <?php foreach($hours as $hour){?>
            <tr><td><?=sprintf("%'02d",$hour->day).'.'.sprintf("%'02d",$hour->month).'.'.$hour->year?></td><td><?=sprintf("%'02d",$hour->hour_n).':'.sprintf("%'02d",$hour->minutes_n)?></td><td><?=sprintf("%'02d",$hour->hour_k).':'.sprintf("%'02d",$hour->minutes_k)?></td><td><?=number_format(round($hour->v_rc,2),2,'.','')?></td><td><?=number_format(round($hour->v_sc,2),2,'.','')?></td><td><?=number_format(round($hour->paverage,4),4,'.','')?></td><td><?=number_format(round($hour->taverage,2),2,'.','')?></td><td><?=$hour->emergency?></td></tr>
            <?php } ?>
            </tbody>
        </table>
    </div>


    <div style="width: 100%;text-align: left; margin-top:0px;margin-left: 210px">
        <span>
            <table style="font-weight: 700">
                <tr>
                    <td width="83px"> <b> Итого:</b> </td> <td width="65px"> <b>  <?=number_format($sumRC,2,'.','')?> </b></td><td> <b> <?=number_format($sumSC,2,'.','')?></b></td>
                </tr>

            </table>
        </span><br>
    </div>

    <?php if($counter->prog!="MConCore"){ ?>
    <div style="width: 100%;text-align: left; margin-top:20px">
        <span>Безаварийный объем за сутки при р.у.,м3:<?=sprintf("%'.95s",number_format($bezavV,1,'.',''))?></span><br>
        <span>Аварийный объем за сутки при р.у.:<?=sprintf("%'.108s",number_format($avV,1,'.',''))?></span><br>
        <span>Полный объем за сутки при р.у.:<?=sprintf("%'.111s",number_format($polV,1,'.',''))?></span><br>
        <span>Безаварийный объем за сутки при c.у.,м3:<?=sprintf("%'.93s",number_format($bezavVsu,1,'.',''))?></span><br>
        <span>Аварийный объем за сутки при c.у.:<?=sprintf("%'.108s",number_format($avVsu,1,'.',''))?></span><br>
        <span>Полный объем за сутки при c.у.:<?=sprintf("%'.110s",number_format($polVsu,1,'.',''))?></span><br>
        <span>Длительность АС Qстоп < Qv < Qmin  за сутки , ч:мин:с:<?=sprintf("%'.62s%'02d:%'02d:%'02d","",$timeQem/3600,$timeQem%3600/60,$timeQem%3600%60)?></span><br>
        <span>Показания счетчика газа наконец отчетного периода (р.у.) , м3:<?=sprintf("%'.52s",number_format($pokaz,2,'.',''))?></span><br>
        <span>Общая длительность аварийніх ситуация за сутки , ч:мин:с:<?=sprintf("%'.55s%'02d:%'02d:%'02d","",$timeEmrg/3600,$timeEmrg%3600/60,$timeEmrg%3600%60)?></span><br>
    </div>

    <?php } else{ ?>
        <div style="width: 100%;text-align: left; margin-top:20px">

            <span>Аварийный объем за сутки при р.у.:<?=sprintf("%'.108s",number_format($avV,1,'.',''))?></span><br>
            <span>Аварийный объем за сутки при c.у.:<?=sprintf("%'.108s",number_format($avVsu,1,'.',''))?></span><br>
            <span>Полный объем за сутки при c.у.:<?=sprintf("%'.110s",number_format($polV,1,'.',''))?></span><br>
            <span>Длительность измирительных авар.ситуаций за сутки , ч:мин:с:<?=sprintf("%s%'02d:%'02d:%'02d","",$timeQem/3600,$timeQem%3600/60,$timeQem%3600%60)?></span><br>
            <span>Длительность методических авар.ситуаций за сутки , ч:мин:с:<?=sprintf("%s%'02d:%'02d:%'02d","",$timeQem/3600,$timeQem%3600/60,$timeQem%3600%60)?></span><br>
            <span>Длительность отключения питания за сутки , ч:мин:с:<?=sprintf("%'.10s%'02d:%'02d:%'02d","",$timeQem/3600,$timeQem%3600/60,$timeQem%3600%60)?></span><br>
            <span>Длит.постановки на несанкц. константы  за сутки , ч:мин:с:<?=sprintf("%'.10s%'02d:%'02d:%'02d","",$timeQem/3600,$timeQem%3600/60,$timeQem%3600%60)?></span><br>
            <span>Длит.работы когда расход был был < НПИ  за сутки , ч:мин:с:<?=sprintf("%'.10s%'02d:%'02d:%'02d","",$timeQem/3600,$timeQem%3600/60,$timeQem%3600%60)?></span><br>
            <span>Показания счетчика газа наконец отчетного периода (р.у.) , м3:<?=sprintf("%'.52s",number_format($pokaz,2,'.',''))?></span><br>

        </div>
    <?php } ?>

    <?php if($emsit){?>
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
                    $V_sca+=$hour['vsc'];
                    $V_rca+=$hour['vrc'];
                    $V_pmdba+=$hour['var1'];
                    $Time+=$hour['duration'];
                    ?>
                    <tr>
                        <td><?=$hour['timestamp']?></td>
                        <td><?=getEmergLabel($hour['params'])?></td>
                        <td> <?=sprintf("%'02d:%'02d:%'02d",$hour['duration']/3600,$hour['duration']%3600/60,$hour['duration']%3600%60)?> </td>
                        <td><?=number_format(round($hour['vrc'],2),2,'.','')?></td>
                        <td><?=number_format(round($hour['vsc'],1),1,'.','')?></td>
                        <td><?=number_format($hour['countP'],0,'.','')?></td>
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

            <span>Аварийный объем  при р.у. помешенный в основную базу:<?=sprintf("%s",number_format($V_rca-$avV,2,'.',''))?></span><br>
            <span>Аварийный объем  при c.у. помешенный в основную базу:<?=sprintf("%s",number_format($V_sca-$avVsu,1,'.',''))?></span><br>
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
                foreach($emsit as $hour){

                    $V_sca+=$hour['vsc'];
                    $V_rca+=$hour['vrc'];
                    $V_pmdba+=$hour['var1'];
                    $Time+=$hour['duration'];

                    ?>
                    <tr>
                        <td><?=getEmergSignLabel($hour['params'])?></td>
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


    <?php if($diag){?>
        <div style="width: 100%;text-align: center; margin-top:20px">
            <p>ДИАГНОСТИЧЕСКИЕ СООБЩЕНИЯ</p>
            <table style="width: 100%; border-bottom-color: #0a0a0a;border: 1; border-bottom-style: dashed;border-top-style: dashed;border-left: none;border-right: none ">
                <thead>
                <tr><th>Дата</th><th>Время</th><th>Сообщение</th><th>Объем с начала Суток(с.у) м3</th></tr>
                </thead>
                <tbody>
                <?php foreach($diag as $hour){?>
                    <tr><td><?=sprintf("%'02d",$hour->day).'.'.sprintf("%'02d",$hour->month).'.'.$hour->year?></td><td><?=sprintf("%'02d",$hour->hour).':'.sprintf("%'02d",$hour->minutes).':'.sprintf("%'02d",$hour->seconds)?></td><td> <?=getDiagnostLabel($hour->params)?> </td><td><?=number_format(round($hour->vsc,2),2,'.','')?></td></tr>
                <?php } ?>
                </tbody>
            </table>

        </div><br>


    <?php }; ?>

    <?php if($intervention){?>
        <div style="width: 100%;text-align: center; margin-top:20px">
            <p>ВМЕШАТЕЛЬСВА В РАБОТУ ВЫЧИСЛИТЕЛЯ</p>
            <table style="width: 100%; border-bottom-color: #0a0a0a;border: 1; border-bottom-style: dashed;border-top-style: dashed;border-left: none;border-right: none ">
                <thead>
                <tr><th>Дата</th><th>Время</th><th>Наименование параметра</th><th colspan="2">Значение параметра</th></tr>
                <tr><th></th><th></th><th></th><th>Старое</th><th>Новое</th></tr>
                </thead>
                <tbody>
                <?php foreach($intervention as $hour){?>
                    <tr><td><?=sprintf("%'02d",$hour->day).'.'.sprintf("%'02d",$hour->month).'.'.$hour->year?></td><td><?=sprintf("%'02d",$hour->hour).':'.sprintf("%'02d",$hour->minutes).':'.sprintf("%'02d",$hour->seconds)?></td><td> <?=getIntervLabel($hour->params)?> </td><td><?=number_format(round($hour->old_params,2),2,'.','')?></td><td><?=number_format(round($hour->new_params,4),4,'.','')?></td></tr>
                <?php } ?>
                </tbody>
            </table>
        </div>
    <?php }; ?>


    <div style="width: 100%;text-align: center; margin-top:0px">
        <span ><?=sprintf("%'-65s",'')?>Конец отчета<?=sprintf("%'-65s",'')?></span>

        <table style="width: 100%;">
            <tr><td>Представитель поставщика</td><td width ="200px">  </td><td>Представитель потребителя</td></tr>
            <tr><td>________________________</td><td width="200px">  </td><td>_________________________</td></tr>
        </table>

    </div>
</div>
<?php
function getIntervLabel($id){


    $interv=\app\models\InterventionLabel::findOne(['intervention_id'=>$id]);

    if($interv)
    { return $interv->name;}
    else{
        return "in_label:".$id;
    }

}

function getEmergLabel($id){


    $interv=\app\models\EmergencyLabel::findOne(['emergency_id'=>$id]);

    if($interv)
    { return $interv->name;}
    else{
        return "em_label:".$id;
    }

}

function getEmergSignLabel($id){


    $interv=\app\models\EmergencySignLabel::findOne(['emergency_id'=>$id]);

    if($interv)
    { return $interv->name;}
    else{
        return "em_label:".$id;
    }

}

function getDiagnostLabel($id){


    $interv=\app\models\DiagnosticLabel::findOne(['diagnostic_id'=>$id]);

    if($interv)
    { return $interv->name;}
    else{
        return "em_label:".$id;
    }

}

?>