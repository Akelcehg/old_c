<?php

use yii\grid\GridView;
use app\components\AjaxLinkPager;
use app\components\GetAlerts;
use yii\grid\ActionColumn;

echo GridView::widget(
        [
            'id' => $gridViewId,
            'layout' => '<div class="row"><div class="col-sm-6 pager-row">{summary}</div><div class="col-sm-6"><a href="javascript:;" class="remove-appended-row"><i class="fa fa-elg fa-times-circle"></i></a></div></div> {items} {pager}',
            'dataProvider' => $counters,
            'options' => [
                'cellspacing' => 0,
                'cellpadding' => 0,
                'class' => 'inner-table',
                'template' => '',
            ],
            'pager' => [
                //'class' => AjaxLinkPager::className(),
                //'parentId' => $gridViewId,
                //'data' => Yii::$app->request->post('data', 0),
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
                'modem_id',
                'serial_number',
                [
                    'header' => 'Тип Абонента',
                    'format' => 'html',
                    'value' => function ($model) {
                        
                            return $model->getUserTypeText();
                       
                    },
                ],
                'account',
                [
                    'header' => 'Ф.И.О.',
                    'format' => 'html',
                    'value' => function ($model) {
                        
                            return $model->fullname;
                       
                    },
                ],

                
                'flat',
                [
                    'header' => 'Потребление за период',
                    'format' => 'raw',
                    'value' => function ($model) {
                        return '<a href="javascript:;" counter_id="' . $model->serial_number . '" class="counter">' . round($model->getFlatIndicationsForPeriod($model->serial_number),3) . '</a>';
                    },
                ],
                [
                    'header' => 'Начало периода',
                    'format' => 'html',
                    'value' => function ($model) {
                        return round($model->getFirstFlatIndications($model->serial_number),3);
                    },
                ],
                [
                    'header' => 'конец периода',
                    'format' => 'html',
                    'value' => function ($model) {
                        return round($model->getLastFlatIndications($model->serial_number),3);
                    },
                ],
                [
                    'header' => 'Текущие показания',
                    'format' => 'html',
                    'value' => function ($model) {
                        return round($model->getCurrentIndications(),3);
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
                            $url = Yii::$app->urlManager->createUrl(["admin/counter/editcounter", 'serial_number' => $model->serial_number, "&backUrl=admin/counter", ((isset($_GET["page"])) ? "?page=" . $_GET["page"] : "")]);
                            $label = 'Edit Counter';
                            return \yii\helpers\Html::a('<i class="fa fa-edit"></i>', $url, ['title' => Yii::t('yii', $label), 'data-pjax' => '0']);
                        },
                                
                            ]
                        ]
                    ]
                ]
        );


        