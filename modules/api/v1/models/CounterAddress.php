<?php
/**
 * Created by PhpStorm.
 * User: akel
 * Date: 7/20/15
 * Time: 2:43 PM
 */

namespace app\modules\api\v1\models;


class CounterAddress extends \app\models\CounterAddress
{
    public function extraFields()
    {
        return ['region'];
    }

    /*public function extraFields() {
        return ['user', 'modem', 'address'];
    }*/

    /*public function getLinks()
    {
        return [
            Link::REL_SELF => Url::to(['user-counter/view', 'id' => $this->serial_number], true),
        ];
    }*/

}