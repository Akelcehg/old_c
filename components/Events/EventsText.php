<?php
/**
 * Created by PhpStorm.
 * User: alexey
 * Date: 15.01.16
 * Time: 9:33
 */

namespace app\components\Events;


use Yii;
use yii\base\Component;
use yii\base\Model;

class EventsText extends Component
{
    /* array [
     *       'value'=>handler(),
     *       'value2'=>handler2(), ...
     */
    public $valueHandlerArray=[];
    public $type;
    /**
     * @var Model
     */
    public $model=false;
    public $oldAttributes;
    public $newAttributes;

    public function __construct($event)
    {
        $this->type=$event->type;
        $this->model=$event->model;
        $this->newAttributes=$event->newAttributes;
        $this->oldAttributes=$event->oldAttributes;
        $this->valueHandlerArray=$event->valueHandlerArray;
    }

    public function getAttributesToText ()
    {
        switch($this->type)
        {
            case 'add':
               return $this->Add();
                break;
            case 'edit':
               return $this->Edit();
                break;
        }
    }

    public function getLabel($key){

         if($this->model){
             if(isset($this->model->attributeLabels()[$key])){
                 return $this->model->attributeLabels()[$key];
             }
         }
         return $key;


    }

    public function getText($key,$value){

        if($this->valueHandlerArray){
            if(array_key_exists($key,$this->valueHandlerArray)){
                if(isset($this->valueHandlerArray[$key][$value])){
                    return $this->valueHandlerArray[$key][$value];
                }else
                {
                    return 'Не задано';
                }
            }
        }
        return $value;

    }

    public function Add(){

        $output='';

        foreach ($this->newAttributes as $key=>$value)
        {
            $label = $this->getLabel($key);
            $text =$this->getText($key,$value);

            $output.=$label .' - '.$text. '<br/>';
        }

        return $output;

    }

    public function Edit(){

        $output='';

        foreach ($this->newAttributes as $key=>$value)
        {
            if($value != $this->oldAttributes[$key]){

                $label = $this->getLabel($key);
                $text =$this->getText($key,$value);
                $text2 =$this->getText($key,$this->oldAttributes[$key]);

                $output.=$label .
                    Yii::t('events',': was- {text2} became- {text}',
                        [
                            'text2' =>$text2 ,
                            'text'=>$text
                        ]).'<br/>';

            }

        }

        return $output;

    }


}