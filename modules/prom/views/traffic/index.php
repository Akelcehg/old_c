<?php


use yii\grid\GridView;
use yii\helpers\Html;
use app\assets\AdminAppAsset;
use yii\helpers\Url;
use yii\widgets\PjaxAsset;

AdminAppAsset::register($this);
PjaxAsset::register($this);
/* $this->pageTitle = $pageTitle;
  $this->breadcrumbs = array(
  'Administration' => array('administration/index'),
  $pageTitle
  ); */

$this->registerJsFile(Yii::$app->request->baseUrl . '/js/chart/Chart_yiir_summary.js');

?>
<div id="content"><?php
    echo GridView::widget(
        [
            'id' => 'browse-address-grid',
            'dataProvider' => $dataProvider,

            'filterRowOptions' => ['style' => 'display:none;'],
            'options' => [
                'cellspacing' => 0,
                'cellpadding' => 0,
                'class' => 'grid-items table table-striped table-bordered table-hover dataTable',
                'width' => '100%',
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
            'rowOptions' => function ($model) {
                return [
                    'geo_location_id' => $model->id,
                    'class' => 'drillDown',
                    'style' => 'cursor:pointer'
                ];
            },
            'columns' =>
                [
                   'id',
                    [
                        'header' => ' № Модема',
                        'format' => 'raw',
                        'value' => function ($model) {
                            return $model->modem_id;
                        },
                    ],
                    [
                        'header' => '  последний трафик  входящий / исходящий  байт',
                        'format' => 'raw',
                        'value' => function ($model) {
                            return $model->lastTrafficInSum.'/'.$model->lastTrafficOutSum;
                        },
                    ],
                    [
                        'header' => 'Трафик за день входящий / исходящий  байт',
                        'format' => 'raw',
                        'value' => function ($model) {
                            return $model->dayTrafficInSum.'/'.$model->dayTrafficOutSum;
                        },
                    ],
                    [
                        'header' => 'Трафик за месяц входящий / исходящий  байт',
                        'format' => 'raw',
                        'value' => function ($model) {
                            return $model->monthTrafficInSum.'/'.$model->monthTrafficOutSum;
                        },
                    ],
                    [
                        'header' => 'Количесво выходов на связь',
                        'format' => 'raw',
                        'value' => function ($model) {
                            return $model->cycle;
                        },
                    ],
                    [
                        'header' => 'Время последнего выхода на связь',
                        'format' => 'raw',
                        'value' => function ($model) {
                            if($model->status){return $model->status->time_on_line;}else{
                                return "-";
                            }

                        },
                    ],

                ]
        ]
    );


    ?>