<?php
/**
 * Created by PhpStorm.
 * User: alexey
 * Date: 25.02.16
 * Time: 13:12
 */

use app\models\CorrectorToCounter;
use app\models\MomentData;
use yii\helpers\Html;

$cc=CorrectorToCounter::findOne(Yii::$app->request->get('id'));
$md=MomentData::find()->where(['all_id'=>$cc->id])->orderBy(['created_at'=>SORT_DESC])->one();
$comConv=\app\models\CommandConveyor::find()->where(['modem_id'=>$cc->modem_id,'command_type'=>2,'status'=>"ACTIVE"])->all();
$isAsked=false;
foreach($comConv as $com){

    $comArr=str_split($com->command);

    if(($comArr[6]=='0')and($comArr[7]=='4'))
    {
        $isAsked=true;
    }

}

?>

<p class="momentData">
    <?=Yii::t('prom','Data for')?>: <?php if(isset($md)) {
        echo $md->created_at;
    }else{echo "-";}
    echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
    if($isAsked){

        echo "<span  id=\"timertext\">".Yii::t('prom','The query is executed expect an answer')."</span> ";

    }else{
    echo Html::Button('Запрос на обновление', [
        'id' => 'getMoments',
        'class' => 'btn btn-primary',
        'style' => 'padding: 12px 12px;10px;10px;',
    ]);
    ?>

    <span style="display: none" id="timertext"> Запрос выполняется ожидайте ответа </span> <span style="display: none" id="timer"> корректор выйдет на связь через : </span>



    <?php }





    echo "<table class='table-striped table-hover table-bordered' id='momentDataTable'>";
    if(isset($md)) {
        foreach ($md->attributeLabels() as $key=>$value)
        {


            if($md->$key>0 and $key!='created_at') {

                echo "<tr><td>".$md->attributeLabels()[$key]."</td> <td>". round($md->$key, 3) . "</td></tr>";
            }


        }
    }

    echo "</table >";


    ?>
</p>
