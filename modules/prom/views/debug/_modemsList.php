<?php

use yii\grid\ActionColumn;
use yii\grid\GridView;
use app\components\CounterTabFilter;
use yii\helpers\Html;

?>
<div class="clientSearch sferaUsers">

    <div style="float:right;">

        <span id="count_users" class="count_users"></span>
    </div>
    <div class="clear"></div>
</div>

<?php
///CounterTabFilter::begin(['searchModel' => $searchModel]);
echo GridView::widget(
    [
        'id' => 'browse-correctors-grid',
        'dataProvider' => $dataProvider,

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
        'rowOptions' => function ($model) {
            return [
                'geo_location_id' => $model->id,
                'style' => 'cursor:pointer'
            ];
        },
        'columns' =>
            [
                'id',
                'modem_id',
                'balance',
                'invoice',
                'phone',
                'time_on_line',
                'status',
                'signal_level',
                [
                'class' => ActionColumn::className(),
                'header' => 'Действие',
                'options' =>
                    [
                        'class' => 'button-column',
                        'width' => '60px',
                    ],
                'template' => '{conveyor} ',
                'buttons' => [
                    'conveyor' => function ($url, $model) {
                        $label = 'лог';
                        return \yii\helpers\Html::a('<strong>' . $label . '</strong>', Yii::$app->urlManager->createUrl(['prom/debug/onemodem', "id" => $model->modem_id]), ['title' => Yii::t('yii', $label),]);
                    },
                ],
            ],


            ]
    ]

);
?>
