<?php
/**
 * Created by PhpStorm.
 * User: alexey
 * Date: 31.03.16
 * Time: 16:41
 */

namespace app\modules\prom\components\ReportChecker\widgets;


use app\components\Prom\PromReportParts;
use app\models\Diagnostic;
use app\models\PromReport;
use app\modules\prom\components\ReportChecker\ReportCheckerComponent;
use Yii;
use yii\base\Widget;
use yii\grid\ActionColumn;
use yii\grid\GridView;
use yii\helpers\Html;

class CorrectorDailyDataWidget extends Widget
{
    public $id;
    public $date=false;

   function run()
   {

       $reports=PromReport::find()
           ->where(['all_id'=>$this->id])
           ->andWhere(['is_valid'=>1])
           ->andWhere(['like','date',$this->date]);

       $dataProvider = new \yii\data\ActiveDataProvider([
           'query' =>  $reports,
           "pagination"=>false,
           'sort' => new \yii\data\Sort([
               'attributes' => [
                   'id',
                   'date',
               ],

               'defaultOrder' => [
                   'date'  => SORT_DESC,
               ]

           ]),
       ]);


       $this->renderWidget($dataProvider);

   }

    function renderWidget($dataProvider){
        echo GridView::widget(
            [
                'id' => 'browse-day-reports-grid',
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
                        'header' => 'Дата',
                        'filter' => false,
                        'format' => 'raw',
                        'options' =>
                            [
                                'class' => 'editable editable-click',

                            ],
                        'value' => function ($dataElement) {
                            $date = new \DateTime($dataElement['date']);
                            return $date->format("Y-m-d");
                        },
                    ],
                    [
                        'header' => 'Расход',
                        'filter' => false,
                        'format' => 'raw',
                        'options' =>
                            [
                                'class' => 'editable editable-click',
                                'width' => '60px',

                            ],
                        'value' => function ($dataElement) {
                            $dataElement->getDayData();
                            if(isset($dataElement->dayData)){
                                return  round($dataElement->dayData->v_sc,2)."м<sup>3</sup>";
                            }else{
                                return '0';
                            }

                        },
                    ],
                    [
                        'header' => 'Аварии',
                        'filter' => false,
                        'format' => 'raw',
                        'options' =>
                            [
                                'class' => 'editable editable-click',
                                'width' => '60px',

                            ],
                        'value' => function ($dataElement) {
                            if(isset($dataElement->dayData)){

                                switch($dataElement->dayData->emergency){
                                    case 'A':
                                        return Html::tag('i'," ",['class'=>'glyphicon glyphicon-warning-sign txt-color-red']);
                                        break;
                                    case 'B':
                                        return Html::tag('i'," ",['class'=>'glyphicon glyphicon-warning-sign  txt-color-orange']);
                                        break;
                                    case 'AB':
                                        return Html::tag('i'," ",['class'=>'glyphicon glyphicon-warning-sign txt-color-red']).Html::tag('i'," ",['class'=>'glyphicon glyphicon-warning-sign txt-color-orange']);
                                        break;
                                    default :return '';
                                }
                            }else{
                                return '';
                            }

                        },
                    ],
                    [
                        'class' => ActionColumn::className(),
                        'header' => 'Действие',
                        'options' =>
                            [
                                'class' => 'button-column editable editable-click',
                                'width' => '60px',
                            ],
                        'template' => '{download}',
                        'buttons' => [
                            'download' => function($url, $dataElement) {
                                $date = new \DateTime($dataElement['date']);
                                $url = \Yii::$app->urlManager->createUrl(["prom/report/getreport",
                                    'counterId' => $dataElement['all_id'],
                                    'date' => $date->format("Y-m-d"),
                                    'type'=>'day',
                                    'format'=>'pdf'
                                ]);
                                $label = 'суточный отчет';

                                return \yii\helpers\Html::a('<strong>Cкачать</strong>', $url, ['title' => \Yii::t('yii', $label),]);

                            },
                        ]
                    ]
                ],

            ]
        );


    }
}