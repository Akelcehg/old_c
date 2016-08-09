<?php
/**
 * Created by PhpStorm.
 * User: alexey
 * Date: 22.01.16
 * Time: 17:11
 */

namespace app\components;



use yii\helpers\Html;

use Yii;


class Buttons
{

    public static function getButton($model,$columns)
    {
        $array = [];


                foreach ($columns as $column) {
                        if (!$model->hasErrors($column['attributeValue'])) {
                            $array[] = Html::a('<i class="' . $column['icon'] . '"></i>' . $column['label'],
                                Yii::$app->urlManager->createUrl([
                                    $column['url'],
                                    $column['attribute'] => $model->$column['attributeValue']]), array(
                                    'class' => $column['class'],
                                    'style' => $column['style'],
                                ));
                        }
                }
        return $array;

    }





    }