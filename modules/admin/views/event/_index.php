<?php

use yii\grid\GridView;
use yii\helpers\Html;
use app\models\EventLog;

echo GridView::widget(
        [
            'id' => 'browsec-correction-grid',
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
                    'attribute'=> 'user_id',
                    'format' => 'html',
                    'contentOptions' => ['class' => " breakword",],
                    'value' => function ($model) {
                if (!is_null($model->user)) {
                    return Html::a($model->user->fullname,Yii::$app->urlManager->createUrl(['/admin/event/tracking','id'=>$model->user_id]));
                } else {
                    return '';
                }
            },
                ],
                [
                    'attribute'=> 'type',
                    'value'=>  function($model){
                
                        return $model->getEventTypeText();
                
                    },
                    'filter'=>  Html::dropDownList("EventLogSearch[type]",
                                                    Yii::$app->request->get('EventLogSearch')['description'],
                                                    [null=>' без типа ']+EventLog::getEventTypeList(),
                                                    ['class'=>"form-control"]),
                ] ,   
               
                
                [
                    'attribute'=>'description',
                    'format' => 'raw',
                    'filter'=>  Html::dropDownList("EventLogSearch[description]",Yii::$app->request->get('EventLogSearch')['description'],[
                                                                ''=>' без типа ',
                                                                'Редактирование Модели Cчетчика'=>'Редактирование Модели Cчетчика',
                                                                'Редактирование Предупреждения'=>'Редактирование Предупреждения',
                                                                'Редактирование Счетчика'=>'Редактирование Счетчика',
                                                                'Приём Предупреждения'=>'Приём Предупреждения',
                                                                'Редактирование Предупреждения'=>'Редактирование Предупреждения',
                                                                'Редактирование Модема'=>'Редактирование Модема',
                                                                'Редактирование Меню'=>'Редактирование Меню',
                                                                'Редактирование Юзера'=>'Редактирование Юзера'
                                                                ],['class'=>"form-control"]).'<br/>'.
                                                                Html::textInput("EventLogSearch[descriptionText]",Yii::$app->request->get('EventLogSearch')['descriptionText'],['class'=>"form-control"]) ,
                                
                    ],
                'created_at'
            ]
        ]
);



