<?php
/**
 * Created by PhpStorm.
 * User: alks
 * Date: 11.05.2016
 * Time: 15:37
 */


?>




<div width="100%" >
    <div class="row no-space" >
        <div id="updating-chart" class="chart-large txt-color-blue" style="padding: 0px; position: relative;width: 65%;float: left">
            <?=\app\components\BarChart2::widget([
                'name' => 'monthChart',
                'height' => '235px',
                'width' => '100%',
                'type1' => 'dayChart',
                'global' => $globalChartSettings,

                'chartsConfig' => [
                    'bezierCurve' => 'false',
                    'animation'=>'false',
                    //'responsive'=>'true',
                    'scaleBeginAtZero'=>'false',


                ],
            ]);?>
        </div>

        </div>
</div>
