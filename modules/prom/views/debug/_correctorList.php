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
                [
                    'header' => '№ Корректора',
                    'attribute' => 'corrector_id'

                ],
                [
                    'header' => '№ модема',
                    'attribute' => 'modem_id'

                ],
                [
                    'header' => '№ телефона',
                    'format' => 'raw',
                    'value' => function ($model) {
                        if ($model->status)
                            return $model->status->phone;
                        else {
                            return '-';
                        }
                    },

                ],
                [
                    'header' => 'Баланс',
                    'format' => 'raw',
                    'value' => function ($model) {
                        if ($model->status)
                            return $model->status->balance;
                        else {
                            return '-';
                        }
                    },

                ],
                [
                    'header' => 'Адрес',
                    'filter' => false,
                    'format' => 'raw',
                    'value' => function ($model) {
                        if ($model->address)
                            return $model->address->fulladdress;
                    },
                ],

                [
                    'header' => 'Уровень сигнала',
                    'format' => 'raw',
                    'value' => function ($model) {
                        if ($model->status) {
                            $style = "width:100%;height:100%;color:black;";

                            if ($model->status->signal_level <= 15) {
                                $style .= "background-color:red;";
                            }
                            if ((15 < $model->status->signal_level) and ($model->status->signal_level <= 20)) {
                                $style .= "background-color:yellow;";
                            }
                            if (20 < $model->status->signal_level) {
                                $style .= "background-color:green;";
                            }


                            return Html::tag('p', $model->status->signal_level, ['style' => $style]);
                        } else {
                            return '-';
                        }
                    },

                ],


                [
                    'header' => 'Показания за  предыдушие сутки  ',

                    'filter' => false,
                    'format' => 'raw',
                    'value' => function ($model) {
                        if ($model->momentData) {
                            return round($model->momentData->vprevday_sc, 3);
                        } else {
                            return '-';
                        }
                    },
                ],

                [
                    'header' => ' последний выход на связь ',

                    'filter' => false,
                    'format' => 'raw',
                    'value' => function ($model) {
                        if ($model->status)
                            return $model->status->time_on_line;
                        else {
                            return '-';
                        }
                    },
                ],


                [
                    'header' => ' время обновления ',

                    'filter' => false,
                    'format' => 'raw',
                    'value' => function ($model) {
                        if ($model->momentData)
                            return $model->momentData->created_at;
                        else {
                            return '-';
                        }
                    },
                ],

                [
                    'class' => ActionColumn::className(),
                    'header' => 'Действие',
                    'options' =>
                        [
                            'class' => 'button-column',
                            'width' => '60px',
                        ],
                    'template' => '{conveyor} {askAnswer}',
                    'buttons' => [
                        'conveyor' => function ($url, $model) {
                            $label = 'конвеер';
                            return \yii\helpers\Html::a('<strong>' . $label . '</strong>', Yii::$app->urlManager->createUrl(['prom/debug/conveyor', "CommandConveyorSearch[modem_id]" => $model->modem_id]), ['title' => Yii::t('yii', $label),]);
                        },
                        'askAnswer' => function ($url, $model) {
                            $label = 'запрос-ответ';
                            return \yii\helpers\Html::a('<strong>' . $label . '</strong>', Yii::$app->urlManager->createUrl(['prom/debug/askanswer', "CommandAskAnswerSearch[modem_id]" => $model->modem_id]), ['title' => Yii::t('yii', $label),]);
                        },
                    ]
                ]
            ]
    ]

);
?>
