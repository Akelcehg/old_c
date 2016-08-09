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

class PromSummaryWidjet1 extends Widget
{
    public $type=false;
    public $id=null;


    public function run()
    {

        ?>
        <div >
            <div class="row" style="padding-top: 10px">
                <?= \app\modules\prom\components\DayConsumpProgressbarWidjet::widget(['type'=>$this->type,'id'=>$this->id]) ?>
                <?= \app\modules\prom\components\PrevDayConsumpProgressbarWidjet::widget(['type'=>$this->type,'id'=>$this->id]) ?>
                <?= \app\modules\prom\components\MonthDayConsumpProgressbarWidjet::widget(['type'=>$this->type,'id'=>$this->id]) ?>
            </div>
        </div>
        <?php
    }


}

