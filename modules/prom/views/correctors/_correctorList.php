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
                [

                    'attribute' =>'company',// Yii::t('prom','Company'),//'Предприятие',
                    'filter' => false,
                    'format' => 'raw',
                    'value'  => function($model) {
                        return $model->company;
                    }
                ],
                [
                    'header' => Yii::t('prom','Status'),//'состояние',
                    'attribute'=>'status',
                    'filter' => false,
                    'format' => 'raw',
                    'value'=> function ($model) {
                        return \app\modules\prom\components\PromStatusWidjet::widget(['id'=>$model->id]);
                    }
                ],
                [
                    'attribute' => 'geo_location_id',//Yii::t('prom','Address'),//''Адрес',
                    'filter' => false,
                    'format' => 'raw',
                    'value' => function ($model) {
                        if($model->address)
                            return $model->address->fulladdress;
                    },
                ],
                [
                    'header' => Yii::t('prom','Consumption on prev day'),//'Расход за  предыдушие сутки  ',

                    'filter' => false,
                    'format' => 'raw',
                    'value' => function ($model) {
                        if($model->momentData)
                        {return round($model->momentData->vprevday_sc,3);}
                        else{ return '-';}
                    },
                ],
                [
                    'header' => Yii::t('prom','Consumption on day'),//'Расход за текущие сутки  ',

                    'filter' => false,
                    'format' => 'raw',
                    'value' => function ($model) {
                        if($model->momentData)
                        {return round($model->momentData->vday_sc,3);}
                    },
                ],
                [
                    'header' => Yii::t('prom','Temperature'),//'Температура',
                    //'attribute'=>'tabs',
                    'filter' => false,
                    'format' => 'raw',
                    'value' => function ($model) {

                        if($model->momentData){return round($model->momentData->tabs,3);}
                        else{ return '-';}

                    },
                ],
                [
                    'header' => Yii::t('prom','Pressure'),//'давление',
                    //'attribute'=>'pabs',
                    'filter' => false,
                    'format' => 'raw',
                    'value' => function ($model) {
                        if($model->momentData)
                        {return round($model->momentData->pabs,3);}
                        else{ return '-';}
                    },
                ],

                [
                    'header' => Yii::t('prom','Day report'),//'дневной отчет',
                    'filter' => false,
                    'format' => 'raw',
                    'value'=> function ($model) {
                        return \app\modules\prom\components\ReportChecker\widgets\DayReportIsValidWidget::widget(['id'=>$model->id]);
                    }
                ],

                [
                    'header' => Yii::t('prom','Update time'),//' время обновления ',

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
                    'header' => Yii::t('prom','Action'),//'Действие',
                    'options' =>
                        [
                            'class' => 'button-column',
                            'width' => '60px',
                        ],
                    'template' => '{view}',
                    'buttons' => [
                        'view' => function($url, $model) {
                            $label =  Yii::t('prom','View');
                            return \yii\helpers\Html::a('<strong>'.Yii::t('prom','View').'</strong>', $url, ['title' => Yii::t('yii', $label),]);
                        },
                    ]
                ]
            ]
    ]
);
?>
