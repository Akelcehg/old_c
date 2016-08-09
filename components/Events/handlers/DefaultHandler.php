<?php
namespace app\components\Events\handlers;
use app\components\Events\EventHandler;

/**
 * Created by PhpStorm.
 * User: alexey
 * Date: 14.01.16
 * Time: 14:37
 */
class DefaultHandler extends EventHandler
{
    public $valueHandlerArray=[];



    public function init()
    {
        // TODO: Implement init() method.
    }

    public function GetRegionAndType()
    {
        if(isset($this->newAttributes['region_id'])){
            $this->region_id=$this->newAttributes['region_id'];
        }

        if(isset($this->newAttributes['type'])){
            $this->counter_type=$this->newAttributes['type'];
        }
    }

    public function getDescription()
    {
        // TODO: Implement getDescription() method.
        $this->description=$this->model->className();

        $this->GetRegionAndType();
    }

}