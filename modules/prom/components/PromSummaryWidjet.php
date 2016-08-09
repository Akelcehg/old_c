<?php
/**
 * Created by PhpStorm.
 * User: alks
 * Date: 11.05.2016
 * Time: 16:10
 */

namespace app\modules\prom\components;


use app\models\CorrectorToCounter;
use app\models\Diagnostic;
use app\models\EmergencySituation;
use app\models\Intervention;
use Yii;
use yii\base\Widget;

class PromSummaryWidjet extends Widget
{
    public $type=false;
    public $id=null;


    public function run()
    {

        ?>
        <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4 show-stats">
            <div class="row" style="padding-top: 50px">
                <?= \app\modules\prom\components\DayConsumpProgressbarWidjet::widget(['type'=>$this->type,'id'=>$this->id]) ?>
                <?= \app\modules\prom\components\PrevDayConsumpProgressbarWidjet::widget(['type'=>$this->type,'id'=>$this->id]) ?>
                <?= \app\modules\prom\components\MonthDayConsumpProgressbarWidjet::widget(['type'=>$this->type,'id'=>$this->id]) ?>
            </div>
        </div>
        <?php
    }


}

