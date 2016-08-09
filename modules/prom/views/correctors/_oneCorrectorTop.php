<?php
/**
 * Created by PhpStorm.
 * User: alks
 * Date: 11.05.2016
 * Time: 15:37
 */


?>


<div width="100%">
    <div class="row no-space">
        <div id="updating-chart" class="chart-large txt-color-blue"
             style="padding: 0px;margin-right: 1% ;position: relative;float: left;width: 64%;height:300px;max-height: 272px">
            <?= \app\components\ChartRealtime2::widget(
                [
                    'typeCorr'=>$cc->type,
                    'width' => '65%',
                    'height' => '90%',
                    'global' => $globalChartSettings,
                    'chartsConfig' => [
                        'animation'=>'false',
                        'pointDot' => 'false',
                        'bezierCurve' => 'false',
                        'datasetStrokeWidth' => 1,
                        'scaleFontSize' => 10,
                    ]
                ]) ?>
        </div>

        <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4 show-stats" >

            <table  class='table-striped table-hover table-bordered' id='emergDataTable'>
              <?php if(!empty($cc->contract)) { ?>
                <tr>
                    <td><?=Yii::t('prom','Contract')?>:</td>
                    <td><?php echo $cc->contract ?></td>
                </tr>
                <?php } ?>

                <?php if(!empty($cc->company)) { ?>
                <tr>
                    <td><?=Yii::t('prom','Company')?>:</td>
                    <td><?php echo $cc->company ?> </td>
                </tr>
                <?php } ?>

                <?php if(!empty($stg->zavod_number)) { ?>
                <tr>
                    <td> <?=Yii::t('prom','Calculator number')?>:</td>
                    <td><?php echo isset($stg) ? $stg->zavod_number : "-" ?></td>
                </tr>
                <?php } ?>

                <?php if(!empty($md->tabs) ){ ?>
                <tr>
                    <td><?=Yii::t('prom','Current temperature')?>:</td>
                    <td><?php echo isset($md) ? round($md->tabs,3) : "-"; echo Yii::t('common','C') ?></td>
                </tr>
                <?php } ?>

                <?php if(!empty($md->pabs)) { ?>
                <tr>
                    <td><?=Yii::t('prom','Current pressure')?>:</td>
                    <td><?php echo isset($md) ? round($md->pabs,3) : "-"; echo Yii::t('common','kgf / m2') ?></td>
                </tr>
                <?php } ?>
                <?php if(!empty($cc->dateOptions)) { ?>
                <tr>
                    <td><?=Yii::t('prom','Contract hour')?>:</td>
                    <td><?php echo isset($cc->dateOptions) ? $cc->dateOptions->contract_hour.":00" : "-";?></td>
                </tr>
                <?php } ?>

                <tr>
                    <td><?=Yii::t('prom','Daily report')?>:</td>
                    <td><?=\app\modules\prom\components\ReportChecker\widgets\DayReportIsValidTopWidget::widget(['id'=>$cc->id])?></td>
                </tr>

            </table>
            <?=\app\modules\prom\components\PromSummaryWidjet1::widget(['type'=>$cc->type,'id'=>$cc->id])?>

        </div>

    </div>

    <div class="show-stat-microcharts">

        <?=\app\modules\prom\components\TempMicrochartWidget::widget(['type'=>$cc->type,'id'=>$cc->id])?>
        <?=\app\modules\prom\components\PressMicrochartWidget::widget(['type'=>$cc->type,'id'=>$cc->id])?>
        <?=\app\modules\prom\components\Limit\widgets\LimitMicrochartWidget::widget(['type'=>$cc->type,'id'=>$cc->id])?>

        <div class="col-xs-12 col-sm-3 col-md-3 col-lg-3">
            <?=\app\modules\prom\components\SummaryMonthEmergencyMFWidjet::widget(['id'=>$cc->id])?>
        </div>

    </div>


</div>
