<?php
namespace app\modules\counter\components;
use app\models\AlertsList;
use app\models\Modem;
use yii\grid\GridView;
use yii\helpers\Html;


class GetPacketButton extends \yii\base\Widget
{

    /**
     * @var Modem
     */

    public $modem;


    public function run() {

        $this->getView()->registerJs("$(function () {
            $('#simCardGetPacket').click(function () {

                        $.get(app.baseUrl+'/counter/ajax/ajaxgetpacket', {

                            modem_id:".$this->modem->serial_number.",

                        }, function (htmlData) {

                            $('#simCardGetPacketContainer').replaceWith(htmlData);

                        });
            })
         });",2);

        echo "<span id='simCardGetPacketContainer'>";
        if($this->modem->isGetPacket()){
            echo "Запрос на получение названия пакета отправлен";
        }else{
            echo Html::a('Отправить','#',['id'=>"simCardGetPacket"]);
        }
        echo "</span>";

    }

}