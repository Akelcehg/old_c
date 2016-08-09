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
        <span>Наименование Комплекса:<?=$correctorName?></span><br>
        <span>Заводской номер Вычислителя:<?=$serialNumber?></span><br>
        <span style="width:100%;"><span><?=$complexName?></span><span style="mar">Т/п 1: <?=$tubeName?></span></span><br>
        <span>Версия ПО:<?=$poVersion?></span><br>
    </div>

    <div style="width: 100%;text-align: left; margin-top:10px;margin-left:-3px">
        <p>Метод расчета:<?=$metodName?></p>
        <p>Наименоване счетчика:<?=$counterName?></p>
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
            <span>Количесво импульсов счетчика на 1 м3:<?=sprintf("%'.97s",number_format($impulseOnM3,4,'.',''))?></span><br>
            <span>Верхний предел измерений при  рабочих условиях (Qmax), м3/ч: <?=sprintf("%'.55s",number_format($qVPI,4,'.',''))?></span><br>
            <span>Миниальный расход при рабочих условиях(Qmin), м3/ч: <?=sprintf("%'.70s",number_format($qNPI,4,'.',''))?></span><br>
            <span>Расход при котором счетчик останавливается, м3/ч: <?=sprintf("%'.76s",number_format($qStop,2,'.',''))?></span><br>
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
            <table>
                <tr>
                    <td width="80px">  Итого: </td> <td width="50px">   <?=number_format($sumRC,2,'.','')?> </td><td>  <?=number_format($sumSC,2,'.','')?></td>
                </tr>

            </table>
        </span><br>
    </div>


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

    <div style="width: 100%;text-align: center; margin-top:0px">
        <span ><?=sprintf("%'-65s",'')?>Конец отчета<?=sprintf("%'-65s",'')?></span>

        <table style="width: 100%;">
            <tr><td>Представитель поставщика</td><td width ="200px">  </td><td>Представитель потребителя</td></tr>
            <tr><td>________________________</td><td width="200px">  </td><td>_________________________</td></tr>
        </table>

    </div>




</div>
