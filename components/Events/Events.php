<?php
namespace app\components\Events;

/**
 * Created by PhpStorm.
 * User: alexey
 * Date: 13.01.16
 * Time: 15:15
 */
class Events extends \yii\base\Component
{
    public $oldAttributes;
    public $newAttributes;
    public $type;
    public $region_id;
    public $counter_type;
    /**
     * @var \yii\base\Model
     */
    public $model=false;
    /**
     * @var EventHandler;
     */
    public $eventHandler;

    protected $event;

    public function __construct()
    {
        parent::__construct();
        $this->event=new EventFactory();
    }

    public function AddEvent()
    {
        $this->eventHandler=$this->event->createEvent($this->model->className());
        $this->eventHandler->model=$this->model;
        $this->eventHandler->newAttributes=$this->newAttributes;
        $this->eventHandler->oldAttributes=$this->oldAttributes;
        $this->eventHandler->type=$this->type;
        $this->eventHandler->Handle();
    }




}