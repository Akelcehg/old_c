<?php
namespace app\modules\prom\components;
use app\models\DateOptions;
use Yii;
use yii\data\ArrayDataProvider;
use yii\grid\ActionColumn;
use yii\grid\GridView;
use yii\helpers\Html;

class ConnectWidget extends \yii\base\Widget
{
    /**
     * CorrectorToCounter.id
     * @var int
     */
    public $id;



    public function run() {

        if(file_exists('../serverlog/connectLog.txt')){
            $string=file_get_contents('../serverlog/connectLog.txt');
        }else{
            $string='no file';
        }

        $stringArr=explode(";",$string);
        $stringElemArr=[];


        foreach($stringArr as $oneStringArr){
            $buf=explode(" ",$oneStringArr);



            if(isset($buf[1]) and isset($buf[6]) and ($buf[6]==$this->id)){
                $dt=new \DateTime($buf[1]." ".$buf[2]);
                if(isset($buf2)){
                    $dn=new \DateTime($buf2[1]." ".$buf2[2]);
                }else{
                    $dn= clone $dt;
                }
                $time=$dt->diff($dn);

                if($time->i>1) {

                    $stringElemArr[] = [
                        'date' => $buf[1],
                        'time' => $buf[2],
                        'modem№' => $buf[6],
                        'sleeptime' => $time->format("%H:%I:%S")
                    ];
                }
                $buf2=explode(" ",$oneStringArr);
            }
        }

        $dataProvider= new ArrayDataProvider([
            'allModels' =>  $stringElemArr,
            'pagination' => [
                'pageSize' => 100,
            ],
            'sort' => [
                'attributes' => ['sleeptime'],
            ],
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
                'rowOptions' => function ($model, $index, $widget, $grid) {

                    $timeArr=explode(":",$model['sleeptime']);

                    $di=new \DateInterval("PT".$timeArr[0]."H".$timeArr[1]."M".$timeArr[2]."S");



                    if($di->i>20){

                        return ['style' => 'background-color:yellow'];

                    }

                    if($di->h>1){

                        return ['style' => 'background-color:red'];

                    }

                },
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
                            'attribute'=>'date',
                            'header'=>Yii::t('promWidgets','Date')
                        ],
                        [
                            'attribute'=>'time',
                            'header'=>Yii::t('promWidgets','Time')
                        ],
                        [
                            'attribute'=>'modem№',
                            'header'=>Yii::t('promWidgets','Modem number')
                        ],
                        [
                            'attribute'=>'sleeptime',
                            'header'=>Yii::t('promWidgets','Delay')
                        ],

                ],

            ]
        );





    }
}