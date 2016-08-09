<?php

/**
 * Created by PhpStorm.
 * User: alexey
 * Date: 04.01.16
 * Time: 9:58
 */
namespace app\modules\metrix\components;

use app\models\Counter;
use app\models\Indication;
use app\modules\metrix\models\MetrixCounter;
use app\modules\metrix\models\MetrixIndication;
use yii\base\Component;
use app\models\Address;
use app\models\AddressSearch;
use Yii;
use app\components\RightsComponent;
use app\models\CounterSearch;
use yii\data\ActiveDataProvider;
use app\modules\admin\components\AdminComponent;
use yii\db\Query;
use yii\web\HttpException;


class MetrixComponent extends AdminComponent
{

    public static function AllCountersDayConsumption($user_type = false)
    {


        $counters=MetrixCounter::find()->all();
        $dayConsumption = 0;

        $dt=new \DateTime();


        foreach ($counters as  $counter) {

            $ind=MetrixIndication::find()
                ->where(['counter_id'=>$counter->id])
                ->andWhere(['>','created_at',$dt->format("Y-m-d").' 00:00:00'])
                ->andWhere(['<','created_at',$dt->format("Y-m-d").' 23:59:59']);
                $indM = clone $ind;
            $indP = clone $ind;

            $indP=$indP->orderBy(['created_at'=>SORT_DESC])->one();
            $indM=$indM->orderBy(['created_at'=>SORT_ASC])->one();

            if(isset($indP)and isset($indM)) {
                $dayConsumption += $indP->indications-$indM->indications;
            }
        }
        return $dayConsumption;
    }

    public static function AllCountersPrevDayConsumption($user_type = false)
    {


        $counters=MetrixCounter::find()->all();
        $dayConsumption = 0;

        $dt=new \DateTime();
        $dt->sub(new \DateInterval("P1D"));


        foreach ($counters as  $counter) {

            $ind=MetrixIndication::find()
                ->where(['counter_id'=>$counter->id])
                ->andWhere(['>','created_at',$dt->format("Y-m-d").' 00:00:00'])
                ->andWhere(['<','created_at',$dt->format("Y-m-d").' 23:59:59']);

            $indM = clone $ind;
            $indP = clone $ind;

            $indP=$indP->orderBy(['created_at'=>SORT_DESC])->one();
            $indM=$indM->orderBy(['created_at'=>SORT_ASC])->one();

            if(isset($indP)and isset($indM)) {
                $dayConsumption += $indP->indications-$indM->indications;
            }
        }
        return $dayConsumption;
    }

    public static function AllCountersMonthConsumption($user_type = false)
    {


        $counters=MetrixCounter::find()->all();
        $dayConsumption = 0;

        $dt=new \DateTime(date("Y-m-1"));
        $dn=clone$dt;
        $dn->sub(new \DateInterval("P1M"));



        foreach ($counters as  $counter) {

            $ind=MetrixIndication::find()
                ->where(['counter_id'=>$counter->id])
                ->andWhere(['>','created_at',$dt->format("Y-m-d").' 00:00:00']);

            $indM = clone $ind;
            $indP = clone $ind;

            $indP=$indP->orderBy(['created_at'=>SORT_DESC])->one();
            $indM=$indM->orderBy(['created_at'=>SORT_ASC])->one();



            if(isset($indP)and isset($indM)) {
                $dayConsumption += $indP->indications-$indM->indications;
            }
        }
        return $dayConsumption;
    }
}