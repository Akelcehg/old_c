<?php

use yii\grid\GridView;
use app\components\AjaxLinkPager;
use app\components\GetAlerts;
use yii\grid\ActionColumn;
use app\components\AllCountersTabFilter;


echo GridView::widget(
        [
            'id' => $gridViewId,
            'layout' => '<div class="row"><div class="col-sm-6 pager-row">{summary}</div><div class="col-sm-6"><a href="javascript:;" class="remove-appended-row"><i class="fa fa-elg fa-times-circle"></i></a></div></div> {items} {pager}',
            'dataProvider' => $dataProvider,
            'filterModel' => $searchModel,
            'options' => [
                'cellspacing' => 0,
                'cellpadding' => 0,
                'class' => 'inner-table',
                'template' => '',
            ],
             'headerRowOptions'=>['class' => 'breakword sort',],
            'rowOptions' => function ($model, $index, $widget, $grid) {
        return ['counter_id' => $model->serial_number, 'class' => 'counter', 'style' => 'cursor: pointer'];
    },
        
            'columns' => [
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
                    'attribute' => 'modem_id',
                    'contentOptions' => ['class' => " breakword",],
                ],
                [
                    'attribute' => 'serial_number',
                    'contentOptions' => ['class' => " breakword",],
                ],
                [
                    'attribute' => 'rmodule_id',
                    'contentOptions' => ['class' => " breakword",],
                ],
                [
                    'header' => 'Физ.\Юр. Лицо',
                    'format' => 'html',
                    'contentOptions' => ['class' => " breakword",],
                    'value' => function ($model) {
                if (!is_null($model->user_type)) {
                    return $model->getUserTypeText();
                } else {
                    return '';
                }
            },
                ],
                [
                    'header' => 'Адрес',
                    'attribute'=>'fulladdress',
                    'format' => 'raw',
                    'contentOptions' => ['class' => " breakword",],
                    'value' => function ($model) {
                        if ($model->address) {
                            return $model->address->fulladdress;
                        } else {
                            return '-';
                        }
                    },
                ],
                'flat',
                'account',
                [
                    'attribute'=>'type',
                    'value'=>function($model){return $model->getCounterTypeText();},
                    'filter'=>app\models\Counter::getCounterTypeList(),
                    'options'=>['width'=>'60px'],
                ],
                [
                    'header' => 'Ф.И.О.',
                    'format' => 'html',
                    'contentOptions' => ['class' => " breakword",],
                    'value' => function ($model) {
                if (!is_null($model->fullname)) {
                    return $model->fullname;
                } else {
                    return '';
                }
            },
                ],

                [
                    'header' => 'Заряд Батареи',
                    'format' => 'raw',
                    'value' => function ($model) {
                        if(isset($model->rmodel)) {
                            return $model->rmodel->battery_level;
                        }else{return "-";}

                    },
                ],
                [
                    'header' => 'Потребление за период',
                    'format' => 'raw',
                    'value' => function ($model) {
                        return round($model->flatindications, 3);
                    },
                ],
                [
                    'header' => 'Начало периода',
                    'format' => 'html',
                    'value' => function ($model) {

                        return $model->getFirstFlatIndications($model->serial_number);
                    },
                ],
                [
                    'header' => 'конец периода',
                    'format' => 'html',
                    'value' => function ($model) {

                        return $model->getLastFlatIndications($model->serial_number);
                    },
                ],
                [
                    'header' => 'Текущие показания',
                    'format' => 'html',
                    'value' => function ($model) {
                        return $model->getCurrentIndications();
                    },
                ],
                [
                    'attribute' => 'updated_at',
                    'contentOptions' => ['class' => " breakword",],
                ],
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
                            $url = Yii::$app->urlManager->createUrl(["admin/counter/editcounter", 'serial_number' => $model->serial_number, "&backUrl=admin/counter", ((isset($_GET["page"])) ? "?page=" . $_GET["page"] : "")]);
                            $label = 'редактирование счетчика';
                            return \yii\helpers\Html::a('<i class="fa fa-edit"></i>', $url, ['title' => Yii::t('yii', $label), 'data-pjax' => '0']);
                        },
                            ]
                        ]
                    ]
                ]
        );


        