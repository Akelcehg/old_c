<?php
/**
 * User: Igor S <igor.skakovskiy@sferastudios.com>
 * Date: 1/6/16
 * Time: 10:40 AM
 */

namespace app\components;


use yii\base\Object;

class RuntimeClientDetector extends Object
{
    const CLIENT_ASER = 'aser';

    public static function isAser()
    {
        return (strtolower(\Yii::$app->params['client']) == self::CLIENT_ASER);
    }
}