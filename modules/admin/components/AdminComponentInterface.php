<?php
/**
 * Created by PhpStorm.
 * User: alexey
 * Date: 11.01.16
 * Time: 9:42
 */

namespace app\modules\admin\components;


interface AdminComponentInterface
{

    public function getModel();

    public function getSearchModel();

    public function getDataProvider();

}