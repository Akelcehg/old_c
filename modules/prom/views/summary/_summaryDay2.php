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
        <div id="updating-chart2" class="chart-large txt-color-blue"
             style="padding: 0px;margin-right: 1% ;position: relative;float: left;width: 64%">
            <?= \app\components\ChartRealtime1::widget(
                [   'name'=>'2',
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

        <?=\app\modules\prom\components\PromSummaryWidjet::widget(['type'=>'prom'])?>

    </div>




</div>
