<?php
/**
 * Created by PhpStorm.
 * User: alexey
 * Date: 06.04.16
 * Time: 9:46
 */

namespace app\components\ComBit;


use app\models\Indication;
use Yii;
use yii\base\Component;
use yii\base\Widget;
use yii\helpers\Html;

class ComBitReportWidget extends Widget
{

    public $counter1;

    public function run(){

        $comBit=new ComBit();
        $comBit->counter1=$this->counter1;
        $dateArray=$comBit->GetReports();

        //var_dump($dateArray);
        $html="";

        $th=Html::tag('th',Yii::t('metrix','report_for').':');
        $th.=Html::tag('th','-');
        $html.=Html::tag('tr',$th);

        foreach ($dateArray as $date){

            $tr=Html::tag('td',Yii::$app->formatter->asDate($date['beginDate'],'Y-LLLL'));
            $tr.=Html::tag('td',Html::a(Yii::t('metrix','download'),'#',$date+['class'=>"excel-export"]));
            $html.=Html::tag('tr',$tr);

        }
        return Html::tag('table',$html,['width'=>'500px','class'=>'table-striped table-hover table-bordered']);
    }

}