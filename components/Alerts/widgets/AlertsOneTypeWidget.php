<?php

namespace app\components\Alerts\widgets;
use app\components\Alerts\AlertNotification;
use app\models\AlertsList;
use app\models\Modem;
use app\models\ModemStatus;
use Yii;
use yii\bootstrap\Html;
use yii\bootstrap\Tabs;
use yii\grid\ActionColumn;
use yii\grid\GridView;
use yii\widgets\Pjax;

/**
 * Created by PhpStorm.
 * User: alexey
 * Date: 23.03.16
 * Time: 9:58
 */
class AlertsOneTypeWidget extends \yii\base\Widget
{
    public $serial_number=null;
    public $type=null;
    public $mode=['modem','prom'];
    public $status=null;

    public function run()
    {
        $this->renderWidget();
    }

    public function renderWidget(){

        $alertstype=AlertsList::find()
            ->where(['device_type'=>$this->mode,])
            ->andFilterWhere(['status'=>$this->status])
            ->andFilterWhere(['in','type',$this->type])
            ->andFilterWhere(['serial_number'=>$this->serial_number]);

        $dataProvider = new \yii\data\ActiveDataProvider([
            'query' => $alertstype,
        ]);

        Pjax::begin([]);
        echo GridView::widget(
            [
                'id' => 'browse-alerts-grid-'.$this->serial_number,
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
                        'firstPageCssClass' => 'show',
                        'lastPageCssClass' => 'show',
                        'prevPageLabel' => 'Previous',
                        'nextPageLabel' => 'Next',
                        'template' => "{summary}<div id='browse-users-grid_container' class='dt-wrapper'>{items}<div class='row'><div class='col-sm-sm-6 text-right pull-right'>{pager}</div></div></div>",
                        'selectableRows' => 0,
                        'data-pjax'=>'1'
                    ],
                ],
                'columns' => [

                    'id',
                    'serial_number',
                     [
                        'attribute'=>'address',
                         'format'=>'raw',
                        'value'=>function($model)
                        {
                            switch($model->item->classname()){
                                case ModemStatus::className():
                                    if(!empty($model->item->corrector)){
                                        $link=Html::a($model->item->address->fullAddress,Yii::$app->urlManager->createUrl(['/prom/correctors/view','id'=>$model->item->corrector->id]),['data-pjax'=>"0"]);
                                    }else{
                                        $link='N\A';

                                    }

                                    return $link;
                                    break;
                                case Modem::className():
                                    if(isset($model->item->address->fullAddress) and isset($model->item->counters) and isset($model->item->counters[0])) {
                                        return Html::a($model->item->address->fullAddress, Yii::$app->urlManager->createUrl(['/counter/counter/view', 'id' => $model->item->counters[0]->id]), ['data-pjax' => "0"]);
                                    }
                                    break;

                            }

                            /*if(isset($model->item->address->fullAddress) and isset($model->item->counters) and isset($model->item->counters[0])){
                                $text=Html::a($model->item->address->fullAddress,Yii::$app->urlManager->createUrl(['/counter/counter/view','id'=>$model->item->counters[0]->id]),['data-pjax'=>"0"]);
                            return $text;}else{

                                $text=Html::a($model->item->address->fullAddress,Yii::$app->urlManager->createUrl(['/prom/correctors/view','id'=>$model->item->all_id]),['data-pjax'=>"0"]);

                                return $text;
                            }*/
                        }
                    ],
                    [
                        'attribute'=>'type',
                        'value'=>function($model)
                        {
                            return $model->getAlertTypeText();
                        }
                    ],
                      [
                          'attribute'=>'cause',
                        'value'=>function($model)
                        {
                            return AlertNotification::generateWidgetNotification($model->id,$model->device_type);
                        }
                    ],
                    'created_at',

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
                                $url = Yii::$app->urlManager->createUrl([
                                    "counter/alerts/editalerts",
                                    'id' => $model->id,
                                    'backUrl'=>Yii::$app->request->absoluteUrl
                                ]);
                                $label = 'Edit Alerts';
                                return \yii\helpers\Html::a('<i class="fa fa-edit"></i>', $url, [
                                    'title' => Yii::t('yii', $label),
                                    'data-pjax' => '0',

                                ]);
                            }
                        ]
                    ]

                ],

            ]
        );
        Pjax::end();
    }

}