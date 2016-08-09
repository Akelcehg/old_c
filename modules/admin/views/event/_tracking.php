<?php
/**
 * Created by PhpStorm.
 * User: alexey
 * Date: 08.07.16
 * Time: 16:06
 */

use yii\grid\GridView;
use yii\helpers\Html;
use app\models\EventLog;

echo GridView::widget(
    [
        'id' => 'browse-tracking-grid',
        'dataProvider' => $dataProvider,
        'filterModel'=>$searchModel,
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
        'columns' =>
            [

                'id',
                [
                    //'header' => 'Ф.И.О.',
                    'attribute'=>'user_id',
                    'format' => 'html',
                    'contentOptions' => ['class' => " breakword",],
                    'value' => function ($model) {
                        if (!is_null($model->user)) {
                            return $model->user->fullname;
                        } else {
                            return '';
                        }
                    },
                ],
                [
                    //'header'=> 'Действие',
                    'attribute'=>'user_action',
                    'format' => 'html',
                    'value'=>  function($model){

                        return Html::a($model->user_action,$model->url);

                    },

                ] ,
                [
                    //'header'=> 'Длительность',
                    'attribute'=>'delay',
                    'value'=>  function($model){
                            $dt=new \DateTime($model->time_out);
                            $dn=new \DateTime($model->time_in);
                            $di=$dt->diff($dn);
                        if($model->time_out=='0000-00-00 00:00:00')
                        {
                            return 'находится на странице';
                        }
                        else{
                            return $di->format('%H:%I:%S');
                        }


                    },

                ] ,

                'created_at'
            ]
    ]
);