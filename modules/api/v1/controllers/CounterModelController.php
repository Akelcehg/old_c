<?php

namespace app\modules\api\v1\controllers;

use yii\rest\Controller;

class CounterModelController extends Controller
{

    public $modelClass = 'app\modules\api\v1\models\CounterModel';

    public function actions()
    {
        $actions = parent::actions();

        $actions['index'] = [
            'class'       => 'app\modules\api\v1\actions\SearchAction',
            'modelClass'  => $this->modelClass,
        ];

        return $actions;
    }

}