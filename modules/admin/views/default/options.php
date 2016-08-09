<?php
/**
 * Created by PhpStorm.
 * User: alexey
 * Date: 02.03.16
 * Time: 12:10
 */

use yii\bootstrap\Tabs;

echo Tabs::widget([

    'items' => [
        [
            'label' => 'Календарь',
            'content' => 'Календарь',

        ],
        [
            'label' => 'История показаний',
            'content' => 'История показаний',
        ],
        [
            'label' => 'Лог предупреждений',
            'content' => 'Лог предупреждений',
        ],
    ],

]);