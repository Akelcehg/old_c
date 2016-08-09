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
             style="padding: 0px;margin-right: 1% ;position: relative;float: left;width: 64%">
            <?= \app\components\ChartRealtime::widget(
                [
                    'width' => '65%',
                    'height' => '90%',
                    'global' => $globalChartSettings,
                    'chartsConfig' => [
                        'pointDot' => 'false',
                        'bezierCurve' => 'false',
                        'datasetStrokeWidth' => 1,
                        'scaleFontSize' => 10,
                    ]
                ]) ?>
        </div>
        <?=\app\modules\prom\components\PromSummaryWidjet::widget()?>

    </div>

    <div class="show-stat-microcharts">

        <?=\app\modules\prom\components\TempMicrochartWidget::widget()?>
        <?=\app\modules\prom\components\PressMicrochartWidget::widget()?>
        <?=\app\modules\prom\components\QMicrochartWidget::widget()?>

        <div class="col-xs-12 col-sm-3 col-md-3 col-lg-3">
            <?=\app\modules\prom\components\SummaryMonthEmergencyMFWidjet::widget()?>
        </div>

    </div>


</div>
