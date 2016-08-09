<?php
/**
 * User: Igor S <igor.skakovskiy@sferastudios.com>
 * Date: 2/3/16
 * Time: 10:36 AM
 */

namespace app\commands;


use app\models\UserCounters;
use app\models\UserModems;
use yii\console\Controller;
use yii\db\Expression;

class FakeIndicationUpdaterController extends Controller
{
    public function actionUpdateByModemId($modemId)
    {
        $userCounters = UserCounters::findAll([
            'user_modem_id' => $modemId
        ]);

        foreach ($userCounters as $userCounter) {
            $userCounter->updated_at = new Expression("NOW()");
            $userCounter->save(false);
        }
    }
}