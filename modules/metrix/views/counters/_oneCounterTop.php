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
            <?php
            echo
            \app\components\ChartOneMetrix::widget([
                'name' => 'monthConsum',
                'width' => '100%',
                'height' => '98%',

                'datasets' => [
                    'labels' => $labels['labels'],
                    'datasets' => [[
                        'label' => "My First dataset",
                        'fillColor' => 'rgba(151,187,205,1)',
                        'strokeColor' => "rgba(87,136,156,1)",
                        'pointColor' =>  "rgba(151,187,205,1)",
                        'pointStrokeColor' => "#fff",
                        'pointHighlightFill' => "#fff",
                        'pointHighlightStroke' => "rgba(87,136,156,1)",
                        'data' => $labels['data'],
                    ],
                    ],
                ],
                //'global' => $globalChartSettings,
                'chartsConfig' => [
                    'bezierCurve' => 'false',
                    'scaleBeginAtZero' => 'false',
                    'animation' => 'false',
                    'showTooltips' => 'false',
                    'pointDot' => "false",
                    'datasetStrokeWidth' => 1,

                ],
            ]);

            ?>
        </div>

        <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4 show-stats" >



                <table  class='table-striped table-hover table-bordered' id='emergDataTable'>


                    <tr>
                        <td><?=Yii::t('metrix','counter_number')?> :</td>
                        <td><?= $counter->serial_number ?></td>
                    </tr>

                    <tr>
                        <td><?=Yii::t('metrix','ConsumpMonth')?>:</td>
                        <td><?= round($counter->flatindications, 3) ?></td>
                    </tr>

                    <tr>
                        <td><?=Yii::t('metrix','current_indication')?>:</td>
                        <td><?= $counter->getCurrentIndications() ?></td>
                    </tr>

                    <tr>
                        <td><?=Yii::t('metrix','ConsumpDayAverage')?>:</td>
                        <td><?= round($counter->flatindications / date("d"), 3) ?></td>
                    </tr>
                    <tr>
                        <td><?=Yii::t('metrix','signal_level')?>:</td>
                        <td><?php echo $counter->modemStatus->signal_level ?></td>
                    </tr>
                    <tr>
                        <td><?=Yii::t('metrix','time_on_line')?>:</td>
                        <td><?= $counter->modemStatus->time_on_line ?></td>
                    </tr>
                    <tr>
                        <td><?=Yii::t('metrix','valve_status')?>:</td>

                        <td> <?=\app\modules\metrix\components\MetrixValveButtonWidget::widget(['id'=>$counter->id])?></td>
                    </tr>


                </table>
                <div style="margin-top:20px;margin-left: 25%;">



                </div>



        </div>

    </div>

</div>
