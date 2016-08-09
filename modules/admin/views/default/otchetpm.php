<div style="width: 100%;height: 100%;" xmlns="http://www.w3.org/1999/html">

    <div style="float: right; width:150px">
        <p >Коммерческий отчет</p>
    </div>


    <div style="width: 100%;text-align: center; margin-top:20px">
        <p>МЕСЯЧНЫЙ ОТЧЕТ</p>
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

        <table width="100%">
            <tr>
                <td><p>Метод измерений:</p></td>
                <td><p><?=$metodDimName?></p></td>
                <td width="50px"></td>
                <td><p>Контрактный час:</p></td>
                <td><p><?=$contractTime?></p></td>
            </tr>
            <tr>
                <td><p>Тип отбора:</p></td>
                <td><p><?=$typeOtb?></p></td>
                <td width="50px"></td>
                <td><p>Отсечка, кгс/м2:</p></td>
                <td><p><?=$otsechka?></p></td>
            </tr>
            <tr>
                <td><p>Плотность, кг/м3:</p></td>
                <td><p><?=$density?></p></td>
                <td width="50px"></td>
                <td><p>Молярная доля СО2,%:</p></td>
                <td><p><?=$molCO2?></p></td>
            </tr>
            <tr>
                <td><p>Шероховатость Rш , мм:</p></td>
                <td><p><?=$sharpness?></p></td>
                <td width="50px"></td>
                <td><p>Молярная доля N2,%:</p></td>
                <td><p><?=$molN2?></p></td>
            </tr>
            <tr>
                <td><p>Диаметр трубы , мм:</p></td>
                <td><p><?=$Dtube?></p></td>
                <td width="50px"></td>
                <td><p>Диаметр СУ , мм:</p></td>
                <td><p><?=$Dsu?></p></td>
            </tr>
            <tr>
                <td><p>Коэф. а0 для Ктр трубы:</p></td>
                <td><p><?=$a0?></p></td>
                <td width="50px"></td>
                <td><p>Коэф. а0 для Ктр СУ:</p></td>
                <td><p><?=$a0su?></p></td>
            </tr>
            <tr>
                <td><p>Коэф. а1 для Ктр трубы:</p></td>
                <td><p><?=$a1?></p></td>
                <td width="50px"></td>
                <td><p>Коэф. а1 для Ктр СУ:</p></td>
                <td><p><?=$a1su?></p></td>
            </tr>
            <tr>
                <td><p>Коэф. а2 для Ктр трубы:</p></td>
                <td><p><?=$a2?></p></td>
                <td width="50px"></td>
                <td><p>Коэф. а2 для Ктр СУ:</p></td>
                <td><p><?=$a2su?></p></td>
            </tr>
            <tr>
                <td><p>Дин. вязкость, кгс*с/м2</p></td>
                <td><p><?=$Dvyaz?></p></td>
                <td width="50px"></td>
                <td><p>Тип давления:</p></td>
                <td><p><?=$pressureType?></p></td>
            </tr>
              <tr>
                <td><p>НПИ давления , кгс/см2:</p></td>
                <td><p><?=number_format($pressureNPI,4,'.','')?></p></td>
                <td width="50px"></td>
                <td><p>ВПИ давления , кгс/см2:</p></td>
                <td><p><?=number_format($pressureVPI,4,'.','')?></p></td>
            </tr>
            <tr>
                <td><p>НПИ температуры , гр .Целс:</p></td>
                <td><p><?=number_format($tempNPI,4,'.','')?></p></td>
                <td width="50px"></td>
                <td><p>ВПИ температуры , гр .Целс</p></td>
                <td><p><?=number_format($tempVPI,4,'.','')?></p></td>
            </tr>
            <tr>
                <td><p>НПИ перепада , кгс/см2:</p></td>
                <td><p><?=number_format($perepNPI,4,'.','')?></p></td>
                <td width="50px"></td>
                <td><p>ВПИ перепада , кгс/см2:</p></td>
                <td><p><?=number_format($perepVPI,4,'.','')?></p></td>
            </tr>
            <tr>
                <td><p>Межконтр. интервал, год :</p></td>
                <td><p><?=number_format($mki,4,'.','')?></p></td>
                <td width="50px"></td>
                <td><p>R  закругл. кромки, мм:</p></td>
                <td><p><?=number_format($Rzkrug,4,'.','')?></p></td>
            </tr>

            <tr>
                <td colspan="2"><p>При dР < dPmin принимается dP=dPmin</p></td>
                <td width="50px"></td>
                <td colspan="2"><p>При P < Pmin принимается P=Pmin</p></td>

            </tr>



        </table>


    <div style="width: 100%;text-align: center; margin-top:20px">
        <p>СУТОЧНЫЕ ИЗМЕРИТЕЛЬНЫЕ ДАННЫЕ</p>
        <table style="width: 100%; border-bottom-color: #0a0a0a;border: 1; border-bottom-style: dashed;border-top-style: dashed;border-left: none;border-right: none ">
            <thead>
            <tr><th>Дата</th><th>Полный V</th><th>Ср. перепад</th><th>Ср.давл.</th><th>Ср.темп.</th><th>АВ</th></tr>
            <tr><th></th><th> c.у. ,м3</th><th>кгс/см2 </th><th>кгс/см2</th><th>гр.Целс</th><th></th></tr>
            </thead>
            <tbody>
            <?php
            $Vsc=0;
            $avVsu=0;

            foreach($daydata as $hour){

                $Vsc+=$hour->v_sc;
                $avVsu+=$hour->vav_sc;


                ?>
            <tr><td><?=sprintf("%'02d",$hour->day).'.'.sprintf("%'02d",$hour->month).'.'.$hour->year?></td><td><?=number_format(round($hour->v_sc,2),2,'.','')?></td><td><?=number_format(round($hour->pdelta,2),2,'.','')?></td><td><?=number_format(round($hour->paverage,4),4,'.','')?></td><td><?=number_format(round($hour->taverage,2),2,'.','')?></td><td><?=$hour->emergency?></td></tr>
            <?php } ?>
            </tbody>
        </table>
    </div>
    <div style="width: 100%;text-align: left; margin-top:0px;margin-left: 20px">
        <span>
            <table >
                <tr>
                    <td width="92px"> <b> Итого:</b> </td> <td> <b> <?=number_format($Vsc+$avVsu,2,'.','')?></b></td>
                </tr>

            </table>
        </span><br>
    </div>


    <div style="width: 100%;text-align: left; margin-top:20px">
        <span>Безаварийный объем за месяц при c.у.,м3:<?=sprintf("%'.85s",number_format($Vsc,1,'.',''))?></span><br>
        <span>Аварийный объем за месяц  при c.у.:<?=sprintf("%'.108s",number_format($avVsu,1,'.',''))?></span><br>
        <span>Полный объем за месяц при c.у.:<?=sprintf("%'.90s",number_format($Vsc+$avVsu,1,'.',''))?></span><br>
        <span>Длительность АС dPотс < dP < dPmin или P < P min  за месяц , ч:мин:с:<?=sprintf("%'.62s%'02d:%'02d:%'02d","",$timeQem/3600,$timeQem%3600/60,$timeQem%3600%60)?></span><br>
        <span>Общая длительность аварийных ситуация за месяц , ч:мин:с:<?=sprintf("%'.50s%'02d:%'02d:%'02d","",$timeEmrg/3600,$timeEmrg%3600/60,$timeEmrg%3600%60)?></span><br>
    </div>


        <?php if($emsit){?>
            <div style="width: 100%;text-align: center; margin-top:20px">
                <p>АВАРИЙНЫЕ СИТУАЦИИ</p>
                <table style="width: 100%; border-bottom-color: #0a0a0a;border: 1; border-bottom-style: dashed;border-top-style: dashed;border-left: none;border-right: none ">
                    <thead>
                    <tr><th>Время первого </th><th>Аварийный признак</th><th>Длительность</th><th>Объем </th><th >Кол.</th></tr>
                    <tr><th>появления</th><th></th><th>ЧЧ:ММ:CC</th><th > с.у м3</th><th > появ.</th></tr>
                    </thead>
                    <tbody>
                    <?php
                    $V_sca=0;
                    $V_rca=0;
                    $V_pmdba=0;
                    $Time=0;

                    foreach($emsit as $hour){
                        $V_sca+=$hour['vsc'];

                        $V_pmdba+=$hour['var1'];
                        $Time+=$hour['duration'];
                        ?>
                        <tr>
                            <td><?=$hour['timestamp']?></td>
                            <td><?=getEmergLabel($hour['params'])?></td>
                            <td> <?=sprintf("%'02d:%'02d:%'02d",$hour['duration']/3600,$hour['duration']%3600/60,$hour['duration']%3600%60)?> </td>

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
                        <td>Итого:</td><td width="273px"><?=sprintf("%s"," ")?></td><td width="140px"><?=sprintf("%'02d:%'02d:%'02d",$Time/3600,$Time%3600/60,$Time%3600%60)?></td><td><?=sprintf(" %s",number_format($V_sca,1,'.',''))?></span></td><td></td>
                    </tr>
                </table>


                <span>Аварийный объем  при c.у. помешенный в основную базу:<?=sprintf("%s",number_format($V_sca-$avVsu,1,'.',''))?></span><br>
            </div>


            <div style="width: 100%;text-align: center; margin-top:20px">
                <p>АВАРИЙНЫЕ ПРИЗНАКИ</p>
                <table style="width: 100%; border-bottom-color: #0a0a0a;border: 1; border-bottom-style: dashed;border-top-style: dashed;border-left: none;border-right: none ">
                    <thead>
                    <tr><th> Основной признак </th><th>Длительность</th><th >Объем</th><th >Кол.</th></tr>
                    <tr><th> аварийной ситуации</th><th></th><th > с.у м3</th><th >прояв.</th></tr>
                    </thead>
                    <tbody>
                    <?php
                    $V_sca=0;
                    $V_rca=0;
                    $V_pmdba=0;
                    $Time=0;
                    foreach($emsit as $hour){

                        $V_sca+=$hour['vsc'];

                        $V_pmdba+=$hour['var1'];
                        $Time+=$hour['duration'];

                        ?>
                        <tr>
                            <td><?=getEmergSignLabel($hour['params'])?></td>
                            <td><?=sprintf("%'02d:%'02d:%'02d",$hour['duration']/3600,$hour['duration']%3600/60,$hour['duration']%3600%60)?> </td>

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