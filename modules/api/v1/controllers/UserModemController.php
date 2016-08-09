<?php
/**
 * Date: 7/16/15
 * Time: 2:06 PM
 */

namespace app\modules\api\v1\controllers;


use yii\rest\Controller;

class UserModemController extends Controller
{
    public $modelClass = 'app\modules\api\v1\models\UserModem';

    public function actions()
    {
        $actions = parent::actions();

        $actions['index'] = [
            'class'       => 'app\modules\api\v1\actions\SearchAction',
            'modelClass'  => $this->modelClass,
        ];

        $actions['view'] = [
            'class' => 'yii\rest\ViewAction',
            'modelClass'  => $this->modelClass,
        ];

        return $actions;
    }
}