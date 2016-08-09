<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Chart
 *
 * @author alks
 */

namespace app\components;

use app\models\Address;
use app\models\User;
use Yii;
use yii\base\Component;
use app\models\Counter;
use app\models\Modem;
use app\models\ModemTemparatues;
use app\models\Indication;

class ChartCalc extends Component  {
    public $counter_id;
    public $beginDate=false;
    public $endDate=false;
    public $data;

    public $countersTable='counters';

    public  $counterModel='';
    public  $modemModel='';
    public  $indicationsModel='';
    public  $temperatureModel='';

    public function __construct()
    {


        if(empty($this->counterModel)){
            $this->counterModel=Counter::className();
        }

        if(empty($this->modemModel)){
            $this->modemModel=Modem::className();
        }

        if(empty($this->indicationsModel)){
            $this->indicationsModel=Indication::className();
        }

        if(empty($this->temperatureModel)){
            $this->temperatureModel=ModemTemparatues::className();
    }

        parent::__construct();
    }

    public function tempChart()
    {
        $dateArray =  explode('-', $this->beginDate);        
        $monthTempArray=[];
        for($i=1;$i<=date('t',  mktime(0, 0, 0, $dateArray[1], $dateArray[2], $dateArray[0]));$i++)
        {
            $tempArray = $this->tempGraph($dateArray[0].'-'.$dateArray[1].'-'.$i);
            
            $j=0;
            $tempSum=0;
            if (!empty($tempArray)){
            foreach ($tempArray as $oneTemp)
                {
                    $tempSum+=$oneTemp['data'][0];
                    $j++;
                }
                
                }else
                {$tempSum=0;$j=1;}
            $monthTempArray[]=[
                'label' => $dateArray[0].'-'.$dateArray[1].'-'.$i,
                'data' => [round($tempSum/$j, 0)]
            ];    
                
        }

        $counter = call_user_func($this->counterModel.'::findOne',['id' => $this->counter_id]);

        if($this->modemModel=Modem::className()) {
            $modem = call_user_func($this->modemModel . '::findOne', ['serial_number' => $counter->modem_id]); //Modem::findOne(['serial_number' => $counter->modem_id]);
        }else{
            $modem = call_user_func($this->modemModel . '::findOne', ['modem_id' => $counter->modem_id]); //Modem::findOne(['serial_number' => $counter->modem_id]);
        }
        if(isset($modem->type)) {
            $monthTempArray[] = ['type' => $modem->type];
        }

         return $monthTempArray;
    }
    
     public function tempGraph($endDate) {

         $counter = call_user_func($this->counterModel.'::findOne',['id' => $this->counter_id]);
         $modemTempsArray = call_user_func($this->temperatureModel.'::find')//ModemTemparatues::find()
                ->where(['modem_id' => $counter->modem_id])
                ->andWhere('created_at > DATE_ADD( :endDate , INTERVAL "0" DAY)', [':endDate' => $endDate])
                ->andWhere('created_at < DATE_ADD( :endDate, INTERVAL "1" DAY)', [':endDate' => $endDate])
                ->all();
     
        //$label = $this->graphByDay($counter_id, $beginDate, $endDate);
        $allconsumptionArray = [];
        foreach ($modemTempsArray as $oneTemps) {
            $allconsumptionArray[] = [

                'label' => explode(' ', $oneTemps->created_at)[1],
                'data' => [$oneTemps->temp]
            ];
        }
        return $allconsumptionArray;    
     }
     
