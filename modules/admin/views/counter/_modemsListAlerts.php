<?php

use yii\grid\GridView;
use yii\grid\ActionColumn;

echo GridView::widget(
        [
            'id' => $gridViewId,
            'layout' => '<div class="row"><div class="col-sm-6 pager-row">{summary}</div><div class="col-sm-6"><a href="javascript:;" class="remove-appended-row"><i class="fa fa-elg fa-times-circle"></i></a></div></div> {items} {pager}',
            'dataProvider' => $dataProvider,
            //'filterModel' => $searchModel,
            'filterRowOptions' => ['style' => 'display:none;'],
            'options' => [
                'cellspacing' => 0,
                'cellpadding' => 0,
                'class' => 'inner-table'
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
            'columns' =>
            [
                   'id',
                 [
                    'header' => 'Адрес',
                    'format' => 'html',
                    'value' => function ($model) {
                      if($model->address){
                      return $model->address->fulladdress;
                      }
                      else
                      {return '-';}
                    },
                ],
                
                'serial_number',
                'phone_number',
                'last_invoice_request',
                'invoice_request',
                 [
                    'header' => 'Заряд Батареи',
                    'format' => 'html',
                    'value' => function ($model) {
                       
                            return $model->counters[0]->battery_level;
                      
                    },
                ],
                            
               
                'signal_level',
                'updated_at',
                'created_at',
                
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
                                $url = Yii::$app->urlManager->createUrl(["admin/counter/editmodem", 'serial_number' => $model->serial_number, "&backUrl=admin/counters", ((isset($_GET["page"])) ? "?page=" . $_GET["page"] : "")]);
                                $label = 'Ereate Account';
                                return \yii\helpers\Html::a('<i class="fa fa-edit"></i>', $url, ['title' => Yii::t('yii', $label), 'data-pjax' => '0']);
                            },
                                  
                                ]
                        ]
                    ]
                ]
        );
?>  