<?php
namespace app\modules\prom\components;
use app\models\AlertsList;
use app\models\CorrectorToCounter;
use yii\grid\GridView;
use yii\helpers\Html;


class ForcedReportButton extends \yii\base\Widget
{
    /**
     * @var CorrectorToCounter
     */
    public $corrector;


    public function run() {

        $this->getView()->registerJs("$(function () {
            $('#simCardForcedReport').click(function () {

                        $.get(app.baseUrl+'/prom/correctors/ajaxcheckforcedreport', {

                            id:".$this->corrector->id.",

                        }, function (htmlData) {

                            $('#simCardForcedReportContainer').replaceWith(htmlData);

                        });

            })
         });",2);

        echo "<span id='simCardForcedReportContainer'>";
        if($this->corrector->isForcedReport()){
            echo\Yii::t('promWidgets','Send on').$this->corrector->status->time_on_line;
        }else{
            echo Html::a(\Yii::t('promWidgets','Send'),'#',['id'=>"simCardForcedReport"]);
        }
        echo "</span>";

    }

}