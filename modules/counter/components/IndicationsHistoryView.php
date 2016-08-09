<?php
namespace app\modules\counter\components;
use app\models\AlertsList;
use app\models\IndicationSearch;
use Yii;
use yii\grid\ActionColumn;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\widgets\Pjax;

class IndicationsHistoryView extends \yii\base\Widget
{

    public $id;

    public function run() {

        $searchModel = new IndicationSearch();

        $searchModel->counter_id = $this->id;

        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);


  Pjax::begin();
        echo GridView::widget(
            [
                'id' => 'browse-indications-grid',
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

                    'id',
                    'counter_id',
                    'indications',
                    [
                        'attribute'   => 'impulse',
                        'filter' => false,
                        'value'=>function($model){
                            $impulse = $model->impulse;

                            return ($impulse) ? $impulse->impulse : 0;
                        }
                    ],

                    [
                        'attribute'   => 'created_at',
                        'filter' => false
                    ],

                ],

            ]
        );

  Pjax::end();
    }
}