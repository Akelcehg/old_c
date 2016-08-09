<?php


use yii\grid\ActionColumn;
use yii\grid\GridView;
use yii\helpers\Html;
use app\assets\AdminAppAsset;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use yii\widgets\PjaxAsset;

AdminAppAsset::register($this);
PjaxAsset::register($this);

$this->registerJsFile(Yii::$app->request->baseUrl . '/js/corrector/SummaryScripts.js', ['position' => 2]);

?>
        <?=GridView::widget(
    [
        'id' => 'browse-month-reports-grid',
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
        'columns' => [
            [
                'header' => 'Дата',
                'filter' => false,
                'format' => 'raw',
                'value' => function ($dataElement) {
                    return $dataElement->created_at ;
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
                'template' => '{view}<br>{download}<br>{edit}',
                'buttons' => [
                    'view' => function($url, $dataElement) {
                        $url = \Yii::$app->urlManager->createUrl(["prom/summary/viewsummary",
                            'id' => $dataElement->id,
                        ]);

                        return \yii\helpers\Html::a("<strong>просмотр</strong>", $url);
                    },
                    'download' => function($url, $dataElement) {
                        $url = \Yii::$app->urlManager->createUrl(["prom/summary/loadsummary",
                            'id' => $dataElement->id,
                        ]);

                        return \yii\helpers\Html::a("<strong>скачать PDF</strong>", $url);
                    },
                    'edit' => function($url, $dataElement) {
                        $url = \Yii::$app->urlManager->createUrl(["prom/summary/editsummary",
                            'id' => $dataElement->id,
                        ]);

                        return \yii\helpers\Html::a("<strong>редактировать</strong>", $url);
                    },
                ]
            ]
        ],

    ]
);?>


