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

use \yii\base\Widget;
use yii\helpers\Html;
use Yii;

class ChartOneMetrix extends Widget
{

    public $name = 'MyChartMetrix';
    public $anchor="document";
    public $event="ready";
    public $label=false;
    public $type = 'Line';
    public $width = '400px';
    public $height = '400px';
    public $labels = [];
    public $type1 = 'default';
    public $datasets = [];
    public $global = [];
    public $chartsConfig = [];
    public $options=[];
    public $legendShow=false;


    public function init()
    {

        $html="";
        $html.='';
        if($this->label){
            $html.='<p>'.$this->label.'</p>';
        }
        $html.='<canvas id="' . $this->name . '"  style= "width:' . $this->width . ';height:' . $this->height . '" class="chartCanvas" ></canvas>';
        $html.='';
        echo $html;
        if($this->legendShow) {
            echo ChartLegend::widget(['name'=>$this->name]);
        }

        $this->getView()->registerJsFile(Yii::$app->request->baseUrl . '/js/chart/Chart.js', ['position' => 1]);
        $this->getView()->registerJs($this->config(), $position = 1);
        $this->getView()->registerJsFile(Yii::$app->request->baseUrl . '/js/chart/Chart_yii_one_metrix.js',['position' => 2]);

        $this->getView()->registerJs('
        $(function () {
           $('.$this->anchor.').on("'.$this->event.'",function(){
           ctx'.$this->name.' = document.getElementById(\''.$this->name.'\').getContext("2d");




    var gradient =  ctx'.$this->name.'.createLinearGradient(0, 0, 0, 400);
    gradient.addColorStop(0, "rgba(87,136,156,0)");
    gradient.addColorStop(1, "rgba(87,136,156,1)");

    '.$this->name.'.datasets[0].fillColor=gradient;


  chart'.$this->name.' = new Chart(ctx'.$this->name.').Line('.$this->name.',option'.$this->name.');


        })});', $position = 2);
    }

    public function globalConfig()
    {
        $string = '{';
        if ($this->global) {
            foreach ($this->global as $key => $value) {
                $string .= $key . ':' . $value . ',';
            }
        }
        return $string . '};';
    }

    public function chartsConfig()
    {
        $string = '{';
        if ($this->chartsConfig) {
            foreach ($this->chartsConfig as $key => $value) {

                $string .= $key . ':' . $value . ',';
            }
        }
        return $string . '};';
    }

    public function setDatasets()
    {
        $string = '';
        $string .= "var " . $this->name . " = " . json_encode($this->datasets) . ";";
        return $string;
    }


    public function config()
    {
        $string = '';
        $string .= "var type = '" . $this->type . "'; ";
        $string .= "var chartGlobalConfig = " . $this->globalConfig() . "; ";
        $string .= "var option" . $this->name . " = " . $this->chartsConfig() . "; ";
        $string .=$this->setDatasets();
        return $string;
    }

}