      public function tempChartByDay()
            {
        
                $counter = call_user_func($this->counterModel.'::findOne',['id' => $this->counter_id]);
        
                $modemTempsArray = call_user_func($this->temperatureModel.'::find')
                        ->where(['modem_id' => $counter->modem_id])
                        ->andWhere('created_at > DATE_ADD( :endDate , INTERVAL "0" DAY)', [':endDate' => $this->endDate])
                        ->andWhere('created_at < DATE_ADD( :endDate, INTERVAL "1" DAY)', [':endDate' => $this->endDate])
                        ->all();
                /* if ($counter_id) {

                  $label = $this->oneCounterGraph($counter_id, $beginDate, $endDate);
                  } else {
                  $label = $this->allCounterGraph($beginDate, $endDate);
                  } */
                //$label = $this->graphByDay($counter_id, $beginDate, $endDate);
                $allconsumptionArray = [];
                foreach ($modemTempsArray as $oneTemps) {
                    $allconsumptionArray[] = [

                        'label' => explode(' ', $oneTemps->created_at)[1],
                        'data' => [$oneTemps->temp]
                    ];
                }

                $modem =call_user_func($this->modemModel.'::findOne',['serial_number' => $counter->modem_id]);// Modem::findOne(['serial_number' => $counter->modem_id]);
                if(isset($modem->type)) {
                    $allconsumptionArray[] = ['type' => $modem->type];
                }
                
                return $allconsumptionArray;
            }
     
     
     public function chartByDay() {
        if ($this->counter_id == 0) {
            return false;
        };

        $beginDateArr = explode('-', $this->beginDate);
        $endDateArr = explode('-', $this->endDate);

        
        $counters =call_user_func($this->counterModel.'::find') //Counter::find()
                ->joinWith('address')
                ->filterWhere(['in', $this->countersTable.'.id', $this->counter_id]);
        $counter = $counters->one();

        if (!empty($this->counter_id) and count(explode(',', $this->counter_id)) == 1) {


            if (!(isset($counter->flat) and ($counter->flat != 0))) {
                $address = $counter->address->fulladdress;
            } else {
                $address = $counter->address->fulladdress . ' кв.' . $counter->flat;
            }
        } else {
            $address = 'Суммарный расход';
        }

        if (CounterQuery::isRole('admin_region')) {

            $user = User::find()->where(['id' => Yii::$app->user->id])->one();
            $counters->andWhere('address.region_id =:region_id', [':region_id' => $user->geo_location_id]);
        }

        $counters = $counters->all();


        $indications =call_user_func($this->indicationsModel.'::find') //Indication::find()
                ->where('created_at >DATE_ADD( :endDate , INTERVAL "0" DAY)AND created_at < DATE_ADD( :endDate , INTERVAL "1" DAY)',
                [':endDate' => $this->endDate] )
                ->andfilterWhere(['in', 'counter_id', $this->counter_id])
                ->orderBy('created_at')
                ->all();

        $indicationsByCounters = $this->indicationsByCounters($counters, $indications);

        $allconsumptionArray = [];


        if ($this->beginDate <= $counter->created_at) {
            $buf = $counter->initial_indications;
        } else {
            $ind = call_user_func($this->indicationsModel.'::find')
                            ->where(['counter_id' => $this->counter_id])
                            ->andWhere('created_at < DATE_ADD( :endDate , INTERVAL "0" DAY)', [':endDate' => $this->endDate])
                            ->orderBy(['created_at' => SORT_DESC])->one();
            if ($ind)
                $buf = $ind->indications;
            else
                $buf = 0;
        }


        foreach ($indicationsByCounters as $oneCounterIndications) {
            $consumptionArray = [];
            foreach ($oneCounterIndications as $indications) {
                if ($buf == 0) {
                    $consumption = 0;
                    $buf = $indications->indications;
                } else {
                    $consumption = $indications->indications - $buf;
                    $buf = $indications->indications;
                }

                $allconsumptionArray[] = [
                    'address' => $address,
                    'label' => explode(' ', $indications->created_at)[1],
                    'data' => [round($consumption, 3)]
                ];
            }
        }

        return $allconsumptionArray;
    }

    
     protected function indicationsByCounters($counters, $indications) {
        $indicationsByCounters = [];

        foreach ($counters as $counter) {

            $oneCounterIndications = [];

            foreach ($indications as $indication) {

                if ($indication->counter_id == $counter->id) {

                    $oneCounterIndications[] = $indication;
                }
            }

            $indicationsByCounters[$counter->id] = $oneCounterIndications;
        }

        return $indicationsByCounters;
    }
    
     protected function indicationsByCountersAndDate($indicationsByCounters, $endDateInSec, $daysRange) {

        $indicationsByCountersAndDate = [];

        foreach ($indicationsByCounters as $key => $indicationsByOneCounter) {

            $oneCounterBuf = [];
            $buf = 0;

            for ($i = $daysRange; $i >= 0; $i--) {

                $date = date('Y-m-d', $endDateInSec - 60 * 60 * 24 * $i);

                if (is_array($indicationsByOneCounter)) {

                    foreach ($indicationsByOneCounter as $oneIndication) {

                        if (!empty($oneIndication)) {

                            if (strstr($oneIndication->created_at, $date) and $buf < $oneIndication->indications) {

                                $buf = $oneIndication->indications;
                            }
                        }
                    }
                }

                $oneCounterBuf[] = ['indications' => $buf, 'date' => $date];
            }

            $indicationsByCountersAndDate[$key] = $oneCounterBuf;
        }

        return $indicationsByCountersAndDate;
    }

