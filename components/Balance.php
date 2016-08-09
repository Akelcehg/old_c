<?php

namespace app\components;

use yii\base\Component;
use app\models\UserModems;

class Balance extends Component
{

    public function getBalance($data)
    {
        preg_match_all('!\d+(?:\.\d+)?!', $data, $result);

        if ($result[0]) {
            return $result[0][0];
        }

        return null;
    }

}