<?php

use yii\grid\GridView;
use app\components\AjaxLinkPager;
use app\components\GetAlerts;
use yii\grid\ActionColumn;

echo GridView::widget(
        [
            
            'layout' => '<div class="row"><div class="col-sm-6 pager-row">{summary}</div></div> {items} {pager}',
            'dataProvider' => $dataProvider,
            'filterModel' => $searchModel,
            'options' => [
                'cellspacing' => 0,
                'cellpadding' => 0,
                'class' => 'inner-table-events',
                'style' => 'overflow-x: auto;',
                'template' => '',
            ],

            'columns' =>
            [
                [
                    'header' => 'alerts',
                    'format' => 'raw',
                    'contentOptions' => ['class' => "project-alerts",],
                    'value' =>
                    function ($model) {
                return GetAlerts::widget(['oneCounter' => $model]);
            }
                ,
                ],
                [   
                    'attribute' =>'modem_id',
                    'filter' => true,
                    ],
                'serial_number',
                'rmodule_id',
                [
                    //'header' => 'Физ.\Юр. Лицо',
                    'attribute' =>'user_type',
                    'format' => 'html',
                    'value' => function ($model) {
                        if (!is_null($model->user_type)) {
                            return $model->getUserTypeText();
                        } else {
                            return '';
                        }
                    },
                ],
                'account',
                [
                    //'header' => 'Ф.И.О.',
                    'attribute' =>'fullname',
                    'format' => 'html',
                    'value' => function ($model) {
                        if (!is_null($model->fullname)) {
                            return $model->fullname;
                        } else {
                            return '';
                        }
                    },
                ],

                'flat',
                [
                    //'header' => 'Потребление за период',
                    'attribute' =>'consump_period',
                    'format' => 'raw',
                    'value' => function ($model) {
                        return '<a href="javascript:;" counter_id="' . $model->serial_number . '" class="counter">' . $model->getFlatIndicationsForPeriod($model->serial_number) . '</a>';
                    },
                ],
                [
                    //'header' => 'Начало периода',
                    'attribute' =>'period_begin',
                    'format' => 'html',
                    'value' => function ($model) {

                        return $model->getFirstFlatIndications($model->serial_number);
                    },
                ],
                [
                    //'header' => 'конец периода',
                    'attribute' =>'period_end',
                    'format' => 'html',
                    'value' => function ($model) {

                        return $model->getLastFlatIndications($model->serial_number);
                    },
                ],
                [
                    //'header' => 'Текущие показания',
                    'attribute' =>'current_indication',
                    'format' => 'html',
                    'value' => function ($model) {
                        return $model->getCurrentIndications();
                    },
                ],
                'updated_at',
                [
                    'class' => ActionColumn::className(),
                    'header' => '-',
                    'options' =>
                    [
                        'class' => 'button-column',
                        'width' => '60px',
                    ],
                    'template' => '{login}&#160;{edit}',
                    'buttons' => [
                        'edit' => function($url, $model) {
                            $url = Yii::$app->urlManager->createUrl(["admin/counter/editcounter", 'id' => $model->id, "&backUrl=admin/counter", ((isset($_GET["page"])) ? "?page=" . $_GET["page"] : "")]);
                            $label = 'Редактировать счетчик';
                            return \yii\helpers\Html::a('<i class="fa fa-edit"></i>', $url, ['title' => Yii::t('yii', $label), 'data-pjax' => '0']);
                        },
                                
                            ]
                        ]
                    ]
                ]
        );


        