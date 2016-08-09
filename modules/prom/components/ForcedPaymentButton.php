<?php
namespace app\modules\prom\components;
use app\models\AlertsList;
use app\models\CorrectorToCounter;
use yii\grid\GridView;
use yii\helpers\Html;


class ForcedPaymentButton extends \yii\base\Widget
{
    /**
     * @var CorrectorToCounter
     */
    public $corrector;


    public function run() {

        $this->getView()->registerJs("$(function () {
            $('#simCardForcedPayment').click(function () {

                        $.get(app.baseUrl+'/prom/correctors/ajaxcheckforcedpayment', {

                            id:".$this->corrector->id.",

                        }, function (htmlData) {

                            $('#simCardForcedPaymentContainer').replaceWith(htmlData);

                        });

            })
         });",2);

        echo "<span id='simCardForcedPaymentContainer'>";
        if($this->corrector->isForcedPayment()){
            echo \Yii::t('promWidgets','Send on').$this->corrector->status->time_on_line;
        }else{
            echo Html::a(\Yii::t('promWidgets','Send'),'#',['id'=>"simCardForcedPayment"]);
        }
        echo "</span>";

    }

}