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

class ChartRealtime1 extends Widget {

    public $name = 'MyChart';
    public $type = 'Line';
    public $width = 1059;
    public $height = 400;
    public $labels = [];
    public $type1 = 'default';
    public $datasets = [];
    public $global = [];
    public $chartsConfig = [];

    public function init() {

        $this->getView()->registerJsFile('/js/eventCalendar/moment/moment.js');
        $this->getView()->registerJsFile('/js/eventCalendar/moment/ru.js');
        
        $this->getView()->registerJsFile(Yii::$app->request->baseUrl . '/js/chart/Chart.js',['position' => 1]);
        $this->getView()->registerJs($this->config(), $position = 1);


        echo '

            <header><h2 id="label'. $this->name .'"> </h2></header>

            <canvas id="' . $this->name . '" style="height:'.$this->height.';width:100%;" class="chartCanvas" ></canvas>

              ';
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
       // $string.=$this->setDatasets();
        return $string;
    }

}
