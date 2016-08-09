<?php
/**
 * Date: 7/16/15
 * Time: 2:01 PM
 */

namespace app\modules\api\v1\models;


use app\models\UserModems;
use yii\helpers\Url;
use yii\web\Link;
use yii\web\Linkable;

class UserModem extends UserModems implements Linkable
{
    public function extraFields()
    {
        return ['counters', 'address'];
    }

    public function getLinks()
    {
        return [
            Link::REL_SELF => Url::to(['user-modem/view', 'id' => $this->serial_number], true),
        ];
    }
}