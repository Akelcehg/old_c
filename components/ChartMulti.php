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

class ChartMulti extends Widget {

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
    if( $this->type1=='dayChart'){
        $this->getView()->registerJsFile(Yii::$app->request->baseUrl . '/js/chart/Chart_yii_1.js');
    }
    else{
    $this->getView()->registerJsFile(Yii::$app->request->baseUrl . '/js/chart/Chart_yii_multi.js');}
        
        
    if( $this->type1=='dayChart'){
        
        echo'<p id="no-data" style=" position:absolute; top :250px;left:40%;font-size:20px"> Нет данных </p>';
        
        echo'<input id="today" type="hidden" counter_id="0" value="'.  date("Y-m-d").'">';
        echo'<input id="month" type="hidden" counter_id="0" value="'.  date("Y-m-d").'">';
        echo'<input id="week" type="hidden" counter_id="0" value="'.  date("Y-m-d").'">';
        
        echo'<div id="dayMode" style="clear:both;text-align:center;">'
        . '<span style="float:left;cursor: pointer;font-size:20px" id="prevDay">&nbsp;</span>'
        . '<span id="datelabel" style="font-size:20px; font-weight:bolder;">'.  date("Y-m-d").'</span>'
        . '<span style="float:right;width:200px">&nbsp;<span style="cursor: pointer;font-size:20px;" id="nextDay"></span></span>'
        . '</div>';
        
        echo'<div id="monthMode" style="clear:both;text-align:center;">'
        . '<span style="float:left;cursor: pointer;font-size:20px" id="prevMonth">&nbsp;</span>'
        . '<span id="monthlabel" style="font-size:20px; font-weight:bolder;">'.'plaseholder'.'</span>'
        . '<span style="float:right;width:145px">&nbsp;<span style="cursor: pointer;font-size:20px;width:143px" id="nextMonth"></span></span>'
        . '</div>';
        
         echo'<div id="weekMode" style="clear:both;text-align:center;">'
        . '<span style="float:left;cursor: pointer;font-size:20px" id="prevWeek">&nbsp;</span>'
        . '<span id="weeklabel" style="font-size:20px; font-weight:bolder;">'.'plaseholder'.'</span>'
        . '<span style="float:right;width:145px">&nbsp;<span style="cursor: pointer;font-size:20px;width:143px" id="nextWeek"></span></span>'
        . '</div>';
    }
        echo ' 
            <div width="100%" style="margin-left:10px;margin-right:10px;clear:both">
           <!-- <header><h2 id="label'. $this->name .'"> </h2></header>-->
            <div id="temp">    
                <p>Температура</p>
            <canvas id="' . $this->name . 'temp"  style= "width:' . $this->width . ';height:' . $this->height . '" class="chartCanvas" ></canvas>
            </div>
                <p>Расход</p>
            <canvas id="' . $this->name . '"  style= "width:' . $this->width . ';height:' . $this->height . '" class="chartCanvas" ></canvas>
               
            </div>';
        echo'<div id="consumptionDetail" style="clear:both;text-align:center;">'
        . '<span style="float:left;cursor: pointer;font-size:20px" id="begin">Начало периода:</span>'
        . '<span id="end" style="font-size:20px; ">Конец периода: </span>'
        . '<span style="float:right;width:200px">&nbsp;<span style="cursor: pointer;font-size:20px;font-weight: 800; " id="consumtionSumm"> Расход:</span></span>'
        . '</div>';
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
        $data = 'var data = {';
        $labels = '[';
        if ($this->labels) {
            foreach ($this->labels as $label) {
                $labels.=$label . ',';
            }
        }
        $data.='labels :'.$labels . ']';
        $datasets = '[';
        if ($this->datasets) {
            foreach ($this->datasets as $dataset) {
                $datasets.='{';
                foreach ($dataset as $key => $value) {
                    $datasets.=$key . ' :" ' . $value . '",';
                }
                $datasets.='}';
            }
        }
        return $data.=$datasets.']};';
    }

    public function config() {
        $string = '';
        $string.="var name = '" . $this->name . "';";
        $string.="var type = '" . $this->type . "';";
        $string.="var chartGlobalConfig = ".$this->globalConfig().";";
        $string.="var option".$this->name." = ".$this->chartsConfig().";";
        $string.="var option".$this->name."temp = ".$this->chartsConfig().";";
       // $string.=$this->setDatasets();
        return $string;
    }

}
