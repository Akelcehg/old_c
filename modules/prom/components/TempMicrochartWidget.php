<?php
/**
 * Created by PhpStorm.
 * User: alexey
 * Date: 31.03.16
 * Time: 16:41
 */

namespace app\modules\prom\components;


use app\components\Prom\PromReportParts;
use app\models\CorrectorToCounter;
use app\models\Diagnostic;
use app\models\Intervention;
use app\models\MomentData;
use Yii;
use yii\base\Widget;

class TempMicrochartWidget extends Widget
{
    public $type=false;
    public $id=null;


public function run()
{
    $temp=CorrectorComponent::GetAverageTemp($this->type,$this->id);
    $percentage=$temp+40;

    $lastAverageTemp=CorrectorComponent::GetLastAverageTemp($this->type,$this->id);


    $md=MomentData::find()
        ->where(['all_id'=>$this->id])
        ->orderBy(['tabs'=>SORT_DESC])
        ->one();
    if($md){
        $tMax=$md->tabs;
    }else{
        $tMax=+60;
    }


    $md1=MomentData::find()
        ->where(['all_id'=>$this->id])
        ->orderBy(['tabs'=>SORT_ASC])
        ->one();

    if($md1){
        $tMin=$md1->tabs;
    }else{
        $tMin=-40;
    }

    $this->renderMC($temp,$percentage,$lastAverageTemp,$tMax,$tMin);
}


public function renderMC($temp,$percentage,$lastAverageTemp,$tMax,$tMin){

?><div class="col-xs-12 col-sm-3 col-md-3 col-lg-3">
            <?=\app\components\IfaceHelper::EasyPieChart(ceil($percentage),50,50,'txt-color-red','degree-sign-celsius',Yii::t('promWidgets','Temperature'),$temp)?>
            <?=\app\components\IfaceHelper::MinMax($tMin,$tMax,'bg-color-blue txt-color-white','bg-color-red txt-color-white')?>
            <?=\app\components\IfaceHelper::Sparklines('bar',implode(',',$lastAverageTemp),'70px','33px',"sparkline txt-color-red hidden-sm hidden-md pull-right")?>
</div><?php
}

}