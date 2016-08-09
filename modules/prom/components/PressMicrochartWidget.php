<?php
/**
 * Created by PhpStorm.
 * User: alexey
 * Date: 31.03.16
 * Time: 16:41
 */

namespace app\modules\prom\components;


use app\components\Prom\PromReportParts;
use app\models\Diagnostic;
use app\models\Intervention;
use app\models\MomentData;
use Yii;
use yii\base\Widget;

class PressMicrochartWidget extends Widget
{
    public $type=false;
    public $id=null;


public function run()
{
    if(!$this->type){$this->type=Yii::$app->request->get('type','prom');}

    $press=CorrectorComponent::GetAveragePress($this->type,$this->id);
    if($this->type=="grs"){
        $percentage=($press-0.86)/65*100;

    }else {
        $percentage=($press-0.86)/4*100;
    }

    $lastAverage=CorrectorComponent::GetLastAveragePress($this->type,$this->id);

    $md=MomentData::find()
        ->where(['all_id'=>$this->id])
        ->orderBy(['pabs'=>SORT_DESC])
        ->one();

    if($md){
        $pMax=$md->pabs;
    }else{
        $pMax=65;
    }


    $md1=MomentData::find()
        ->where(['all_id'=>$this->id])
        ->orderBy(['pabs'=>SORT_ASC])
        ->one();

    if($md1){
        $pMin=$md1->pabs;
    }else{
        $pMin=0.86;
    }


    $this->renderMC($press,$percentage,$lastAverage,$pMax,$pMin);
}


public function renderMC($press,$percentage,$lastAverage,$pMax,$pMin){

?><div class="col-xs-12 col-sm-3 col-md-3 col-lg-3">

            <?=\app\components\IfaceHelper::EasyPieChart(ceil($percentage),50,50,'txt-color-greenLight','font-xs',Yii::t('promWidgets','Pressure'),$press)?>

            <?php echo \app\components\IfaceHelper::MinMax($pMin,$pMax,'bg-color-darken txt-color-white','bg-color-greenLight txt-color-white');?>


            <?=\app\components\IfaceHelper::Sparklines('bar',implode(',',$lastAverage),'70px','33px',"sparkline txt-color-greenLight hidden-sm hidden-md pull-right")?>

</div><?php
}

}