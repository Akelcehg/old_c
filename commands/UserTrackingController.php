<?php

namespace app\commands;

use app\components\Balance;
use app\components\Events\UserTracking;
use Yii;
use yii\console\Controller;
use yii\db\Query;

class UserTrackingController extends Controller
{
    public function actionCheck()
    {
        date_default_timezone_set("Europe/Kiev");
        $userTracking=new UserTracking();
        $userTracking->GetLogout();

    }

}