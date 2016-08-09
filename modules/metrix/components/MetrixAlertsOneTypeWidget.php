<?php

namespace app\modules\metrix\components;
use app\components\Alerts\AlertNotification;
use app\models\AlertsList;
use app\modules\metrix\models\MetrixAlert;
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
class MetrixAlertsOneTypeWidget extends \yii\base\Widget
{
    public $id=null;
    public $type=null;
    public $status=null;

    public function run()
    {
        $this->renderWidget();
    }

    public function renderWidget(){

        $alertstype=MetrixAlert::find()
            //->andFilterWhere(['status'=>$this->status])
            ->andFilterWhere(['in','type',$this->type])
            ->andFilterWhere(['counter_id'=>$this->id]);

        $dataProvider = new \yii\data\ActiveDataProvider([
            'query' => $alertstype,
        ]);

        Pjax::begin([]);
        echo GridView::widget(
            [
                'id' => 'browse-alerts-grid-'.$this->id,
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
                    'metrix_id',
                    [
                        'attribute'=>'type',
                        'value'=>function($model)
                        {
                            return $model->type;
                        }
                    ],

                    'created_at',

                ],

            ]
        );
        Pjax::end();
    }

}