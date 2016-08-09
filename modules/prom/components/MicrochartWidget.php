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
use yii\base\Widget;

class MicrochartWidget extends Widget
{
    public $type=false;


public function run()
{
    $this->renderMC();
}


public function renderMC(){

?><div class="show-stat-microcharts">
    <?=TempMicrochartWidget::widget(['type'=>$this->type]);?>
    <?=PressMicrochartWidget::widget(['type'=>$this->type]);?>
    <?=QMicrochartWidget::widget(['type'=>$this->type]);?>
    <?=\app\modules\prom\components\SummaryMonthEmergencyMFSumWidjet::widget(['type'=>$this->type])?>
</div><?php
}

}