<?php
/**
 * Created by PhpStorm.
 * User: alexey
 * Date: 02.03.16
 * Time: 12:10
 */




echo yii\widgets\DetailView::widget(
    [
        'options'=>['class'=>'table-striped table-hover table-bordered','id'=>'momentDataTable'],
        'model'=>$counter->corrector,
        'attributes' => [
            'id',
            'modem_id',
            'lastTrafficInSum',
            'lastTrafficOutSum',
            'dayTrafficInSum',
            'dayTrafficOutSum',
            'monthTrafficInSum',
            'monthTrafficOutSum',
            'cycle',
        ],
    ]);
