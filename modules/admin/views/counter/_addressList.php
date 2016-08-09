<?php

use yii\helpers\Html;
use app\components\managePageSize\ManagePageSize;
use yii\grid\GridView;
use yii\grid\ActionColumn;
use yii\bootstrap\ActiveForm;
use yii\jui\DatePicker;
use app\models\Regions;
use yii\helpers\ArrayHelper;
use yii\widgets\Pjax;
use app\components\Chart;
use app\components\CounterMap;
use app\components\AllAlerts;
use app\models\CounterAddressSearch;
use app\components\CounterQuery;
use app\models\UserCounters;
?>
<div class="clientSearch ">
    <?php
    $searchedValue = (Yii::$app->request->get('search')) ? Yii::$app->request->get('search') : '';
    $selectedNativeLanguage = (Yii::$app->request->get('nativeLanguageFilter')) ? Yii::$app->request->get('nativeLanguageFilter') : '0';
    $selectedSpokenLanguage = (Yii::$app->request->get('spokenLanguageFilter')) ? Yii::$app->request->get('spokenLanguageFilter') : '0';

    //$languages = Language::find()->where(['order' => 'name'])->all();
    //$closePerm = !Yii::$app->authManager->checkAccess(Yii::$app->user->getId(),'moderator');
    ?>

    <div style="float:right;">
        <span id="count_users" class="count_users"></span>
    </div>
    <div class="clear"></div>
</div>

<?php
$form = ActiveForm::begin([ 'method' => 'GET',
            'options' => [
                'class' => 'smart-form',
                'data-pjax' => 1,
                'id' => 'CounterAddressSearch',
            ]
        ]);
echo $form->field($searchModel, 'id')->hiddenInput(['value' => 0, 'id' => 'address'])->label(false)->error(false);
?>
<div class="row">
    <section class="input col col-2">
        <div class="input-group">
            <span class="input-group-addon clickable"> <i class="fa fa-calendar"></i></span>
<?php
echo $form->field($searchModel, 'beginDate')->widget(DatePicker::classname(), [
    //'name' => 'CounterAddressSearch[beginDate]',


    'dateFormat' => 'yyyy-MM-dd',
    'clientOptions' => [
        'nextText' => '>',
        'prevText' => '<',
    ],
    'options' => [ 'id' => 'beginDate',
    ],
])->label(false)->error(false);
?>
        </div>
    </section>

    <section class="input col col-2">
        <div class="input-group">
            <span class="input-group-addon clickable"> <i class="fa fa-calendar"></i></span>
            <?php
            /* echo DatePicker::widget([
              'name' => 'CounterAddressSearch[endDate]',
              'dateFormat' => 'yyyy-MM-dd',
              'id'=>'endDate',
              'clientOptions' => [
              'nextText' => '>',
              'prevText' => '<',
              ],
              'value' => Yii::$app->request->get('CounterAddressSearch[endDate]', 0)? : date('Y-M-d')
              ]) */

            echo $form->field($searchModel, 'endDate')->widget(DatePicker::classname(), [
                //'name' => 'CounterAddressSearch[endDate]',

                'dateFormat' => 'yyyy-MM-dd',
                'clientOptions' => [
                    'nextText' => '>',
                    'prevText' => '<',
                ],
                'options' => [ 'id' => 'endDate',
                ],
            ])->label(false)->error(false);
            ?>
        </div>
    </section>

    <div class="col col-2">
        <section>
            <label class="select">
<?php
echo $form->field($searchModel, 'region')
        ->dropDownList(['Выберите Регион'] + ArrayHelper::map(Regions::find()->where('parent_id=0')->all(), 'id', 'name'), [
            'value' => Yii::$app->request->get('CounterAddressSearch[region]', 0),
            'id' => 'region'
        ])
        ->label(false)->error(false);

//echo Html::dropDownList('CounterAddressSearch[region]', Yii::$app->request->get('region', 0), ['Выберите Регион'] + ArrayHelper::map(Regions::find()->where('parent_id=0')->all(), 'id', 'name'), ['id' => 'region']); 
?>
                <i></i>
            </label>
        </section>
    </div>

    <div class="col col-2">
        <section>
            <label class="select">
<?php
echo $form->field($searchModel, 'region_id')
        ->dropDownList(['Выберите Регион'] + ArrayHelper::map(Regions::find()->where('parent_id!=0')->all(), 'id', 'name'), [
            'value' => Yii::$app->request->get('CounterAddressSearch[region_id]', 0),
            'id' => 'city'
        ])
        ->label(false)->error(false);

//echo Html::dropDownList('CounterAddressSearch[city]', Yii::$app->request->get('city', 0), ['Выберите город'] + ArrayHelper::map(Regions::find()->all(), 'id', 'name'), ['id' => 'city']); 
?>
                <i></i>
            </label>
        </section>
    </div>
<?php
//echo Html::dropDownList('house','',['Выберите дом'] ,['id'=>'house']);
?>
    <div class="col col-2">
    <?php
    echo Html::Button('Применить Фильтр', [
        'id' => 'filterSubmitGraph',
        'class' => 'btn-primary',
        'style' => 'padding: 6px 12px',
        'data-pjax' => true

            //
    ]);
    ?>
    </div>

    <div class="col col-2">
        <?php
        echo Html::Button('Сбросить Фильтр', [
            'id' => 'reset',
            'class' => 'btn btn-primary',
            'style' => 'padding: 6px 12px',
                //
        ]);
        ?>
    </div>

</div>



        <?php
        echo GridView::widget(
                [
                    'id' => 'browse-address-grid',
                    'dataProvider' => $dataProvider,
                    'filterModel' => $searchModel,
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
                    'rowOptions' => function ($model){
                                        return [
                                            'geo_location_id'=>$model->id,
                                            'class'=>'drillDown',
                                            'style'=>'cursor:pointer'
                                            ];
                                      },
                    'columns' =>
                    [


                        [
                            'header' => 'Город',
                            'format' => 'raw',
                            'options'=>[],
                            'value' => function ($model) {
                                return $model->region->name;
                            },
                        ],
                        [
                            'header' => 'Улица',
                            'attribute' => 'street',
                            'filter' => false,
                            'format' => 'raw',
                            'value' => function ($model) {
                                return $model->street;
                            },
                        ]
                        ,
                        [
                            'header' => 'Дом',
                            'attribute' => 'house',
                            'filter' => false,
                            'format' => 'raw',
                            'value' => function ($model) {
                                return $model->house;
                            },
                        ]
                      
                    ]
                ]
        );

        ?>

<script type="text/javascript">
    $(document.body).on("dblclick", "#browse-users-grid table tr", function () {
        id = $(this).find('td:first-child').text();
        url = "<?php echo Yii::$app->getUrlManager()->createUrl('admin/users/viewUser', array('backTo' => 'approvedUsers', 'id' => '')); ?>" + id;
        if (parseInt(id)) {
            location.href = url;
        }
    });
</script>
