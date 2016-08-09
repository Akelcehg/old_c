<?php

namespace app\modules\prom\controllers;

use app\modules\prom\components\ReportChecker\ReportCheckerComponent;
use Yii;
use yii\web\Controller;

class DefaultController extends Controller
{
    public function actionIndex()
    {
        $this->redirect('prom/correctors');
    }
}