    protected function consumptionByCounterAndDate($indicationsByCountersAndDate, $beginDate) {

        $consumptionByCounterAndDate = [];
        foreach ($indicationsByCountersAndDate as $key => $indicationsByOneCounterAndDate) {
            $counter = call_user_func($this->counterModel.'::find')->where(['id' => $key])->one();
                //Counter::find()
            if ($beginDate <= $counter->created_at) {
                $buf = $counter->initial_indications;
            } else {
                $ind = call_user_func($this->indicationsModel.'::find')
                                ->where(['counter_id' => $key])
                                ->andWhere('created_at < :beginDate', [':beginDate' => $beginDate])
                                ->orderBy(['created_at' => SORT_DESC])->one();

                if ($ind instanceof $this->indicationsModel) {
                    $buf = $ind->indications;
                }else {
                    $buf = 0;
                }

            }

            $consumptionByOneCounter = [];

            foreach ($indicationsByOneCounterAndDate as $oneIndication) {
                if ($oneIndication['indications'] >= $buf) {
                    $buf2 = $oneIndication['indications'];
                    $oneIndication['indications'] = $oneIndication['indications'] - $buf;
                    $buf = $buf2;
                }
                $consumptionByOneCounter[] = $oneIndication;
            }
            $consumptionByCounterAndDate[$key] = $consumptionByOneCounter;
        }

        return $consumptionByCounterAndDate;
    }
    
    
   public function chartByWeek()
            {
        
                $label = [];
                $count = 0;
                $ij = 0;
                for ($i = 1; $i <= 7; $i++) {
                    $label[] = $this->graphByDayF($this->counter_id, $this->data[$i - 1], $this->data[$i-1]);
                    if ($count < count($label[$i - 1])) {
                        $count = count($label[$i - 1]);
                        $ij = $i - 1;
                    }
                }



                $output = [];

                for ($i = 0; $i < $count; $i++) {
                    $data = [];
                    for ($j = 0; $j < 7; $j++) {
                        if (isset($label[$j][$i]['data'][0])) {

                            $data[] = $label[$j][$i]['data'][0];
                        } else {
                            $data[] = 0;
                        }
                    }

                    $output[] = ['data' => $data, 'label' => $label[$ij][$i]['label'], 'address' => $label[$ij][$i]['address']];
                }
                
                return $output;
        
            }  
    
