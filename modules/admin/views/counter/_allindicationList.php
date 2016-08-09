<?php

use yii\grid\GridView;
use yii\grid\ActionColumn;
use yii\helpers\Html;
use yii\widgets\ActiveForm;




?>
  
    <div class="col col-2">
    <?php
    if(Yii::$app->request->get('counter_id',false)){
    echo Html::a( Html::submitButton('Добавить Коррекцию', ['style' => 'padding: 6px 12px']),
        Yii::$app->urlManager->createUrl(['/admin/correction/addcorrection','counter_id'=>Yii::$app->request->get('counter_id')]));}
    ?>
    </div>
<?php

echo GridView::widget(
        [
            'id' => $gridViewId,
            'layout' => '<div class="row"><div class="col-sm-6 pager-row">{summary}</div></div> {items} {pager}',
            'dataProvider' => $dataProvider,
            'filterModel' => $searchModel,
            'options' => [
                'cellspacing' => 0,
                'cellpadding' => 0,
                'class' => 'inner-table-events',
                'template' => '',
            ],
            'headerRowOptions' => ['class' => 'breakword',],
            'rowOptions' =>['class' => 'counter', 'style' => 'cursor: pointer'],
    
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

            ]
        ]
);


