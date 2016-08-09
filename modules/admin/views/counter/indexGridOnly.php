<?php

use yii\helpers\Html;
use app\components\managePageSize\ManagePageSize;
use yii\grid\GridView;
use yii\grid\ActionColumn;
use yii\bootstrap\ActiveForm;
use yii\jui\DatePicker;
use app\models\Regions;
use yii\helpers\ArrayHelper;
use yii\widgets\Pjax;
use app\components\Chart;
use app\components\CounterMap;
use app\components\AllAlerts;
use app\models\CounterAddressSearch;
use app\components\CounterQuery;
use app\models\UserCounters;


  echo GridView::widget(
                [
                    'id' => 'browse-address-grid',
                    'dataProvider' => $dataProvider,
                    'filterModel' => $searchModel,
                    'filterRowOptions' => ['style' => 'display:none;'],
                    'options' => [
                        'cellspacing' => 0,
                        'cellpadding' => 0,
                        'class' => 'grid-items table table-striped table-bordered table-hover dataTable'
                    ],
                    'pager' => [
                        'options' => [
                            'pagerCssClass' => 'dataTables_paginate paging_bootstrap_full',
                            'itemsCssClass' => 'grid-items',
                            'class' => 'pagination',
                            'selectedPageCssClass' => 'active',
                            'firstPageCssClass' => 'hide',
                            'lastPageCssClass' => 'hide',
                            'prevPageLabel' => 'Previous',
                            'nextPageLabel' => 'Next',
                            'template' => "{summary}<div id='browse-users-grid_container' class='dt-wrapper'>{items}<div class='row'><div class='col-sm-sm-6 text-right pull-right'>{pager}</div></div></div>",
                            'selectableRows' => 0,
                        ],
                    ],
                    'rowOptions' => function ($model){
                                        return [
                                            'geo_location_id'=>$model->id,
                                            'class'=>'drillDown',
                                            'style'=>'cursor:pointer'
                                            ];
                                      },
                    'columns' =>
                    [


                        [
                            'header' => 'Город',
                            'format' => 'raw',
                            'options'=>[],
                            'value' => function ($model) {
                                return $model->region->name;
                            },
                        ],
                        [
                            'header' => 'Улица',
                            'attribute' => 'street',
                            'filter' => false,
                            'format' => 'raw',
                            'value' => function ($model) {
                                return $model->street;
                            },
                        ]
                        ,
                        [
                            'header' => 'Дом',
                            'attribute' => 'house',
                            'filter' => false,
                            'format' => 'raw',
                            'value' => function ($model) {
                                return $model->house;
                            },
                        ]
                        ,
                        [
                            'header' => 'Потребление за период',
                            'format' => 'raw',
                            'value' => function ($model) {
                                //return  round($model->getHouseIndicationsForPeriod(),3) ;
                                return '<button id="consumptionShow" geo_location_id="'.$model->id.'" class="btn-primary" style="padding: 6px 12px" type="button">Расчитать</button>';
                            },
                        ],
                        [
                            'class' => ActionColumn::className(),
                            'header' => '-',
                            'contentOptions' =>
                            [
                                'class' => 'button-column',
                                'width' => '60px',
                            ],
                            'template' => '{login}',
                            'buttons' => [
                                'login' => function($url,$model) {
                                     $model->getHouseCount();
                                            if($model->count>0){

                                                $label = 'Export';
                                                return \yii\helpers\Html::tag('span','<i class="fa fa-file-excel-o"></i>', ['title' => Yii::t('yii', $label),'id'=>'excel-export','style'=>'color:#228B22', 'data-pjax' => '0']);
                                            }},

                                    ]
                        ]
                    ]
                ]
        );
                    
                    

