<?php

use yii\grid\GridView;
use app\components\AjaxLinkPager;
use app\components\GetAlerts;
use yii\grid\ActionColumn;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
$form = ActiveForm::begin([ 'method' => 'GET','action'=>'/admin/countermodel/addcountermodel',
            
        ]);
  echo Html::submitButton(Yii::t('counter','add_counter_model'), [
        //'id' => 'filterSubmitGraph',
        //'class' => 'btn-primary',
        'style' => 'padding: 6px 12px',
        //'onclick'=>'$("#browse-address-grid").yiiGridView("applyFilter"); ',
        //'onclick'=>'$.pjax.reload({container:"#somepjax"});',
        'data-pjax' => true

            //
    ]);
ActiveForm::end();
echo GridView::widget(
        [
            
            'layout' => '<div class="row"><div class="col-sm-6 pager-row">{summary}</div></div> {items} {pager}',
            'dataProvider' => $dataProvider,
            'filterModel'=>$searchModel,
            'options' => [
                'cellspacing' => 0,
                'cellpadding' => 0,
                'class' => 'inner-table-events',
                'template' => '',
                'style'=>'width:1000px'
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
                'id',
                'name',
                ['attribute'=>'type',
                    'value'=>function($model){return $model->getCounterTypeText();},
                    'filter'=>  app\models\CounterModel::getCounterTypeList(),
                    ]
                ,
                'rate',
                   [
                    'class' => ActionColumn::className(),
                    'header' => '-',
                    'options' =>
                    [
                        'class' => 'button-column',
                        'width' => '60px',
                    ],
                    'template' => '{edit}',
                    'buttons' => [
                        'edit' => function($url, $model) {
                            $url = Yii::$app->urlManager->createUrl(["admin/countermodel/editcountermodel", 'id' => $model->id]);
                            $label = 'Edit Counter Model';
                            return \yii\helpers\Html::a('<i class="fa fa-edit"></i>', $url, ['title' => Yii::t('yii', $label), 'data-pjax' => '0']);
                        },
                        
                            ]
                        ]
            ]
        ]
);


