<?php


use yii\grid\GridView;


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
                       
                    ]
                ]
        );
