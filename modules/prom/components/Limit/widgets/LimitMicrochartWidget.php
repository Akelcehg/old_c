<?php
/**
 * Created by PhpStorm.
 * User: alexey
 * Date: 31.03.16
 * Time: 16:41
 */

namespace app\modules\prom\components\Limit\widgets;


use app\components\Prom\PromReportParts;
use app\models\Diagnostic;
use app\models\Intervention;
use app\modules\prom\components\CorrectorComponent;
use app\modules\prom\components\Limit\Limits;
use Yii;
use yii\base\Widget;

class LimitMicrochartWidget extends Widget
{
    public $type = false;
    public $id = null;


    public function run()
    {
        $consump = CorrectorComponent::AllCorrectorsMonthConsumption('prom', $this->id);


        $limit = new Limits();
        $limit->all_id = $this->id;
        $limit->year = date('Y');
        $limit->month = date('m');

        if ($limit->GetLimit()) {
            $limitCount = $limit->GetLimit()->limit;
        } else {
            $limitCount = 0;
        }

        if($limitCount !=0) {
            $percentage = $consump / $limitCount * 100;
        }else{
            $percentage=0;
        }

        $this->renderMC(round($consump,2), $percentage,$limitCount);
    }


    public function renderMC($consump, $percentage, $limit)
    {

        ?>
        <div class="col-xs-12 col-sm-3 col-md-3 col-lg-3">

        <?= \app\components\IfaceHelper::EasyPieChart(
        ceil($percentage), 50, 50,
        'txt-color-greenLight',
        'm3 font-xs', Yii::t('promWidgets','% Of the limit applied <br> limit this month').' '.$limit .' m<sup>3</sup>'
        , $consump) ?>

        </div><?php
    }

}