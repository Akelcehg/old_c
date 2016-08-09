<?php
use app\assets\AdminAppAsset;
use app\components\Alerts\widgets\AlertsOneTypeWidget;
use app\modules\counter\components\EventCalendar;
use yii\bootstrap\Tabs;
use yii\helpers\Html;


AdminAppAsset::register($this);


$this->registerJsFile(Yii::$app->request->baseUrl . '/js/counter/CounterScriptsC.js', ['position' => 2]);
?>
<div id="content">
    <div class="row" style="text-align: center">
        <span style="float: left">
            <b>
                <a href="/counter/search">
                    << <?=Yii::t('counter','Search')?>
                </a>
            </b>
        </span>
        <?php if(isset($counter->address->fulladdresswithcity)) { ?>
            <span id="correctorAddress"><?= $counter->address->fulladdresswithcity ?></span>
            <?php
        }
        for ($i=0;$i<3;$i++) {
            if (isset($counter->modem->counters[$i]) and $counter->modem->counters[$i]->serial_number) {
                $options=['style' => 'float:left;'];
                if($counter->id==$counter->modem->counters[$i]->id) {
                    $options = ['style' => 'color:black;font-weight:bolder;float:left;'];
                }

                $span=Html::tag('span',Yii::t('counter','counter_number').($counter->modem->counters[$i]->serial_number),$options);

                echo Html::a(
                     $span,
                    Yii::$app->urlManager->createUrl(['/counter/counter/view', 'id' => $counter->modem->counters[$i]->id]),
                ['style'=>'margin-right:10px;position: relative;float:right;','data-pjax'=>"0"]);
            }
        }

        ?>
    </div>

    <div class="row">


        <div>
            <div class="col-md-6">
                <?php
                // echo \app\components\ChartMultiN::widget([
                if ($oDU['type'] != 'discrete') {
                    echo \app\components\ChartOne::widget([
                        'name' => 'monthTemp',
                        'height' => '100px',
                        'width' => '100%',

                        'datasets' => [
                            'labels' => $labelsTemp['labels'],
                            'datasets' => [[
                                'label' => "My First dataset",
                                'fillColor' => "rgba(0,0,255,0.2)",
                                'strokeColor' => "rgba(0,0,255,1)",
                                'pointColor' => "rgba(0,0,255,1)",
                                'pointStrokeColor' => "#fff",
                                'pointHighlightFill' => "#fff",
                                'pointHighlightStroke' => "rgba(0,0,255,1)",
                                'data' => $labelsTemp['data'],
                            ],
                            ],
                        ],

                        //  'global' => $globalChartSettings,
                        'chartsConfig' => [
                            'bezierCurve' => 'false',
                            'scaleBeginAtZero' => 'false',
                            'animation' => 'false',
                            'showTooltips' => 'false',
                            'pointDot' => "false",
                            'datasetStrokeWidth' => 1,

                        ],
                    ]);
                }
                echo
                \app\components\ChartOne::widget([
                    'name' => 'monthConsum',
                    'height' => '100px',
                    'width' => '100%',

                    'datasets' => [
                        'labels' => $labels['labels'],
                        'datasets' => [[
                            'label' => "My First dataset",
                            'fillColor' => "rgba(255,0,0,0.2)",
                            'strokeColor' => "rgba(255,0,0,1)",
                            'pointColor' => "rgba(255,0,0,1)",
                            'pointStrokeColor' => "#fff",
                            'pointHighlightFill' => "#fff",
                            'pointHighlightStroke' => "rgba(255,0,0,1)",
                            'data' => $labels['data'],
                        ],
                        ],
                    ],
                    //  'global' => $globalChartSettings,
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
            <div class="col-md-6">

                <?=\yii\widgets\DetailView::widget(
                    [
                        'options'=>['class'=>'table-striped table-hover table-bordered','id'=>'counterTopDataTable'],
                        'model'=>$counter,
                        'attributes' => [
                            'account',
                            'serial_number',
                            'monthIndications',
                            'currentIndications',
                            'dayAverageIndications',
                            'updated_at'
                        ],
                    ]
                )?>

            </div>
        </div>
    </div>
    <div class="row" style="margin-top: 10px">
        <div class="widget-body bordered">
            <?php

            echo Tabs::widget([

                'items' => [
                    [
                        'label' => Yii::t('metrix', 'Indication'),
                        'content' => $this->render('_indication', ['counter' => $counter]),
                        'active' => true
                    ],

                    [
                        'label' => Yii::t('metrix', 'Charts'),
                        //'headerOptions' => ['id'=>'charts'],
                        'options' => ['id' => 'chart','style'=>'height:auto;'],
                        'content' => $this->render('_charts', ['id' => $counter->id]),
                    ],

                    [
                        'label' => Yii::t('metrix', 'Reports'),
                        'content' => $this->render('_reports',['counter1'=>$counter]),

                    ],

                    [
                        'label' =>Yii::t('metrix', 'Accidents'),
                        //'content' => AlertsView::widget(['id' => $counter->modem->serial_number, 'status' => 'ACTIVE']),
                        'content' => AlertsOneTypeWidget::widget(['serial_number' => $counter->modem->serial_number,'status' => 'ACTIVE']),

                    ],
                    [
                        'label' => Yii::t('prom', 'Map'),
                        'options' => ['id' => 'map1','style'=>'height:330px;'],
                        'content' => \app\components\CounterMapOne::widget(['geo_location_id' =>  $counter->geo_location_id]),

                    ],
                    [
                        'label' => Yii::t('metrix', 'Counter'),
                        'content' => $this->render('_editCounter', ['counter' => $counter, 'userRoles' => $userRoles]),

                    ],
                    [
                        'label' => Yii::t('metrix', 'Contract'),
                        'content' => $this->render('_editCounterDogovor', ['counter' => $counter, 'userRoles' => $userRoles]),

                    ],
                    [
                        'label' => Yii::t('metrix', 'GSM modem'),
                        'content' => $this->render('_modem', ['modem' => $counter->modem]),

                    ],

                    [
                        'label' => Yii::t('metrix', 'Options'),
                        'content' => $this->render('_options', ['counter' => $counter]),
                        'options'=>['id'=>'optionsTab']

                    ],
                    [
                        'label' => Yii::t('metrix', 'Calendar'),
                        //'content' =>'В разработке',
                        'content' =>$this->render('_calendar', ['counter' => $counter]),
                        'options'=>['id'=>'calendarWidget']

                    ],
                ],
            ]);
            ?>
        </div>
    </div>
</div>