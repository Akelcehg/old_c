<?php
namespace app\modules\prom\components;
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

                        $.get(app.baseUrl+'/prom/correctors/ajaxgetpacket', {

                            modem_id:".$this->modem->modem_id.",

                        }, function (htmlData) {

                            $('#simCardGetPacketContainer').replaceWith(htmlData);

                        });
            })
         });",2);

        echo "<span id='simCardGetPacketContainer'>";
        if($this->modem->isGetPacket()){
            echo \Yii::t('promWidgets','Send on').$this->modem->status->time_on_line;
        }else{
            echo Html::a(\Yii::t('promWidgets','Send'),'#',['id'=>"simCardGetPacket"]);
        }
        echo "</span>";

    }

}