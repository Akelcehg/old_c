<?php
/**
 * Created by PhpStorm.
 * User: alexey
 * Date: 25.02.16
 * Time: 13:12
 */
use yii\widgets\DetailView;

/* @var $modemStatus \app\models\ModemStatus */

if (isset($modemStatus)) {

    echo DetailView::widget([
        'options'=>[ 'class'=>'table-striped table-hover table-bordered', 'id'=>'gsmDataTable'],
        'model' => $modemStatus,
        'attributes' => [
            'phone',
            'balance',
            'modemStatusText',
            'signal_level',
            'time_on_line',
        ],
    ]);
} ?>