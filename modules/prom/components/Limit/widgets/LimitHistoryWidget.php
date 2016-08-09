<?php
/**
 * Created by PhpStorm.
 * User: alexey
 * Date: 07.07.16
 * Time: 10:32
 */

namespace app\modules\prom\components\Limit\widgets;


use app\modules\prom\components\Limit\Limits;
use Yii;
use yii\base\Widget;
use yii\grid\GridView;

class LimitHistoryWidget extends Widget
{

    public $all_id=null;

    public function run()
    {
       // Yii::$app->formatter->locale='ru';
        $limits =new  Limits();
        $limits->all_id=$this->all_id;
        $dp=$limits->GetLimitsInDataProvider();




        echo GridView::widget(
            [
                'id' => 'browse-limit-history-grid',
                'dataProvider' => $dp,

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
                        'template' => "{summary}<div id='browse-limit-history-grid_container' class='dt-wrapper'>{items}<div class='row'><div class='col-sm-sm-6 text-right pull-right'>{pager}</div></div></div>",
                        'selectableRows' => 0,
                    ],
                ],
                'columns' => [

                    'year',
                    [
                        'attribute'=>'month',
                        'filter' => false,

                        'format' => 'raw',
                        'value' => function ($dataElement) {

                            $dt=new \DateTime(date("Y-".$dataElement->month."-1"));

                            return Yii::$app->formatter->asDate($dt,"LLLL");
                        },
                    ],

                    [
                        'header'=>Yii::t('limit','Limit of gas consumption , m<sup>3</sup>'),
                        'attribute'=>'limit',
                        'format' => 'html'
                    ],
                ],

            ]
        );




    }

}