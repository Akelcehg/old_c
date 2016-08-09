<?php
/**
 * Date: 7/15/15
 * Time: 7:18 AM
 */
namespace app\modules\api\v1\models;

use app\models\UserCounters;
use yii\helpers\Url;
use yii\web\Link;
use yii\web\Linkable;

class UserCounter extends UserCounters implements Linkable
{
    public function extraFields() {
        return ['user', 'modem', 'address','model'];
    }

    public function getLinks()
    {
        return [
            Link::REL_SELF => Url::to(['user-counter/view', 'id' => $this->serial_number], true),
        ];
    }
}