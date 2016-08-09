<?php
namespace app\modules\prom\components;
use app\models\DateOptions;
use yii\grid\ActionColumn;
use yii\grid\GridView;
use yii\helpers\Html;

class CorrectorDailyDataComponent extends \yii\base\Widget
{
    /**
     * CorrectorToCounter.id
     * @var int
     */
    public $id;
    public $year;
    public $month;
    public $contractHour=9;
    public $customLinkData = [];


    public function run() {

        $dt=new \DateTime();
        $dt->sub(new \DateInterval("P1D"));

        $dateOptions=DateOptions::find()->where(['all_id'=>$this->id])->orderBy(['created_at'=>SORT_DESC])->one();
        $this->contractHour=$dateOptions->contract_hour;
        $query = (new \yii\db\Query())
            ->distinct()
            ->select([
                'all_id',
                'month',
                'day',
                'year'
            ])
            ->from(\app\models\HourData::tableName())
            ->where([
                'all_id' => $this->id,
                'hour_n' => $dateOptions->contract_hour,
                'year'=>$this->year,
                'month'=>$this->month,
            ])
            ->andWhere(['<','timestamp',$dt->format("Y-m-d")." ".($dateOptions->contract_hour+1).":00:00"])
            ->groupBy('timestamp');

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
                        'value' => function ($dataElement) {
                            $date = new \DateTime($dataElement['year']."-".$dataElement['month']."-".$dataElement['day']);
                            return $date->format("Y-m-d");
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