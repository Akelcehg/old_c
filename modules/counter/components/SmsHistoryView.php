<?php
namespace app\modules\counter\components;
use app\models\AlertsList;
use app\models\BalanceHistory;
use app\models\SmsHistory;
use yii\grid\GridView;
use yii\widgets\Pjax;


class SmsHistoryView extends \yii\base\Widget
{

    public $serial_number;



    public function run() {
        $query = SmsHistory::find()->where(['modem_id' => $this->serial_number]);

        $dataProvider = new \yii\data\ActiveDataProvider([
            'query' => $query,
            "pagination"=>false,
            'sort' => new \yii\data\Sort([
                'attributes' => [
                    'date',

                ],

                'defaultOrder' => [
                    'date'  => SORT_DESC,

                ]

            ]),
        ]);


        Pjax::begin();
        echo GridView::widget(
            [
                'id' => 'browse-balanceHistory-grid',
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

                    'sms',
                    'date'

                ],

            ]
        );

        Pjax::end();
    }
}