     protected function graphByDayF($counter_id, $beginDate, $endDate) {
        if ($counter_id == 0) {
            return false;
        };

        $beginDateArr = explode('-', $beginDate);
        $endDateArr = explode('-', $endDate);

        $beginDateInSec = mktime(23, 59, 59, $beginDateArr[1], $beginDateArr[2], $beginDateArr[0]);
        $endDateInSec = mktime(23, 59, 59, $endDateArr[1], $endDateArr[2], $endDateArr[0]);




        //$daysRange = ($endDateInSec - $beginDateInSec) / (60 * 60 * 24);


        $counters = call_user_func($this->counterModel.'::find')
                ->joinWith('address')
                ->filterWhere(['in', $this->countersTable.'.id', $counter_id]);
        $counter = $counters->one();
        if (!empty($counter_id) and count(explode(',', $counter_id)) == 1) {


            if ( !(isset($counter->flat)and $counter->flat != 0)) {
                $address = $counter->address->fulladdress;
            } else {
                $address = $counter->address->fulladdress . ' кв.' . $counter->flat;
            }
        } else {
            $address = 'Суммарный расход';
        }

        if (CounterQuery::isRole('admin_region')) {

            $user = User::find()->where(['id' => Yii::$app->user->id])->one();
            $counters->andWhere('address.region_id =:region_id', [':region_id' => $user->geo_location_id]);
        }

        $counters = $counters->all();


        $indications = call_user_func([$this->indicationsModel,'find'])
                ->where('created_at >DATE_ADD( :endDate , INTERVAL "0" DAY)AND created_at < DATE_ADD( :endDate , INTERVAL "1" DAY)',
                [':endDate' => $endDate] )
                ->andfilterWhere(['in', 'counter_id', $counter_id])
                ->orderBy('created_at')
                ->all();

        $indicationsByCounters = $this->indicationsByCounters($counters, $indications);

        $allconsumptionArray = [];


        if ($beginDate <= $counter->created_at) {
            $buf = $counter->initial_indications;
        } else {
            $ind = call_user_func($this->indicationsModel.'::find')
                            ->where(['counter_id' => $counter_id])
                            ->andWhere('created_at < DATE_ADD( :endDate , INTERVAL "0" DAY)', [':endDate' => $endDate])
                            ->orderBy(['created_at' => SORT_DESC])->one();
            //TODO - need to check
            if ($ind instanceof $this->indicationsModel) {
                $buf = $ind->indications;
            }else {
                $buf = 0;
            }
        }


        foreach ($indicationsByCounters as $oneCounterIndications) {
            $consumptionArray = [];
            foreach ($oneCounterIndications as $indications) {
                if ($buf == 0) {
                    $consumption = 0;
                    $buf = $indications->indications;
                } else {
                    $consumption = $indications->indications - $buf;
                    $buf = $indications->indications;
                }

                $allconsumptionArray[] = [
                    'address' => $address,
                    'label' => explode(' ', $indications->created_at)[1],
                    'data' => [round($consumption, 3)]
                ];
            }
        }

        return $allconsumptionArray;
    }
    
          public function tempChartByWeek()
            {
                
                $label = [];
                $count = 0;
                $ij = 0;
                for ($i = 1; $i <= 7; $i++) {
                    $label[] = $this->tempGraphF($this->counter_id, $this->data[$i - 1], $this->data[$i -1]);
                    if ($count < count($label[$i - 1])) {
                        $count = count($label[$i - 1]);
                        $ij = $i - 1;
                    }
                }



                $output = [];

                for ($i = 0; $i < $count; $i++) {
                    $data = [];
                    for ($j = 0; $j < 7; $j++) {
                        if (isset($label[$j][$i]['data'][0])) {

                            $data[] = $label[$j][$i]['data'][0];
                        } else {
                            $data[] = 0;
                        }
                    }

                    $output[] = ['data' => $data, 'label' => $label[$ij][$i]['label']];
                }

                $counter = call_user_func($this->counterModel.'::findOne',['id' => $this->counter_id]);//Counter::findOne(['id' => $this->counter_id]);
                $modem = call_user_func($this->modemModel.'::findOne',['serial_number' => $counter->modem_id]);//Modem::findOne(['serial_number' => $counter->modem_id]);
                if(isset($modem->type)) {
                    $output[] = ['type' => $modem->type];
                }


               
                
                return $output;
                
            } 
    
    public function tempChartF($counter_id,$beginDate)
    {
         $dateArray =  explode('-', $beginDate);        
        $monthTempArray=[];
        for($i=1;$i<=date('t',  mktime(0, 0, 0, $dateArray[1], $dateArray[2], $dateArray[0]));$i++)
        {
            $tempArray = $this->tempGraph($counter_id, $beginDate, $dateArray[0].'-'.$dateArray[1].'-'.$i);
            
            $j=0;
            $tempSum=0;
            if (!empty($tempArray)){
            foreach ($tempArray as $oneTemp)
                {
                    $tempSum+=$oneTemp['data'][0];
                    $j++;
                }
            
                }else
                {$tempSum=0;$j=1;}
            $monthTempArray[]=[
                'label' => $dateArray[0].'-'.$dateArray[1].'-'.$i,
                'data' => [round($tempSum/$j, 0)]
            ];    
                
        }

        $counter = call_user_func($this->counterModel.'::findOne',['id' => $this->counter_id]);//Counter::findOne(['id' => $this->counter_id]);
        $modem = call_user_func($this->modemModel.'::findOne',['serial_number' => $counter->modem_id]);//Modem::findOne(['serial_number' => $counter->modem_id]);
        if(isset($modem->type)) {
            $monthTempArray[] = ['type' => $modem->type];
        }
         return $monthTempArray;
    }
    
