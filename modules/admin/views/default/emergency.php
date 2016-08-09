<div style="width: 100%;height: 100%;" xmlns="http://www.w3.org/1999/html">
    <?php if($emsit){?>
        <div style="width: 100%;text-align: center; margin-top:20px">
            <p>АВАРИЙНЫЕ СИТУАЦИИ</p>
            <table style="width: 100%; border-bottom-color: #0a0a0a;border: 1; border-bottom-style: dashed;border-top-style: dashed;border-left: none;border-right: none ">
                <thead>
                <tr><th>Время первого появления</th><th>Аварийный признак</th><th>Длительность</th><th >Объем </th><th >Колво проявлений</th></tr>
                <tr><th></th><th></th><th></th><th>р.у м3</th><th > с.у м3</th><th ></th></tr>
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
                    <td>Итого:</td><td></td> <td><?=sprintf("%'   30s%'02d:%'02d:%'02d","    ",$Time/3600,$Time%3600/60,$Time%3600%60)?></td><td><?=sprintf("          %s",number_format($V_rca,2,'.',''))?></span></td><td><?=sprintf("          %s",number_format($V_sca,1,'.',''))?></span></td><td></td>
                </tr>
            </table>

            <span>Аварийный объем  при р.у. помешенный в основную базу:<?=sprintf("%s",number_format(abs($V_rca-$avV),2,'.',''))?></span><br>
            <span>Аварийный объем  при c.у. помешенный в основную базу:<?=sprintf("%s",number_format(abs($V_sca-$avVsu),1,'.',''))?></span><br>
        </div>

    <?php }; ?>




<?php if($emsit){?>
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
        <table style="width: 100%;border: 1;color: black">
            <tr>
                <td width="453px">Итого:</td>
                <td><?=sprintf("%'02d:%'02d:%'02d",$Time/3600,$Time%3600/60,$Time%3600%60)?></td>
                <td><?=sprintf("%s",number_format($V_rca,1,'.',''))?></span></td>
                <td><?=sprintf("%s",number_format($V_sca,1,'.',''))?></span></td>
                <td><?=sprintf("%s"," ")?></td>
            </tr>
        </table>

    </div><br>


<?php }; ?>
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