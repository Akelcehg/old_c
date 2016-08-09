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
            //'class' => 'drillDown',
            'style' => 'cursor:pointer'
        ];
    },
            'columns' =>
            [

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
                    'header' => 'Показания за  предыдушие сутки  ',

                    'filter' => false,
                    'format' => 'raw',
                    'value' => function ($model) {
                        if($model->momentData)
                        {return round($model->momentData->vprevday_sc,3);}
                        else{ return '-';}
                    },
                ],
                [
                    'header' => 'Показания за текущие сутки  ',

                    'filter' => false,
                    'format' => 'raw',
                    'value' => function ($model) {
                        if($model->momentData)
                        {return round($model->momentData->vday_sc,3);}
                    },
                ],
                [
                    'header' => 'Температура',

                    'filter' => false,
                    'format' => 'raw',
                    'value' => function ($model) {

                        if($model->momentData){return round($model->momentData->tabs,3);}
                        else{ return '-';}

                    },
                ],
                [
                    'header' => 'давление',

                    'filter' => false,
                    'format' => 'raw',
                    'value' => function ($model) {
                        if($model->momentData)
                        {return round($model->momentData->pabs,3);}
                        else{ return '-';}
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
                    'header' => '-',
                    'options' =>
                        [
                            'class' => 'button-column',
                            'width' => '60px',
                        ],
                    'template' => '{login}&#160;{edit}',
                    'buttons' => [
                        'edit' => function($url, $model) {
                            $url = Yii::$app->urlManager->createUrl(["admin/default/gettxt", 'counterId' => $model->counter_id]);
                            $label = 'суточный отчет';
                            return \yii\helpers\Html::a(Html::Button('Суточный отчет', [
//                                'id' => 'allCounterFilterSubmit',
                                'class' => 'btn btn-primary',
                                'style' => 'padding: 12px 12px ;margin-top:-15px;',
                                //'onclick'=>'$("#browse-address-grid").yiiGridView("applyFilter"); ',
                                //'onclick'=>'$.pjax.reload({container:"#somepjax"});',


                                //
                            ]), $url, ['title' => Yii::t('yii', $label),]);
                        },
                    ]
                ]
            ]
        ]
);
?>

<script type="text/javascript">
    $(document.body).on("dblclick", "#browse-users-grid table tr", function () {
        id = $(this).find('td:first-child').text();
        url = "<?php echo Yii::$app->getUrlManager()->createUrl('admin/users/viewUser', array('backTo' => 'approvedUsers', 'id' => '')); ?>" + id;
        if (parseInt(id)) {
            location.href = url;
        }
    });
</script>