     public function tempGraphF($counter_id, $beginDate, $endDate) {

         $counter = call_user_func($this->counterModel.'::findOne',['id' => $this->counter_id]);
        $modemTempsArray = call_user_func($this->temperatureModel.'::find')//ModemTemparatues::find()
                ->where(['modem_id' => $counter->modem_id])
                ->andWhere('created_at > DATE_ADD( :endDate , INTERVAL "0" DAY)', [':endDate' => $endDate])
                ->andWhere('created_at < DATE_ADD( :endDate, INTERVAL "1" DAY)', [':endDate' => $endDate])
                ->all();
     
        //$label = $this->graphByDay($counter_id, $beginDate, $endDate);
        $allconsumptionArray = [];
        foreach ($modemTempsArray as $oneTemps) {
            $allconsumptionArray[] = [

                'label' => explode(' ', $oneTemps->created_at)[1],
                'data' => [$oneTemps->temp]
            ];
        }
        return $allconsumptionArray;    
     }
     
      public function graph() {



          if($this->beginDate==0 and $this->endDate==0){
              $this->endDate=date("Y-m-d");
              $dt=new \DateTime($this->endDate);
              $dt->sub(new \DateInterval("P30D"));
              $this->beginDate = $dt ->format("Y-m-d");
          }

          $user_type = Yii::$app->request->get('user_type', null);


        //  echo $this->beginDate."/".$this->endDate;

        $beginDateArr = explode('-', $this->beginDate);
        $endDateArr = explode('-', $this->endDate);

        $beginDateInSec = mktime(0, 0, 0, $beginDateArr[1], $beginDateArr[2], $beginDateArr[0]);
        $endDateInSec = mktime(23, 59, 59, $endDateArr[1], $endDateArr[2], $endDateArr[0]);

        $daysRange = ($endDateInSec - $beginDateInSec) / (60 * 60 * 24);


        $counters = call_user_func($this->counterModel.'::find')//Counter::find()
            ->joinWith('address')
            ->filterWhere(['in', $this->countersTable.'.id', $this->counter_id])
            ->andFilterWhere(['in','counters.user_type',$user_type]);

        if (!empty($this->counter_id) and count(explode(',', $this->counter_id)) == 1) {

            $counter = $counters->one();
            if(isset($counter->address->fulladdress) and isset($counter->flat)) {
                $address = $counter->address->fulladdress . ' кв.' . $counter->flat;
            }else{
                $address='';
            }

        } else {
            $address = 'Суммарный расход';
        }

        if (CounterQuery::isRole('admin_region')) {

            $user = User::find()->where(['id' => Yii::$app->user->id])->one();
            $counters->andWhere('address.region_id =:region_id', [':region_id' => $user->geo_location_id]);
        }

        $counters = $counters->all();

        $indications = call_user_func($this->indicationsModel.'::find')
                ->where('created_at > :beginDate AND created_at < :endDate', [
                    ':beginDate' => date('Y-m-d H:i:s', $beginDateInSec),
                    ':endDate' => date('Y-m-d H:i:s', $endDateInSec)
                        ]
                )
                ->andfilterWhere(['in', 'counter_id', $this->counter_id])

                ->orderBy('created_at')
                ->all();

        $indicationsByCounters = $this->indicationsByCounters($counters, $indications);



        $indicationsByCountersAndDate = $this->indicationsByCountersAndDate($indicationsByCounters, $endDateInSec, $daysRange);



        $consumptionByCounterAndDate = $this->consumptionByCounterAndDate($indicationsByCountersAndDate, $this->beginDate);



        $consumptionAllCounters = [];
          $prevlabel="";
        for ($i = $daysRange; $i >= 0; $i--) {

            $date = date('Y-m-d', $endDateInSec - 60 * 60 * 24 * $i);
            $allConsumption = 0;
            foreach ($consumptionByCounterAndDate as $consumptionByOneCounter) {

                foreach ($consumptionByOneCounter as $oneIndication) {

                    if ($oneIndication['date'] == $date) {
                        $allConsumption += $oneIndication['indications'];
                    }
                }
            }

            if($date!=$prevlabel) {
                $dt= new \DateTime($date);
                $consumptionAllCounters[] = [
                    'address' => $address,
                    'label' => $dt->format("j"),
                    'data' => [round($allConsumption, 3)]];
                $prevlabel=$date;
            }
        }





        return $consumptionAllCounters ;
    }
}
