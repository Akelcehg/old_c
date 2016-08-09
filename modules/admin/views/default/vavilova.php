<?php

use yii\grid\GridView;
use app\components\AjaxLinkPager;
use app\components\GetAlerts;
use yii\grid\ActionColumn;

echo GridView::widget(
    [
        //'id' => $gridViewId,
        'layout' => '<div class="summary"><div class="col-sm-6 pager-row">{summary}</div><div class="col-sm-6"><a href="javascript:;" class="remove-appended-row"><i class="fa fa-elg fa-times-circle"></i></a></div></div> {items} {pager}',
        'dataProvider' => $dataProvider,
        //'filterModel' => $searchModel,
        'options' => [
            'class' => 'inner-table',
        ],
        'headerRowOptions'=>['class' => 'breakword sort',],
        'rowOptions' => function ($model, $index, $widget, $grid) {
            return ['counter_id' => $model->id, 'class' => 'counter', 'style' => 'cursor: pointer'];
        },
        'pager' => [
            'class' => AjaxLinkPager::className(),
            //'parentId' => $gridViewId,
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
                'header'=>'Время',
                'attribute' => 'updated_at',
                'contentOptions' => ['class' => " breakword",],
            ],

            [
                'header' => 'Расход м<sup>3</sup>',
                'format' => 'raw',
                'value' => function ($model) {
                    return round($model->flatindications, 3);
                },
            ],
             [
                'header' => 'Расход кор. м<sup>3</sup> ',
                'format' => 'raw',
                'value' => function ($model) {
                    return round($model->flatindications, 3)*1.20;
                },
            ],
            [
                'header' => 'Температура C<sup>o</sup>',
                'format' => 'raw',
                'value' => function () {
                    return 24;
                },

            ],

 [
                'header' => 'Давление Бар',
                'format' => 'raw',
                'value' => function () {
                    return 1.03;
                },

            ],
        ]
    ]
);


