<?php

use yii\grid\GridView;
use app\components\AjaxLinkPager;
use app\components\GetAlerts;
use yii\grid\ActionColumn;

yii\widgets\Pjax::begin(['id' => 'counterGrid']);
echo GridView::widget(
        [
            
            'layout' => '<div class="row"><div class="col-sm-6 pager-row">{summary}</div><div class="col-sm-6"></div></div> {items} {pager}',
            'dataProvider' => $dataProvider,
            'filterModel' => $searchModel,
            'options' => [
                'cellspacing' => 0,
                'cellpadding' => 0,
                'class' => 'inner-table-events',
                'style' => 'overflow-x: auto;',
                'template' => '',
            ],
            'rowOptions' => function ($model, $index, $widget, $grid){
                return ['counter_id'=>$model->serial_number,'class'=>'counter'];
              },
            'columns' =>
            [
                [   
                    'attribute' =>'modem_id',
                    'filter' => true,
                    ],
                
                
                 [
                    'attribute' => 'serial_number',
                    'format' => 'html',
                    'value' => function ($model) {
                            
                            return '<b>'.$model->serial_number.'</b>';
                      
                    },
                ],
                
                
                [
                    //'header' => 'Адрес',
                    'attribute' => 'fulladdress',
                    'format' => 'html',
                    'value' => function ($model) {
                            if(isset($model->address->street)){
                            return $model->address->fulladdress;}
                      
                    },
                    
                ],
                [
                    'attribute' => 'update_interval',
                    'format' => 'html',
                    'value' => function ($model) {

                        return '<b>'.$model->rmodule->update_interval.'</b>';

                    },
                ],
                            
                                           [
                        'class' => ActionColumn::className(),
                        'header' => '-',
                        'options' =>
                        [
                            'class' => 'button-column',
                            'width' => '30px',
                        ], 
                        'template' => '{login}&#160;{edit}',
                        'buttons' => [
                            'edit' => function($url, $model) {
                                $url = Yii::$app->urlManager->createUrl(["admin/counter/editmodem", 'serial_number' => $model->modem_id, "&backUrl=admin/counters", ((isset($_GET["page"])) ? "?page=" . $_GET["page"] : "")]);
                                $label = Yii::t('counter','modem_edit');
                                return \yii\helpers\Html::a('<i class="fa fa-edit"></i>', $url, ['title' => Yii::t('yii', $label), 'data-pjax' => '0']);
                            },
                                  
                                ]
                        ]
                    
     
                ]]
        );

\yii\widgets\Pjax::end();
        