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
        <div id="updating-chart4" class="chart-large txt-color-blue"
             style="padding: 0px;margin-right: 1% ;position: relative;float: left;width: 64%">
            <?= \app\components\ChartRealtimeCounter1::widget(
                [   'name'=>'4',
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
        <?=\app\modules\counter\components\CounterSummaryWidjet::widget(['user_type'=>'legal_entity'])?>

    </div>
</div>
