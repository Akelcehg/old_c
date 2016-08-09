<?php
namespace app\modules\counter\components;
use app\models\AlertsList;
use app\models\Modem;
use yii\grid\GridView;
use yii\helpers\Html;


class ForcedPaymentButton extends \yii\base\Widget
{

    /**
     * @var Modem
     */

    public $modem;


    public function run() {

        $this->getView()->registerJs("$(function () {
            $('#simCardForcedPayment').click(function () {

                        $.get(app.baseUrl+'/counter/ajax/ajaxcheckforcedpayment', {

                            modem_id:".$this->modem->serial_number.",

                        }, function (htmlData) {

                            $('#simCardForcedPaymentContainer').replaceWith(htmlData);

                        });
            })
         });",2);

        echo "<span id='simCardForcedPaymentContainer'>";
        if($this->modem->isForcedPayment()){
            echo "Запрос на принудительное списание  отправлен";
        }else{
            echo Html::a('отправить','#',['id'=>"simCardForcedPayment"]);
        }
        echo "</span>";

    }

}