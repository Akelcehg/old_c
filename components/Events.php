<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Alerts
 *
 * @author alks
 */

namespace app\components;

use yii\base\Component;
use Yii;

use app\models\EventLog;
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use app\models\CounterModel;


class Events extends \app\components\Events\Events
{

   /* public $oldAttributes;
    public $newAttributes;
    public $type;
    public $region_id;
    public $counter_type;
    public $model;
    public $description;
    public $ip;
    private $attributeLocalisationList=[];

    private function UserCountersHandler()
    {

        //$events->description = ' №' . $this->userCounter->serial_number;

        $this->attributeLocalisationList = [
            'type' => ['NA' => 'Не задано'] + $this->model->getCounterTypeList(),
            'user_type' => ['NA' => 'Не задано'] + $this->model->getUserTypeList(),
            'counter_model' => ['NA' => 'Не задано'] + ArrayHelper::map(CounterModel::find()->all(), 'id', 'name'),
            'geo_location_id' => $this->getAddressList()
        ];

        $this->description = "Редактирование Счетчика" .
            Html::a('№' . $this->model->id, Yii::$app->urlManager->createUrl(['/admin/counter/editcounter', 'id' => $this->model->id,])) .
            "( модем " . Html::a('№' . $this->model->modem_id, Yii::$app->urlManager->createUrl(["admin/counter/editmodem", 'serial_number' => $this->model->modem_id])) .
            ", адресс " . Html::a(isset($this->model->address) ? $this->model->address->fulladdress : 'N/A', Yii::$app->urlManager->createUrl(["admin/counter/allcounterlist", 'geo_location_id' => $this->model->geo_location_id])) . ")";

        $this->counter_type=$this->model->type;

        if(isset($this->model->address)){
            $this->region_id = $this->model->address->region_id;
        }



    }

    private function RmoduleHandler()
    {

        //$events->description = ' №' . $this->userCounter->serial_number;

        $this->attributeLocalisationList = [
            'geo_location_id' => $this->getAddressList()
        ];

        $this->description = "Редактирование Радиомодуля" .
            Html::a('№' . $this->model->id, Yii::$app->urlManager->createUrl(['/admin/counter/editcounter', 'id' => $this->model->id,])) .
            "( модем " . Html::a('№' . $this->model->modem_id, Yii::$app->urlManager->createUrl(["admin/modem/editmodem", 'serial_number' => $this->model->modem_id])) .
            ", адресс " . Html::a(isset($this->model->address) ? $this->model->address->fulladdress : 'N/A', Yii::$app->urlManager->createUrl(["admin/counter/allcounterlist", 'geo_location_id' => $this->model->geo_location_id])) . ")";



        $this->region_id = $this->model->address->region_id;

    }

    //Yii::$app->urlManager->createAbsoluteUrl(['/admin/alertinput/editalerts','id'=>$alertId])


    private function getAddressList()
    {

        $address = ArrayHelper::map(\app\models\Address::find()->all(), 'id', 'fulladdress');
        $address['NA'] = 'Не задано';
        return $address;

    }

    private function getRegionList()
    {

        return ['NA' => 'Не задано'] + ArrayHelper::map(\app\models\Region::find()->all(), 'id', 'FullRegionName');

    }


    private function UserModemsHandler()
    {

        $this->attributeLocalisationList = [
            'type' => ['NA' => 'Не задано'] + $this->model->getModemTypeList(),
            'geo_location_id' => $this->getAddressList()
        ];


        $this->description = "Редактирование модема " .
            Html::a('№' . $this->model->serial_number, Yii::$app->urlManager->createUrl(['admin/counter/editmodem', 'serial_number' => $this->model->serial_number,])) .
            "( адресс " . Html::a(isset($this->model->address) ? $this->model->address->fulladdress : 'N/A', Yii::$app->urlManager->createUrl(["admin/counter/modemslist", 'geo_location_id' => $this->model->geo_location_id])) . ")";

        if(isset($this->model->counters[0])) {
            $this->counter_type = $this->model->counters[0]->type;
            if(isset($this->model->counters[0]->address)) {
                $this->region_id = $this->model->counters[0]->address->region_id;
            }
        }


    }

    private function AlertListHandler()
    {

        //$events->description = ' №' . $this->userCounter->serial_number;

        $this->attributeLocalisationList = [
            'type' => ['NA' => 'Не задано'] + $this->model->getAlertTypeList(),
            'status' => ['NA' => 'Не задано'] + $this->model->getAllStatuses(),
        ];

        //$events->description = 'Приём Предупреждения-'..' № счетчика'.$serialNumber;
        $this->description = "Предупреждение " . Html::a('№' . $this->model->id, Yii::$app->urlManager->createUrl(['admin/alertinput/editalerts', 'id' => $this->model->id,])) .
            ' ' . $this->model->getAlertTypeText() .
            " (  счетчик" . Html::a('№' . $this->model->serial_number, Yii::$app->urlManager->createUrl(['/admin/counter/editcounter', 'serial_number' => $this->model->serial_number,])) .
            " , модем " . Html::a('№' . $this->model->counter->user_modem_id, Yii::$app->urlManager->createUrl(["admin/counter/editmodem", 'serial_number' => $this->model->counter->user_modem_id])) .
            ", адресс " . Html::a(isset($this->model->address) ? $this->model->address->fulladdress : 'N/A', Yii::$app->urlManager->createUrl(["admin/counter/allcounterlist", 'geo_location_id' => $this->model->counter->geo_location_id])) .
            ")";
        $this->counter_type=$this->model->counter->type;

        if( $this->model->counter->geo_location_id!=0){
        $this->region_id = $this->model->counter->address->region_id;}

    }

    private function CounterModelsHandler()
    {

        //$events->description = ' №' . $this->userCounter->serial_number;

        $this->attributeLocalisationList=[
            'type'=>['NA'=>'Не задано']+$this->model->getCounterTypeList(),
        ];

        $this->description="Редактирование  модели счетчика ".
            Html::a('№'.$this->model->id."(".$this->model->name.")",Yii::$app->urlManager->createUrl(["admin/counter/editcountermodel", 'id' => $this->model->id]));

        $this->counter_type=$this->model->type;
    }


    private function CounterAddressDescription()
    {

        //$events->description = ' №' . $this->userCounter->serial_number;

        $this->attributeLocalisationList = [
            'status' => array_merge(['NA' => 'Не задано'], $this->model->getAllStatuses()),
            'region_id' => $this->getRegionList(),
        ];

        $this->description = "Редактирование Адресса" .
            Html::a('№' . $this->model->id . "(" . $this->model->fulladdress . ")", Yii::$app->urlManager->createUrl(["admin/address/editaddress", 'id' => $this->model->id]));
    }

    private function MenuItemDescription()
    {

        $this->description = "Редактирование Меню " . '№' . $this->model->id . "(" . $this->model->label . ")";
    }

    private function UserDescription()
    {

        $this->attributeLocalisationList = [
            'user_type' => array_merge(['NA' => 'Не задано'], $this->model->getUserTypeList()),
            'status' => array_merge(['NA' => 'Не задано'], $this->model->getAllStatuses())
        ];

        if ($this->type == 'edit') {
            $this->description = "Редактирование Юзера" .
                Html::a('№' . $this->model->id . "(" . $this->model->fullname . ")", Yii::$app->urlManager->createUrl(["admin/users/edituser", 'id' => $this->model->id,]));
        }

        if ($this->type == 'login') {
            $this->description = " Вход в систему   Пользователь:" . $this->model->fullname . " ip:" . $this->ip;
        }
    }

    public function AddEvent()
    {

        switch ($this->model->classname()) {

            case 'app\models\Counter':
                $this->UserCountersHandler();
                break;

             case 'app\models\Rmodule':
                $this->RmoduleHandler();
                break;

            case 'app\models\Modem':
                $this->UserModemsHandler();
                break;

            case 'app\models\AlertsList':

                $this->AlertListHandler();

                break;


            case 'app\models\CounterModels':

                $this->CounterModelsHandler();

                break;

            case 'app\models\MenuItem':
                $this->MenuItemDescription();
                break;


            case 'app\models\Address':
                $this->CounterAddressDescription();
                break;

            case 'app\models\User':
                $this->UserDescription();
                break;

        }


        if ($this->type == 'edit') {
            $description = $this->description . '<br/>' . $this->compareArray();
        } else {
            $description = $this->description;
        }


        $events = new EventLog();
        $events->user_id = Yii::$app->user->id;
        $events->url = Yii::$app->urlManager->hostInfo;
        $events->type = $this->type;
        $events->region_id = $this->region_id;
        $events->counter_type = $this->counter_type;
        $events->description = $description;
        $events->save();
    }

    protected function compareArray()
    {

        $string = 'Изменения:<br/>' . $this->model->classname().'<br/>'
        ;
        foreach ($this->oldAttributes as $key => $value) {

            if ($value != $this->newAttributes[$key]) {

                if (empty($value)) {
                    $value = 'NA';

                }

                if (empty($this->newAttributes[$key])) {
                    $value2 = 'NA';

                } else {
                    $value2 = $this->newAttributes[$key];
                }

                if (!array_key_exists($key, $this->attributeLocalisationList)) {
                    $string .= $this->model->getAttributeLabel($key) . ': было - ' . $value . ', стало - ' . $value2 . '<br/>';
                } else {
                    $func = $this->attributeLocalisationList;

                    $string .= $this->model->getAttributeLabel($key) . ': было - ' . $func[$key][$value] . ', стало - ' . $func[$key][$value2] . '<br/>';

                }
            }
        }
        return $string;
    }*/

}

/*  $events = new Events();
    $events->oldAttributes = $this->userCounter->getOldAttributes();
 *  $events->newAttributes = $this->userCounter->getAttributes();
    $events->model = $this->userCounter;
    $events->type = 'edit';
    $events->description = 'Редактирование Счетчика №' . $this->userCounter->serial_number;
    $events->AddEvent();
 */