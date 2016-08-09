<?php
/**
 * Created by PhpStorm.
 * User: alexey
 * Date: 06.04.16
 * Time: 9:46
 */

namespace app\components\ComBit;


use app\models\Indication;
use yii\base\Component;

class ComBit extends Component
{

    public $counter1;
    public $beginDate;
    public $endDate;

    public function GetFirstDate (){

        $indication=Indication::find()
            ->where(['counter_id'=>$this->counter1->id])
            ->orderBy(
                'created_at'
            )
            ->one();

        if(!empty($indication)){
            return $indication->created_at;
        }else{
            return false;
        }


    }

    public function GetReports(){
        $dt=new \DateTime($this->GetFirstDate());
        $dn=new \DateTime($dt->format('Y').'-'.$dt->format('m').'-01');
        $dnow=new \DateTime();
        $array=[];
        for($i=0;$dn<=$dnow;$i++){
           $this->beginDate=$dn->format('Y-m-d');

            $dn->add(new \DateInterval("P1M"));
            $this->endDate=$dn->format('Y-m-d');

            $array[]=[
                'beginDate'=>$this->beginDate,
                'endDate'=>$this->endDate,
                'geo_location_id'=>$this->counter1->geo_location_id,
                'type'=>$this->counter1->type,
            ];
        }
        return $array;
    }


}