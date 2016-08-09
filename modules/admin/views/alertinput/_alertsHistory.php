<?php

use yii\grid\GridView;
use yii\grid\ActionColumn;

echo GridView::widget(
        [
            'id' => 'browse-alerts-types-grid',
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
            //'class'=>'drillDown',
            'style' => 'cursor:pointer'
        ];
    },
            'columns' =>
            [
                'id',
                [ 
                    'attribute' => 'type',
                    'filter'=>app\models\AlertsList::getAlertTypeList(),
                    'value' => function ($model) {
                        return $model->getAlertTypeText();
                    }
                ],
                [
                    'attribute' => 'device_type',
                    'filter'=>app\models\AlertsList::getAlertDeviceTypeList(),
                    'value' => function ($model) {
                        return $model->getAlertDeviceTypeText();
                    }
                ],


                
                'serial_number',
                [
                    'header'  =>'№ модема',
                    'filter' => \yii\helpers\Html::textInput('AlertsListSearch[modem_id]',$searchModel->user_modem_id,['class'=>"form-control"]),
                    'value' => function ($model) {
                        if(isset($model->counter->modem_id)){
                            return $model->counter->modem_id;
                        }

                    }
                ],
                [
                    'attribute' =>'status',
                    'filter'=>app\models\AlertsList::getAllStatuses(),
                    'value' => function ($model) {
                        return $model->getStatusName();
                    }
                ],
                [
                    'attribute'=>'created_at',
                    'filter'=>false
                ],

                [
                    'class' => ActionColumn::className(),
                    'header' => '-',
                    'options' =>
                    [
                        //'class' => 'button-column',
                        'width' => '60px',
                    ],
                    'template' => '{indications}&#160;{edit}',
                    'buttons' => [
                        'edit' => function($url, $model) {
                            $url = Yii::$app->urlManager->createUrl(["admin/alertinput/editalerts", 'id' => $model->id]);
                            $label = 'Edit Alerts';
                            return \yii\helpers\Html::a('<i class="fa fa-edit"></i>', $url, ['title' => Yii::t('yii', $label), 'data-pjax' => '0']);
                        },
                        'indications' => function($url, $model) {
                            $url = Yii::$app->urlManager->createUrl(["admin/counter/getindications", 'user_counter_id' => $model->serial_number, "&backUrl=admin/counter", ((isset($_GET["page"])) ? "?page=" . $_GET["page"] : "")]);
                            $label = 'История показаний';
                            return \yii\helpers\Html::a('<i class="fa fa-clock-o"></i>', $url, ['title' => Yii::t('yii', $label), 'data-pjax' => '0']);
                        },
                            ]
                        ]
                    ]
                ]
        );
?>
