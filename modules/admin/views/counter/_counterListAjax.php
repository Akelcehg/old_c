<?php

use yii\grid\GridView;
use app\components\AjaxLinkPager;
use app\components\GetAlerts;
use yii\grid\ActionColumn;

echo GridView::widget(
        [
            'id' => $gridViewId,
            'layout' => '<div class="summary"><div class="col-sm-6 pager-row">{summary}</div><div class="col-sm-6"><a href="javascript:;" class="remove-appended-row"><i class="fa fa-elg fa-times-circle"></i></a></div></div> {items} {pager}',
            'dataProvider' => $dataProvider,
            'filterModel' => $searchModel,
            'options' => [
                'class' => 'inner-table',
            ],
            'headerRowOptions'=>['class' => 'breakword sort',],
            'rowOptions' => function ($model, $index, $widget, $grid) {
        return ['counter_id' => $model->id, 'class' => 'counter', 'style' => 'cursor: pointer'];
    },
            'pager' => [
                'class' => AjaxLinkPager::className(),
                'parentId' => $gridViewId,
                'data' => Yii::$app->request->post('data', 0),
                'options' => [

                    'parentId' => 'browse-flatCounter-grid',
                    'pagerCssClass' => 'dataTables_paginate paging_bootstrap_full',
                    'itemsCssClass' => 'grid-items',
                    'class' => 'pagination',
                    'selectedPageCssClass' => 'active',
                    'firstPageCssClass' => 'hide',
                    'lastPageCssClass' => 'hide',
                    'prevPageLabel' => 'Previous',
                    'nextPageLabel' => 'Next',
                    'selectableRows' => 0,
                ],
            ],
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
                /*[
                    'header' => 'Физ.\Юр. Лицо',
                 *  'attribute' =>'user_type',
                    'format' => 'html',
                    'contentOptions' => ['class' => " breakword",],
                    'value' => function ($model) {
                        if (!is_null($model->user_type)) {
                            return $model->getUserTypeText();
                        } else {
                            return '';
                        }
                    },
                ],*/
                [
                    //'header' => 'Адрес',
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
                    //'header' => 'Ф.И.О.',
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

                        return $model->getFirstFlatIndications($model->id);
                    },
                ],
                [
                    //'header' => 'конец периода',
                    'attribute'=>'period_end',
                    'format' => 'html',
                    'value' => function ($model) {

                        return $model->getLastFlatIndications($model->id);
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
                    'template' => '{indications}&#160;{edit}',
                    'buttons' => [
                        'edit' => function($url, $model) {
                            $url = Yii::$app->urlManager->createUrl(["counter/counter/view", 'id' => $model->id,'lang'=>Yii::$app->request->get('lang',0), "&backUrl=admin/counter", ((isset($_GET["page"])) ? "?page=" . $_GET["page"] : "")]);
                            $label = 'Просмотр';
                            return \yii\helpers\Html::a('<strong>'.Yii::t('counter','view').'</strong>', $url, ['title' => Yii::t('yii', $label), 'data-pjax' => '0']);
                        },
                       /* 'indications' => function($url, $model) {
                            $url = Yii::$app->urlManager->createUrl(["admin/counter/getindications", 'counter_id' => $model->id, "&backUrl=admin/counter", ((isset($_GET["page"])) ? "?page=" . $_GET["page"] : "")]);
                            $label = 'История показаний';
                            return \yii\helpers\Html::a('<i class="fa fa-clock-o"></i>', $url, ['title' => Yii::t('yii', $label), 'data-pjax' => '0']);
                        },*/
                            ]
                        ]
                    ]
                ]
        );


        