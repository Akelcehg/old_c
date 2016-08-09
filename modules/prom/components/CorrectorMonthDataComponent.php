<?php
namespace app\modules\prom\components;
use yii\grid\ActionColumn;
use yii\grid\GridView;
use yii\helpers\Html;

class CorrectorMonthDataComponent extends \yii\base\Widget
{
    /**
     * CorrectorToCounter.id
     * @var int
     */
    public $id;
    public $year;
    public $customLinkData = [];

    public function run() {

        $dt=new \DateTime();
        $query = (new \yii\db\Query())

            ->select([
                'all_id',
                'month',
                'day',
                'year'
            ])
            ->from(\app\models\DayData::tableName())
            ->where([
                'all_id' => $this->id,
                'year'=>$this->year,
            ])
           // ->andWhere(['<','month',$dt->format("m")])
            ->groupBy("month");

        $dataProvider = new \yii\data\ActiveDataProvider([
            'query' => $query,
            "pagination"=>false,
            'sort' => new \yii\data\Sort([
                'attributes' => [
                    'id',
                    'year',
                    'month',
                    'day',
                ],

                'defaultOrder' => [
                    'year'  => SORT_DESC,
                    'month' => SORT_DESC,
                    'day'  => SORT_DESC,
                ]

            ]),
        ]);

        echo '<div id="monthReports" class="tab-pane active">';


        echo GridView::widget(
            [
                'id' => 'browse-month-reports-grid',
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
                        'value' => function ($dataElement) {
                            $date = new \DateTime($dataElement['year']."-".$dataElement['month']."-".$dataElement['day']);
                            return $this->getMonth($date->format("n"));
                        },
                    ],
                    [
                        'class' => ActionColumn::className(),
                        'header' => 'Действие',
                        'options' =>
                            [
                                'class' => 'button-column',
                                'width' => '60px',
                            ],
                        'template' => '{download}',
                        'buttons' => [
                            'download' => function($url, $dataElement) {
                                $date = new \DateTime($dataElement['year']."-".$dataElement['month']."-".$dataElement['day']);
                                $url = \Yii::$app->urlManager->createUrl(["prom/report/getreport",
                                    'counterId' => $dataElement['all_id'],
                                    'date' => $date->format("Y-m-1"),
                                    'type'=>'month',
                                    'format'=>'pdf'
                                ]);
                                $label = 'суточный отчет';
                                return \yii\helpers\Html::a("<strong>Скачать</strong>", $url, ['title' => \Yii::t('yii', $label),]);
                            },
                        ]
                    ]
                ],

            ]
        );
        echo '</div>';





    }

      function getMonth($a){
        switch($a){
            case 1 :return "Январь";break;
            case 2 :return "Февраль";break;
            case 3 :return "Март";break;
            case 4 :return "Апрель";break;
            case 5 :return "Май";break;
            case 6 :return "Июнь";break;
            case 7 :return "Июль";break;
            case 8 :return "Август";break;
            case 9 :return "Сентябрь";break;
            case 10 :return "Октябрь";break;
            case 11 :return "Ноябрь";break;
            case 12 :return "Декабрь";break;
        }
    }
}