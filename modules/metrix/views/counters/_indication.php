<?php
/**
 * Created by PhpStorm.
 * User: alexey
 * Date: 02.03.16
 * Time: 12:10
 */

$year=Yii::$app->request->get("year",date("y"));
$month=Yii::$app->request->get("month",date("n"));

?>

<div id="monthConsumTable">
<?php
    echo \yii\helpers\Html::dropDownList("year",$year,[15=>"2015",16=>"2016"],["id"=>"year"]);
    echo \yii\helpers\Html::dropDownList("month",$month,[
        1=>Yii::$app->formatter->asDate(date('Y-01-01'),'MMMM'),
        2=>Yii::$app->formatter->asDate(date('Y-02-01'),'MMMM'),
        3=>Yii::$app->formatter->asDate(date('Y-03-01'),'MMMM'),
        4=>Yii::$app->formatter->asDate(date('Y-04-01'),'MMMM'),
        5=>Yii::$app->formatter->asDate(date('Y-05-01'),'MMMM'),
        6=>Yii::$app->formatter->asDate(date('Y-06-01'),'MMMM'),
        7=>Yii::$app->formatter->asDate(date('Y-07-01'),'MMMM'),
        8=>Yii::$app->formatter->asDate(date('Y-08-01'),'MMMM'),
        9=>Yii::$app->formatter->asDate(date('Y-09-01'),'MMMM'),
        10=>Yii::$app->formatter->asDate(date('Y-10-01'),'MMMM'),
        11=>Yii::$app->formatter->asDate(date('Y-11-01'),'MMMM'),
        12=>Yii::$app->formatter->asDate(date('Y-12-01'),'MMMM')],["id"=>"month"]);
    echo \yii\helpers\Html::button(Yii::t('metrix','sform'),["id"=>"sform"]);
?>

<table class='table-striped table-hover table-bordered' style="margin: 5px" id='momentDataTable'>
    <tr>
        <td><?=Yii::t('metrix','period_begin')?></td><td><?=$counter->getFirstFlatIndications($counter->id)?></td>
    </tr>
    <tr>
        <td><?=Yii::t('metrix','period_end')?></td><td><?=$counter->getLastFlatIndications($counter->id)?></td>
    </tr>
    <tr>
        <td><?=Yii::t('metrix',Yii::t('counter','consump_period'))?></td><td><?=$counter->flatIndications?></td>
    </tr>
    <tr>
        <td><?=Yii::t('metrix','current_indication')?></td><td><?=$counter->getCurrentIndications();?></td>
    </tr>
</table>
</div>
