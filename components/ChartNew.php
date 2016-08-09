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

class ChartNew extends Widget {

    public $name = 'MyChart';
    public $type = 'Line';
    public $width = 400;
    public $height = 400;
    public $labels = [];
    public $type1 = 'default';
    public $datasets = [];
    public $global = [];
    public $chartsConfig = [];

    public function init() {

       
      
        
    $this->getView()->registerJsFile(Yii::$app->request->baseUrl . '/js/chart/Chart.js',['position' => 1]);
    $this->getView()->registerJs($this->config(), $position = 1);
    $this->getView()->registerJs($this->setDatasets(), $position = 1);
   
 
    echo'<div style="overflow:auto;width:1300px;"> <canvas id="' . $this->name . '"  style= "width:1000px;' . $this->height . '" class="chartCanvas" ></canvas></div>';
    echo'<div style="overflow:auto;width:1300px;"> <canvas id="' . $this->name . '2"  style= "width:1000px;' . $this->height . '" class="chartCanvas" ></canvas></div>';
   
    $this->getView()->registerJs('
        var ctx = document.getElementById(name).getContext("2d");
        var data = {
               labels: labels ,
               datasets: [
                   {
                       label: "My Second dataset",
                       fillColor: "rgba(151,187,205,0.2)",
                       strokeColor: "rgba(151,187,205,1)",
                       pointColor: "rgba(151,187,205,1)",
                       pointStrokeColor: "#fff",
                       pointHighlightFill: "#fff",
                       pointHighlightStroke: "rgba(151,187,205,1)",
                       data: datasets
                   }
               ]
           };

        var myLineChart = new Chart(ctx).Line(data,{animation : false,responsive: false, multiTooltipTemplate: "<%= chart %>",});'
            ,$position = 3);
    
    
    
     $this->getView()->registerJs('
        var ctx2 = document.getElementById(name+"2").getContext("2d");
        var data2 = {
               labels: labels ,
               datasets: [
                   {
                       label: "My Second dataset",
                       fillColor: "rgba(0,187,205,0.2)",
                       strokeColor: "rgba(0,187,205,1)",
                       pointColor: "rgba(0,187,205,1)",
                       pointStrokeColor: "#fff",
                       pointHighlightFill: "#fff",
                       pointHighlightStroke: "rgba(0,187,205,1)",
                       data: datasets
                   }
               ]
           };

        var myLineChart2 = new Chart(ctx2).Line(data2,{animation : false,responsive: false,});
        
             $("#MyChart").mousemove(function(evt){
    var activePoints = myLineChart.getPointsAtEvent(evt);
    // => activePoints is an array of points on the canvas that are at the same position as the click event.
    
    myLineChart2.showTooltip(activePoints);
    //console.log(activePoints);
    
});
               
            ',$position = 3);
    
    }
   

    public function globalConfig() {
        $string = '{';
        if ($this->global) {
            foreach ($this->global as $key => $value) {
                $string.=$key.':'.$value.',';
            }
        }
        return $string.'};';
    }

    public function chartsConfig() {
        $string = '{';
        if ($this->chartsConfig) {
            foreach ($this->chartsConfig as $key => $value) {

                $string.=$key . ':' . $value.',';
            }
        }
        return $string.'};';
    }

    public function setDatasets() {
        $data = 'var labels = '.$this->labels . ';var datasets = '.$this->datasets.';';
    
        return $data;
    }

    public function run() {
        
        
       
        parent::run();   
    }
    public function config() {
        $string = '';
        $string.="var name = '" . $this->name . "';";
        $string.="var type = '" . $this->type . "';";
        $string.="var chartGlobalConfig = ".$this->globalConfig().";";
        $string.="var option".$this->name." = ".$this->chartsConfig().";";
       // $string.=$this->setDatasets();
        return $string;
    }

}
