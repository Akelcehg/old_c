
<?php

use yii\helpers\Html;
use app\assets\AdminAppAsset;
use yii\helpers\Url;
use yii\widgets\PjaxAsset;

AdminAppAsset::register($this);

/* $this->pageTitle = $pageTitle;
  $this->breadcrumbs = array(
  'Administration' => array('administration/index'),
  $pageTitle
  ); */

$this->registerJsFile(Yii::$app->request->baseUrl . '/js/counter/CounterScripts.js', ['position' => 2]);

?>

<div id="content">

    <section id="widget-grid">
         <div class="row" >
                 <?php
                    echo $this->render('/layouts/partials/jarviswidget', array(
                        'dataWidgetCollapsed' => 'true',
                        'class' => 'jarviswidget-color-blue',
                        'id'=>'dayChartWidget',
                        'header' =>
                        $this->render('/layouts/partials/jarviswidget/title', [
                         'title' => 'График расхода газа <span id="timeperiod"></span><span id="counterAddress"></span>',
                            'icon' => 'bar-chart-o'
                                ], true),
                        'control' => $this->render('/layouts/partials/jarviswidget/control', array(
                            'buttons' => array('<div class="widget-toolbar" id="showing2">
									<!-- add: non-hidden - to disable auto hide -->
									<div class="btn-group">
										<button class="dropdown-toggle btn-xs btn-default" id="showing" data-toggle="dropdown">
											По дням <i class="fa fa-caret-down"></i>
										</button>
										<ul class="dropdown-menu js-status-update pull-right">
											<li>
												<a href="javascript:void(0);" id="mt">График за месяц</a>
											</li>

                                                                                        <li>
												<a href="javascript:void(0);" id="wk">График за неделю</a>
											</li>

											<li>
												<a href="javascript:void(0);" id="td">График по дням</a>
											</li>
										</ul>
									</div>
								</div>',
                                '<div class="widget-toolbar" id="showing1">'
                                . '<span id="chartImageUpload">Сохранить как Изображение<span>'
                                . '</div>'
                                .'<div class="widget-toolbar" id="showing3">'
                                . '<span id="chartExcelUpload">Сохранить как Excel<span>'
                                . '</div>'
                            ),
                                ), true),
                        'content' => \app\components\ChartMulti::widget([
                            'name' => 'dayChart',
                            'height' => '200px',
                            'width' => '100%',
                            'type1' => 'dayChart',
                            'global' => $globalChartSettings,
                            'chartsConfig' => [
                                'bezierCurve' => 'false',
                                'scaleBeginAtZero'=>'false',
                            ],
                        ])
                    ));
                    ?>
                </div>

        <div class="row" >


            
                <?php
                echo $this->render('/layouts/partials/jarviswidget', array(
                    'class' => 'jarviswidget-color-blue',
                    'header' =>
                    $this->render('/layouts/partials/jarviswidget/title', array(
                        'title' => 'Таблица Показаний'
                            ), true),
                    'control' => $this->render('/layouts/partials/jarviswidget/control', array(
                            ), true),
                    'content' => $this->render('_addressList', [
                        'dataProvider' => $dataProvider,
                        'model' => $address,
                        //'alerts' => $alerts,
                        'searchModel' => $searchModel,
                            ], true)
                ));
                ?>
           

           

        </div>
</div>

</section>
