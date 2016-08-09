<?php

namespace app\commands;

use app\components\Balance;
use Yii;
use yii\console\Controller;
use yii\db\Query;

class BalanceController extends Controller
{
    public function actionInit()
    {
        $balanceComponent = new Balance();

        $q = new Query();

        $balanceIdArray = [];

        foreach ($q->select('*')
                     ->where('DATE(updated_at)=DATE_SUB(CURDATE(),INTERVAL 7 DAY)')
                     ->from('user_modems')
                     ->all() as $modem) {

            array_push($balanceIdArray, ['id' => $modem['id'], 'balance' => $balanceComponent->getBalance($modem['last_invoice_request'])]);
        }

        if (count($balanceIdArray) > 0)
        return $this->updateDbBalance($balanceIdArray);
        else return true;
    }

    public function updateDbBalance($balanceIdArray)
    {
        $caseString = '';
        for ($i = 0; $i < count($balanceIdArray); $i++) {
            $caseString .= ' when id = ' . $balanceIdArray[$i]['id'] . ' then "' . $balanceIdArray[$i]['balance'] . '" ';
        }

        $caseString .= ' end)';
        $r = Yii::$app->db->createCommand();
        $r->setSql('update user_modems set balans = ( case ' . $caseString);
        return $r->execute();

    }
}