<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of CounterMap
 *
 * @author alks
 */

namespace app\components;
use \yii\base\Widget;
use yii\helpers\Html;
use Yii;

class CounterMap extends Widget{
public $type="list";
public $ajaxEnabled=true;

public $width='100%';
public $height='330px';

    public function init()
    {       
            

        
       /* $this->getView()->registerJsFile("http://api-maps.yandex.ru/2.0-stable/?load=package.standard,package.geoObjects&lang=ru-RU");
        $this->getView()->registerJsFile("/js/counterMap/billboardmapinit.js");
        $this->getView()->registerJsFile("/js/counterMap/loadallbillboard.js");

        echo  ' <div id="map" style="width: 750px; height: 300px"></div>';*/
        
        $this->getView()->registerJs("var mapAjaxEnabled=".$this->ajaxEnabled,1);
        
        $this->getView()->registerJsFile("//api-maps.yandex.ru/2.1/?lang=ru_RU",['position' => 1]);
        //$this->getView()->registerJsFile("http://api-maps.yandex.ru/2.0-stable/?load=package.standard,package.geoObjects&lang=ru-RU",['position' => 1]);
       
        if($this->type=="add" or $this->type=="addaddress" ){
             $this->getView()->registerJsFile(Yii::$app->request->baseUrl ."/js/counterMap/billboardmapaddinit.js",['position' => 2]);
        }else{
             $this->getView()->registerJsFile(Yii::$app->request->baseUrl ."/js/counterMap/billboardmapinit.js",['position' => 2]);
        }
        
// $this->getView()->registerJsFile(Yii::$app->request->baseUrl ."/js/counterMap/loadallbillboard.js");


//        $response =  ' <div id="map" style="width:100%; height:330px"></div>';
//        if($this->type=="add"){
//
//            $response .= Html::input('text', 'lat', '', ['id'=>'lat']);
//            $response .= Html::input('text', 'long', '', ['id'=>'long']);
//
//        }

        echo ' <div id="map" style="width:'.$this->width.'; height:'.$this->height.'"></div>';

        if($this->type=="add"){
            echo Html::input('text', 'lat', '', ['id'=>'lat']);
            echo Html::input('text', 'long', '', ['id'=>'long']);
        }



         
    }

}
