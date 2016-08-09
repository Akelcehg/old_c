<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Chart
 *
 * @author alks
 */

namespace app\components;

use app\models\Language;
use \yii\base\Widget;
use yii\helpers\Html;
use Yii;

class ChartMetrix extends Widget
{

    public $name = 'MyChart';
    public $anchor = "document";
    public $event = "ready";
    public $label = false;
    public $id;
    public $type = 'Line';
    public $width = 400;
    public $height = 400;
    public $labels = [];
    public $charttype = 'day';
    public $datasets = [];
    public $global = [];
    public $chartsConfig = [];
    public $options = [];


    public function init()
    {

        $this->getView()->registerJsFile('/js/eventCalendar/moment/moment-with-locales.js');
        //$this->getView()->registerJsFile('/js/eventCalendar/moment/ru.js');

        echo '<div class="dateControl"  style="clear:both;text-align:center;">'
            . '<input type="hidden" id="date" value="' . time() . '">'
            . '<span style="float:left;cursor: pointer;font-size:20px"><<</span>'
            . '<span style="float:left;cursor: pointer;font-size:20px" id="prev">&nbsp;</span>'
            . '<span id="datelabel" style="font-size:20px; font-weight:bolder;"></span>'
            . '<span style="width:85px;margin-left:20px"><span id="now" style="display:none;font-size:20px; font-weight:bolder;">'.Yii::t('app','today').'</span></span>'
            . '<span style="float:right;width:200px" id="nextCont">&nbsp;<span style="cursor: pointer;font-size:20px;" id="next"></span>'
            . '<span style="cursor: pointer;font-size:20px" id="nextC"> >> </span>'
            . '</span>'
            . '</div>';

        echo Html::tag('h6',Yii::t('metrix','consump'));
        echo ChartOne::widget([
            'name' => $this->name . 'Consum',
            'height' => $this->height,
            'width' => '100%',
            'event' => $this->event,
            'anchor' => $this->anchor,
            'legendShow'=>true,
            'datasets' => [
                'labels' => [0],
                'datasets' => [
                    [
                        'label' => "Понедельник",
                        'fillColor' => "rgba(255,0,0,0)",
                        'strokeColor' => "rgba(255,0,0,0.5)",
                        'pointColor' => "rgba(255,0,0,0.5)",
                        'pointStrokeColor' => "#fff",
                        'pointHighlightFill' => "#fff",
                        'pointHighlightStroke' => "rgba(255,0,0,0.5)",
                        'data' => [0]
                    ],
                    [
                        'label' => "Вторник",
                        'fillColor' => "rgba(255,165,0,0)",
                        'strokeColor' => "rgba(255,165,0,0.5)",
                        'pointColor' => "rgba(255,165,0,0.5)",
                        'pointStrokeColor' => "#fff",
                        'pointHighlightFill' => "#fff",
                        'pointHighlightStroke' => "rgba(255,165,0,0.5)",
                        'data' => [0]
                    ],
                    [
                        'label' => "Среда",
                        'fillColor' => "rgba(255,255,0,0)",
                        'strokeColor' => "rgba(255,255,0,0.5)",
                        'pointColor' => "rgba(255,255,0,0.5)",
                        'pointStrokeColor' => "#fff",
                        'pointHighlightFill' => "#fff",
                        'pointHighlightStroke' => "rgba(255,255,0,0.5)",
                        'data' => [0]
                    ],
                    [
                        'label' => "Четверг",
                        'fillColor' => "rgba(0,139,0,0)",
                        'strokeColor' => "rgba(0,139,0,0.5)",
                        'pointColor' => "rgba(0,139,0,0.5)",
                        'pointStrokeColor' => "#fff",
                        'pointHighlightFill' => "#fff",
                        'pointHighlightStroke' => "rgba(0,139,0,0.5)",
                        'data' => [0]
                    ],
                    [
                        'label' => "Пятница",
                        'fillColor' => "rgba(135,206,255,0)",
                        'strokeColor' => "rgba(135,206,255,0.5)",
                        'pointColor' => "rgba(135,206,255,0.5)",
                        'pointStrokeColor' => "#fff",
                        'pointHighlightFill' => "#fff",
                        'pointHighlightStroke' => "rgba(135,206,255,0.5)",
                        'data' => [0]
                    ],
                    [
                        'label' => "Суббота",
                        'fillColor' => "rgba(0,0,255,0)",
                        'strokeColor' => "rgba(0,0,255,0.5)",
                        'pointColor' => "rgba(0,0,255,0.5)",
                        'pointStrokeColor' => "#fff",
                        'pointHighlightFill' => "#fff",
                        'pointHighlightStroke' => "rgba(0,0,255,0.5)",
                        'data' => [0]
                    ],
                    [
                        'label' => "Воскресенье",
                        'fillColor' => "rgba(85,26,139,0)",
                        'strokeColor' => "rgba(85,26,139,0.5)",
                        'pointColor' => "rgba(85,26,139,0.5)",
                        'pointStrokeColor' => "#fff",
                        'pointHighlightFill' => "#fff",
                        'pointHighlightStroke' => "rgba(85,26,139,0.5)",
                        'data' => [0]
                    ],
                ],
            ],
            //  'global' => $globalChartSettings,
            'chartsConfig' => $this->chartsConfig,
        ]);


        $this->getView()->registerJsFile(Yii::$app->request->baseUrl . '/js/chart/Chart_yii_moment.js', ['position' => 2]);
        $this->getView()->registerJsFile(Yii::$app->request->baseUrl . '/js/chart/Chart_yii_ajaxM.js', ['position' => 2]);
        $this->getView()->registerJs($this->config(), $position = 1);



    }

    public function config()
    {
        $string = '';
        $string .= "var id = '" . $this->id . "';";
        $string .= "var name = '" . $this->name . "';";
        $string .= "var charts = ['chart" . $this->name . "Consum','chart" . $this->name . "Temp'];";
        $string .= "var chartMode = '" . $this->charttype . "';";
        return $string;
    }

}
