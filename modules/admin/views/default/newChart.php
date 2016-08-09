<?php
/**
 * Created by PhpStorm.
 * User: alexey
 * Date: 02.03.16
 * Time: 12:08
 */

use yii\bootstrap\Tabs;

echo Tabs::widget([

            'items' => [
                [
                    'label' => 'Дневной',
                    'content' => 'Дневной',

                ],
                [
                    'label' => 'Недельный',
                    'content' => 'Недельный',
                ],
                [
                    'label' => 'Месячный',
                    'content' => 'Месячный',
                ],

            ],

]);