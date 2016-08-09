<?php

use yii\grid\GridView;
use app\components\AjaxLinkPager;
use app\components\GetAlerts;
use yii\grid\ActionColumn;
use app\components\AllCountersTabFilter;

AllCountersTabFilter::begin(['searchModel' => $searchModel]);
echo GridView::widget(
    [
        'id' => $gridViewId,
        'layout' => '<div class="row"><div class="col-sm-6 pager-row">{summary}</div></div> {items} {pager}',
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'options' => [
            'cellspacing' => 0,
            'cellpadding' => 0,
            'class' => 'inner-table-events',
            'template' => '',
        ],
        'headerRowOptions' => ['class' => 'breakword sort',],
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
                //'header' => 'Физ.\Юр. Лицо',
                'attribute' => 'user_type',
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
                //'header' => 'Адрес',
                'attribute' => 'fulladdress',
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
                'attribute' => 'type',
                'value' => function ($model) {
                    return $model->getCounterTypeText();
                },
                'filter' => app\models\UserCounters::getCounterTypeList(),
                'options' => ['width' => '60px'],
            ],
            [
               // 'header' => 'Ф.И.О.',
                'attribute'=>'fullname',
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
               // 'header' => 'Заряд Батареи',
                'attribute'=>'battery_level',
                'format' => 'raw',
                'value' => function ($model) {
                    if (!empty($model->rmodule)) {
                        return $model->rmodule->battery_level;
                    }
                },
            ],
            [
                //'header' => 'Потребление за период',
                'attribute'=>'flatindications',
                'format' => 'raw',
                'value' => function ($model) {
                    return round($model->flatindications, 3);
                },
            ],
            [
               // 'header' => 'Начало периода',
                'attribute'=>'period_begin',
                'format' => 'html',
                'value' => function ($model) {

                    return $model->getFirstFlatIndications($model->serial_number);
                },
            ],
            [
                //'header' => 'конец периода',
                'attribute'=>'period_end',
                'format' => 'html',
                'value' => function ($model) {

                    return $model->getLastFlatIndications($model->serial_number);
                },
            ],
            [
                //'header' => 'Текущие показания',
                'attribute'=>'current_indication',
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
                    'edit' => function ($url, $model) {
                        $url = Yii::$app->urlManager->createUrl(["admin/counter/editcounter", 'id' => $model->id, "&backUrl=admin/counter", ((isset($_GET["page"])) ? "?page=" . $_GET["page"] : "")]);
                        $label = Yii::t('counter','counter_edit');
                        return \yii\helpers\Html::a('<i class="fa fa-edit"></i>', $url, ['title' => Yii::t('yii', $label), 'data-pjax' => '0']);
                    },
                ]
            ]
        ]
    ]
);


        