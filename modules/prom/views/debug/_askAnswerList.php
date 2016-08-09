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

echo GridView::widget(
    [
        'id' => 'browse-conveyor-grid',
        'dataProvider' => $dataProvider,
        'filterModel'=>$searchModel,


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
                'corrector_id',
                'branch_id',
                'ask',
                [
                    'attribute'=>'answer',
                    'contentOptions' => ['style' => " word-break:break-all",],
                ],
                'command',
                'answered_at'
            ]
    ]
);
?>
