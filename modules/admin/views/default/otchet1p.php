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
        <p>ЧАСОВЫЕ ИЗМЕРИТЕЛЬНЫЕ ДАННЫЕ</p>
        <table style="width: 100%; border-bottom-color: #0a0a0a;border: 1; border-bottom-style: dashed;border-top-style: dashed;border-left: none;border-right: none ">
            <thead>
            <tr><th>Дата</th><th colspan="2">Время</th><th>Полный V</th><th>Ср. перепад</th><th>Ср.давл.</th><th>Ср.темп.</th><th>АВ</th></tr>
            <tr><th></th><th>Начало</th><th>Конец</th><th> c.у. ,м3</th><th>кгс/см2 </th><th>кгс/см2</th><th>гр.Целс</th><th></th></tr>
            </thead>
            <tbody>
            <?php $dd=0; foreach($hours as $hour){ $dd+=$hour->v_sc;?>
            <tr><td><?=sprintf("%'02d",$hour->day).'.'.sprintf("%'02d",$hour->month).'.'.$hour->year?></td><td><?=sprintf("%'02d",$hour->hour_n).':'.sprintf("%'02d",$hour->minutes_n)?></td><td><?=sprintf("%'02d",$hour->hour_k).':'.sprintf("%'02d",$hour->minutes_k)?></td><td><?=number_format(round($hour->v_sc,2),2,'.','')?></td><td><?=number_format(round($hour->pdelta,2),2,'.','')?></td><td><?=number_format(round($hour->paverage,4),4,'.','')?></td><td><?=number_format(round($hour->taverage,2),2,'.','')?></td><td><?=$hour->emergency?></td></tr>
            <?php } ?>
            </tbody>
        </table>
    </div>
    <div style="width: 100%;text-align: left; margin-top:0px;margin-left: 210px">
        <span>
            <table>
                <tr>
                    <td width="80px">  Итого: </td> <td>  <?=number_format($sumSC,5,'.','')?><td>-<?=number_format($dd,5,'.','')?></td>
                </tr>

            </table>
        </span><br>
    </div>


    <div style="width: 100%;text-align: left; margin-top:20px">
        <span>Безаварийный объем за сутки при c.у.,м3:<?=sprintf("%'.85s",number_format($bezavVsu,1,'.',''))?></span><br>
        <span>Аварийный объем за сутки при c.у.:<?=sprintf("%'.108s",number_format($avVsu,1,'.',''))?></span><br>
        <span>Полный объем за сутки при c.у.:<?=sprintf("%'.90s",number_format($polVsu,1,'.',''))?></span><br>
        <span>Длительность АС dPотс < dP < dPmin или P < P min  за сутки , ч:мин:с:<?=sprintf("%'.62s%'02d:%'02d:%'02d","",$timeQem/3600,$timeQem%3600/60,$timeQem%3600%60)?></span><br>
        <span>Общая длительность аварийніх ситуация за сутки , ч:мин:с:<?=sprintf("%'.50s%'02d:%'02d:%'02d","",$timeEmrg/3600,$timeEmrg%3600/60,$timeEmrg%3600%60)?></span><br>
    </div>

    <div style="width: 100%;text-align: center; margin-top:0px">
        <span ><?=sprintf("%'-65s",'')?>Конец отчета<?=sprintf("%'-65s",'')?></span>

        <table style="width: 100%;">
            <tr><td>Представитель поставщика</td><td width ="200px">  </td><td>Представитель потребителя</td></tr>
            <tr><td>________________________</td><td width="200px">  </td><td>_________________________</td></tr>
        </table>

    </div>




</div>
