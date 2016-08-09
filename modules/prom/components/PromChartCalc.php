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
use yii\base\Component;
use app\models\Counter;
use app\models\Modem;
use app\models\ModemTemparatues;
use app\models\Indication;

class PromChartCalc extends Component  {
    public $id;
    public $beginDate;
    public $endDate;
    public $data;

    public function monthChart(){

        Yii::$app->response->format = 'json';
        $label=[];
        $counter_id = $this->id;
        if($counter_id){
            $cc=CorrectorToCounter::findOne($counter_id);
            $hd=DayData::find()->where(['all_id'=>$cc->id,'year'=>date("y"),"month"=>date("n")])->orderBy(["day"=>SORT_ASC])->groupBy("day")->all();

            if($hd) {

                for($i=0;$i<date("t");$i++) {


                    if(isset($hd[$i])) {
                        $label[] = [

                            'label' => $hd[$i]->day,
                            'data' => [round($hd[$i]->v_sc, 3)]
                        ];
                    }else{
                        $label[] = [

                            'label' => $i+1,
                            'data' => 0
                        ];

                    }

                }

                return $label;
            }
            else{

                $md=MomentData::find()->where(['all_id'=>$cc->id])->orderBy(['created_at'=>SORT_DESC])->one();

                $label[] = [

                    'label' => date("j"),
                    'data' => [round($md->vday_sc, 3)]];

                return $label;
            }
        }else{
            return CorrectorComponent::GetCurrentMonthChart();
        }
    }

    public function dayChart(){

    }



}
