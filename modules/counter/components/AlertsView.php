<?php
namespace app\modules\counter\components;
use app\models\AlertsList;
use yii\grid\GridView;
use yii\widgets\Pjax;


class AlertsView extends \yii\base\Widget
{

    public $id;
    public $status=null;


    public function run() {
        $query = AlertsList::find()
            ->where(['serial_number' => $this->id])
            ->andFilterWhere(['status'=>$this->status]);

        $dataProvider = new \yii\data\ActiveDataProvider([
            'query' => $query,
            "pagination"=>false,
            'sort' => new \yii\data\Sort([
                'attributes' => [
                    'created_at',

                ],

                'defaultOrder' => [
                    'created_at'  => SORT_DESC,

                ]

            ]),
        ]);

        Pjax::begin();

        echo GridView::widget(
            [
                'id' => 'browse-alerts-grid',
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
                'columns' => [

                    [
                        'attribute' => 'type',
                        'value' => function ($model) {
                            return $model->getAlertTypeText();
                        }
                    ],
                    'created_at'

                ],

            ]
        );
        Pjax::end();

    }
}