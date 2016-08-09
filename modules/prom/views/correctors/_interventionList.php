<?php

use yii\grid\ActionColumn;
use yii\grid\GridView;
use app\components\CounterTabFilter;
use yii\helpers\Html;
?>

<?php
echo $this->render('_filter', [

]);

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
            'class' => 'grid-items table table-striped table-bordered table-hover dataTable '
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
                'all_id' => $model->id,
                'style' => 'cursor:pointer',
                'class'=>'emDrillDown'
            ];
        },
        'columns' =>
            [
                [

                    'header' => 'Предприятие',
                    'filter' => false,
                    'format' => 'raw',
                    'value'  => function($model) {
                        return $model->company;
                    }
                ],
                [
                    'header' => 'Адрес',
                    'filter' => false,
                    'format' => 'raw',
                    'value' => function ($model) {
                        if($model->address)
                            return $model->address->fulladdress;
                    },
                ],
                [
                    'header' => 'количество вмешательсв за месяц  ',

                    'filter' => false,
                    'format' => 'raw',
                    'value' => function ($model) {
                        if(!empty($model->interventionOnThisMonth))
                        {
                            return $model->interventionOnThisMonthCount;
                        }else{
                            return '0';
                        }
                    },
                ],
                [
                    'header' => ' время обновления ',

                    'filter' => false,
                    'format' => 'raw',
                    'value' => function ($model) {
                        if($model->momentData)
                            return $model->momentData->created_at;
                        else{ return '-';}
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
                    'template' => '{view}',
                    'buttons' => [
                        'view' => function($url, $model) {
                            $label = 'Просмотр';
                            return \yii\helpers\Html::a('<strong>Просмотр</strong>', $url, ['title' => Yii::t('yii', $label),]);
                        },
                    ]
                ]
            ]
    ]
);
?>
