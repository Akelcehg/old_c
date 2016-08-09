<?php

namespace app\modules\counter\controllers;

use Yii;
use yii\web\Controller;

class DefaultController extends Controller
{
    public function actionIndex()
    {
        $this->redirect(Yii::$app->urlManager->createUrl('counter/search'));
    }
}
