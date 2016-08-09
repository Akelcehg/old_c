<?php
namespace app\components\Events;
use yii\base\Model;
use app\models\EventLog;
use Yii;

/**
 * Created by PhpStorm.
 * User: alexey
 * Date: 13.01.16
 * Time: 16:12
 */
abstract class EventHandler
{
    public $oldAttributes;
    public $newAttributes;
    public $type;

    public $description;
    public $region_id;
    public $counter_type;
    /**
     * @var Model;
     */
    public $model=false;

    abstract function getDescription();
    abstract function init();

    public function AddEventNotification()
    {
        $this->description=Yii::t('events','Adding');//"Добавление ";
        $this->getDescription();
        $this->description.="<br/>";
        $this->description.=Yii::t('events','Values').':';//"Значения величин:";
        $this->description.="<br/>";

        $eventText= new EventsText($this);
        $this->description.=$eventText->getAttributesToText();


        $this->save();

    }

    public function EditEventNotification()
    {
        $this->description=Yii::t('events','Editing');//"Редактирование ";
        $this->getDescription();
        $this->description.="<br/>";
        $this->description.=Yii::t('events','Changes').':';//"Изменения: ";
        $this->description.="<br/>";

        $eventText= new EventsText($this);
        $this->description.=$eventText->getAttributesToText();


        $this->save();

    }

    public function Handle()
    {
        $this->init();

        switch($this->type){
            case 'add':
                $this->AddEventNotification();
                break;
            case 'edit':
                $this->EditEventNotification();
                break;
        }

    }

    protected function save(){

    $events = new EventLog();
        if($this->model->className()==\app\models\User::className()){
            $events->user_id=$this->model->id;
        }else{
            $events->user_id = Yii::$app->user->id;
        }


    $events->url = Yii::$app->urlManager->hostInfo;
    $events->type = $this->type;
    $events->region_id = $this->region_id;
    $events->counter_type = $this->counter_type;
    $events->description = $this->description;
    $events->save();

    }







}