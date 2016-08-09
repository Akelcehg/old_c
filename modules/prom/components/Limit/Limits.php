<?php
/**
 * Created by PhpStorm.
 * User: alexey
 * Date: 06.07.16
 * Time: 11:11
 */

namespace app\modules\prom\components\Limit;


use app\models\Limit;
use yii\base\Component;

class Limits extends  Component
{
    public $id=null;
    public $all_id=null;
    public $year=null;
    public $month=null;
    public $errors=[];


    private function GetLimitsModel($id=null,$all_id=null,$year=null,$month=null){

        $limits=Limit::find()
            ->filterWhere(['id'=>$id])
            ->andFilterWhere(['all_id'=>$all_id])
            ->andFilterWhere(['year'=>$year])
            ->andFilterWhere(['month'=>$month]);
        return $limits;
    }




    public function GetNextMonthLimit(){
        $dt=new \DateTime();
        $dt->add(new \DateInterval("P1M"));
        return $this->GetLimitsModel($this->id,$this->all_id,$dt->format('Y'),$dt->format('m'))->one();
    }

    public function GetLimitsInDataProvider(){
       $limitQuery=$this->GetLimitsModel($this->id,$this->all_id,$this->year,$this->month);

        $dataProvider = new \yii\data\ActiveDataProvider([
            'query' => $limitQuery
        ]);

        return $dataProvider;

    }



    public function GetLimit(){
       return $this->GetLimitsModel($this->id,$this->all_id,$this->year,$this->month)->one();
    }

    public function GetAllLimits(){
        return $this->GetLimitsModel()->all();
    }

    private function SaveLimit($id,$all_id,$year,$month,$limitCount){

        if(!empty($id)){
            $limit = Limit::findOne($id);
        }else{
            $limit = new Limit();
        }

        $limit->all_id=$all_id;
        $limit->year=$year;
        $limit->month=$month;
        $limit->limit=$limitCount;
        if($limit->save()){
            return true;
        }else{
            $this->errors=$limit->getErrors();
            return false;
        }

    }

    public function CreateLimit($limitCount){

        return $this->SaveLimit(null,$this->all_id,$this->year,$this->month,$limitCount);

    }

    public function EditLimit($limitCount){

        return $this->SaveLimit($this->id,$this->all_id,$this->year,$this->month,$limitCount);

    }
}