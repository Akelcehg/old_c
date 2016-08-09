<?php

/**
 * Created by PhpStorm.
 * User: alexey
 * Date: 04.01.16
 * Time: 9:58
 */
namespace app\modules\counter\components;

use app\models\Counter;
use app\models\Indication;
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


class CounterComponent extends AdminComponent
{

    public static function AllCountersDayConsumption($user_type = false)
    {
        if (!$user_type) {
            $user_type = Yii::$app->request->get('user_type', "legal_entity");
        }

        $counters=Counter::find()->filterWhere(['user_type'=>$user_type])->all();
        $dayConsumption = 0;

        $dt=new \DateTime();


        foreach ($counters as  $counter) {

            $ind=Indication::find()
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
        if (!$user_type) {
            $user_type = Yii::$app->request->get('user_type', "legal_entity");
        }

        $counters=Counter::find()->filterWhere(['user_type'=>$user_type])->all();
        $dayConsumption = 0;

        $dt=new \DateTime();
        $dt->sub(new \DateInterval("P1D"));


        foreach ($counters as  $counter) {

            $ind=Indication::find()
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
        if (!$user_type) {
            $user_type = Yii::$app->request->get('user_type', "legal_entity");
        }

        $counters=Counter::find()->filterWhere(['user_type'=>$user_type])->all();
        $dayConsumption = 0;

        $dt=new \DateTime(date("Y-m-1"));
        $dn=clone$dt;
        $dn->sub(new \DateInterval("P1M"));



        foreach ($counters as  $counter) {

            $ind=Indication::find()
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

    public static function AllIndividualPrevDayConsumption($user_type = false)
    {

        if (!$user_type) {
            $user_type = Yii::$app->request->get('user_type', "individual");
        }

        $counters=Counter::find()->filterWhere(['user_type'=>$user_type])->all();
        $dayConsumption = 0;

        $dt=new \DateTime();
        $dt->sub(new \DateInterval("P1D"));


        foreach ($counters as  $counter) {

            $query = (new \yii\db\Query())
                ->select('*')
                ->from(Indication::tableName())
                ->where([
                    'counter_id' =>  $counter->id,

                ])
                ->andWhere(['>','created_at',$dt->format("Y-m-d").' 00:00:00'])
                ->andWhere(['<','created_at',$dt->format("Y-m-d").' 23:59:59'])
                ->orderBy(['created_at'=>SORT_DESC])->all();


            if(isset($query[0]->indications)and isset($query[count($query)-1]->indications)) {
                $dayConsumption += $query[0]->indications-$query[count($query)-1]->indications;
            }
        }
        return $dayConsumption;


    }


    public static function CounterOnlineCounter($user_type = false)
{
    $date=new \DateTime();
    $date->sub(new \DateInterval("P7D"));

    $countersCount=\app\models\Counter::find()->where(['>','updated_at',$date->format("Y-m-d H:i:s")])
        ->andWhere(['user_type'=>$user_type])->count();
    return $countersCount;
}

    public static function CounterOnlineNow($user_type = false)
    {
        $date=new \DateTime();
        $date->setTime(0,0,0);

        $countersCount=\app\models\Counter::find()->where(['>','updated_at',$date->format("Y-m-d H:i:s")])
            ->andWhere(['user_type'=>$user_type])
            ->count();
        return $countersCount;
    }

    public static function CounterOnline($user_type = false){
        return self::CounterOnlineNow($user_type)."/".self::CounterOnlineCounter($user_type);
    }

}