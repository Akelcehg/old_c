<?php
/**
 * Created by PhpStorm.
 * User: alexey
 * Date: 04.01.16
 * Time: 12:17
 */

namespace app\modules\admin\components;



use app\models\CounterSearch;
use app\components\RightsComponent;
use app\models\Counter;
use app\modules\metrix\models\MetrixCounter;
use app\modules\metrix\models\MetrixValveOperation;
use Yii;
use app\models\Rmodule;
use app\components\Events;

class CounterEvents extends AdminComponent
{

    public function CounterEvents(){
        $this->searchModel = new CounterSearch();
        $this->searchModel->builtIn = true;

        $rights = new  RightsComponent();
        $this->searchModel = $rights->updateSearchModelCounter($this->searchModel);

        $this->dataProvider = $this->searchModel->search(Yii::$app->request->queryParams);
    }

    public function CounterToCalendar()
    {
        $counterSerialNumber = Yii::$app->request->get('counter_id', 0);

        $counter = Counter::find()->joinWith('address')->where(['counters.id' => $counterSerialNumber])->one();
        $everyDayUpdate =$this->generateEveryDayUpdate($counter);
        $monthUpdate = $this->generateMonthUpdate($counter);
        $everyDayUpdate[] = $monthUpdate;
        for ($z = date('m'); $z <= 12; $z++) {
            $everyDayUpdate[] = [
                "title" => 'Обновление показаний',
                "start" => date('Y-m-d H:i:s', mktime(10, 0, 0, $z + 1, 0, date('Y'))),
                "description" => 'long description',
                "className" => ["event", "bg-color-greenLight"],
                "icon" => 'fa-check',
                "address" => $counter->fulladdress

            ];
        }
        return $everyDayUpdate;
    }

    public function GetCounterData()
    {
        $counterSerialNumber = Yii::$app->request->get('counter_id', 0);
        $counter = Counter::find()->joinWith('rmodule')->where(['counters.serial_number' => $counterSerialNumber])->asArray()->all();
        return $counter;
    }

    public function CounterSave()
    {
        $counterSerialNumber = Yii::$app->request->get('counter_id', 0);
        $update_interval = Yii::$app->request->get('update_interval', 0);
        $month_update = Yii::$app->request->get('month_update', 0);
        $month_update_type = Yii::$app->request->get('month_update_type', 0);
        $event = new Events();
        $rmodule= Rmodule::find()->where(['serial_number' => $counterSerialNumber])->one();
        $event->oldAttributes=$rmodule;
        $rmodule->update_interval = $update_interval;
        $rmodule->month_update = $month_update;
        $rmodule->month_update_type = $month_update_type;

        Yii::$app->response->format = 'json';

       // Events::AddEvent('edit', 'Редактирование Радиомодуля (изменение интервала обновления и или момента обязательного выхода на связь) № счетчкиа ' . $counterSerialNumber);




         if ($rmodule->save()){
        $event->newAttributes=$rmodule->getAttributes();
        $event->model=$rmodule;
        $event->type='edit';
        $event->AddEvent();
             return  true;
    } else {
             return false;
         }
    }

    private function timestampToSec($timestamp)
    {

        $timestampArray = explode(' ', $timestamp);
        $yearMounthDayArray = explode('-', $timestampArray[0]);
        $hourMinSecArray = explode(':', $timestampArray[1]);
        return mktime(0, 0, 0, $yearMounthDayArray[1], $yearMounthDayArray[2], $yearMounthDayArray[0]);
    }

    private function generateEveryDayUpdate($counter)
    {

        $createAtInSec = $this->timestampToSec($counter->created_at);
        $monthBeginInSec = $this->timestampToSec(date('Y-m') . '-1 00:00:00');
        $firstEventTimeInSec = 1 - fmod($monthBeginInSec - $createAtInSec, $counter->rmodule->update_interval * 3600);
        $firstEventTime = $monthBeginInSec + $firstEventTimeInSec * 3600;
        $countUpdateInDay = (3600 * 24) / ($counter->rmodule->update_interval * 3600);

        $array = [];
        for ($z = date('m'); $z <= 12; $z++) {
            for ($i = 1; $i <= 32; $i++) {
                for ($j = 1; $j <= $countUpdateInDay; $j++) {
                    $array[] = [
                        "id" => $counter->serial_number,
                        "title" => 'Обновление показаний',
                        "start" => date('Y-m-d H:i', $firstEventTime),
                        "end" => date('Y-m-d H:i', $firstEventTime + 15 * 60),
                        "allDay" => false,
                        "editable" => false,
                        "className" => ["event", "bg-color-blue"],
                        "icon" => 'fa-clock-o'
                    ];
                    $firstEventTime += $counter->rmodule->update_interval * 3600;
                }
            }
        }

        return $array;
    }

    private function generateMonthUpdate($counter)
    {

        $time = $this->timestampToSec($counter->rmodule->month_update);
        $timestampArray = explode(' ', $counter->rmodule->month_update);
        $yearMounthDayArray = explode('-', $timestampArray[0]);
        $hourMinSecArray = explode(':', $timestampArray[1]);
        for ($z = date('m'); $z <= 12; $z++) {
            $array = [
                "title" => 'месячное обновление',
                "start" => date('Y-m-d H:i', $time),
                "description" => 'long description',
                "className" => ["event", "bg-color-greenLight"],
                "icon" => 'fa-check'
            ];
            $time += 30 * 24 * 3600;
        }

        return $array;
    }

    public function MetrixToCalendar()
    {
        $id = Yii::$app->request->get('counter_id', 0);
        $everyDayUpdate=[];
        //$counter = Counter::find()->joinWith('address')->where(['counters.id' => $counterSerialNumber])->one();

        $metrix=MetrixCounter::find()->where(['id'=>$id])->one();

        $valveOperations=MetrixValveOperation::find()->where(['counter_id'=>$metrix->id])->all();
        foreach($valveOperations as $valceOp){
            $title="";
            if($valceOp->valve_status=='open'){
                $title="Открытие клапана";
                $class=["event", "bg-color-greenLight"];
            }else{
                $title="Закрытие клапана";
                $class=["event", "bg-color-redLight"];
            }

            $everyDayUpdate[] = [
                "title" => $title,
                "start" => (new \DateTime($valceOp->created_at))->format("Y-m-d H:i:s"),
                "description" => 'long description',
                "className" => $class,
                "icon" => 'fa-check',
                "address" => $metrix->fulladdress
            ];

        }

        return $everyDayUpdate;


    }

}