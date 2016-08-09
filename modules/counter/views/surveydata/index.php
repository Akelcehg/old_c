<?php

use app\models\Address;
use yii\grid\ActionColumn;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $searchModel app\models\SearchDocuments */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Акты';
$this->params['breadcrumbs'][] = $this->title;
?>
<div id="content">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Создать Акт', ['create'], ['class' => 'btn btn-success']) ?>
    </p>


    <div class="documents-search">

        <?php $form = ActiveForm::begin([
            'action' => ['index'],
            'method' => 'get',
        ]); ?>
        <div class="row">
            <?php if (\app\models\User::is('admin')) { ?>
                <div class="col col-md-2">
                    <?php echo $form->field($searchModel, 'address_id')->dropDownList(['' => 'Выберите адресс'] + ArrayHelper::map(Address::find()->all(), 'id', 'fulladdress'), ['id' => 'select3']); ?>

                </div>
            <?php } ?>
            <div class="col col-md-2">

                <?php
                /*echo $form->field($searchModel, 'device_type')->dropDownList(
                    ['' => 'Выберите статус', 'Новый' => 'Новый', 'Активный' => 'Активный', 'В работе' => 'В работе', 'Завершен' => 'Завершен']
                ); */
                ?>

                <?php echo $form->field($searchModel, 'device_type')->dropDownList(
                    ['' => 'Выберите прибора учета газа','counter' =>  'Cчетчик','corrector' => 'Корректор' ]); ?>

            </div>
            <div class="col col-md-2">
                <?php echo $form->field($searchModel, 'install_place')->dropDownList(
                    ['' => 'Выберите место установки',   'inside'=>'Внутри','outside'  => 'Снаружи']
                ); ?>
            </div>
        </div>


        <div class="row">
            <div class="col col-md-4">
                <div class="form-group">
                    <?= Html::submitButton('Применить фильтр', ['class' => 'btn btn-primary']) ?>
                </div>
            </div>
        </div>


        <?php ActiveForm::end(); ?>

    </div>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'summary' => '',
        'columns' => [
            'id',
            [
                'label' => 'Адрес',
                'format' => 'raw',
                'value' => function ($model) {
                    //return $data['street'].' '.$data['house'];

                    return $model->fulladdress;

                },
            ],

            [
                'attribute' =>  'device_type',
                'format' => 'raw',
                'value' => function ($model) {
                    switch($model->device_type){
                        case 'counter':return 'Счетчик';
                        case 'corrector':return'Корректор';
                    };
                },
            ],
            'created_at',
              [
                'class' => ActionColumn::className(),
                'header' => '-',
                'options' =>
                    [
                        'class' => 'button-column',
                        'width' => '60px',
                    ],
                'template' => '{login}&#160;{edit}',
                'buttons' => [
                    'edit' => function ($url, $model) {
                        $url = Yii::$app->urlManager->createUrl(["counter/surveydata/update", 'id' => $model->id]);
                        $label = 'редактирование Акта';
                        return \yii\helpers\Html::a('<i class="fa fa-edit"></i>', $url, ['title' => Yii::t('yii', $label), 'data-pjax' => '0']);
                    },
                ]
            ],

        ],
    ]); ?>

</div>
