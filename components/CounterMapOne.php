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
use app\models\Language;
use \yii\base\Widget;
use yii\helpers\Html;
use Yii;

class CounterMapOne extends Widget{

    public $geo_location_id=0;
    public $width='100%';
    public $height='330px';

    public function init()
    {
        $lang=Language::getCurrent();

        if($lang->local=='en-EN'){
            $lang='en-US';
        }else{
            $lang=$lang->local;
        }

        $this->getView()->registerJs("var mapAddressId=".$this->geo_location_id.";",1);
        
        $this->getView()->registerJsFile("//api-maps.yandex.ru/2.1/?lang=".$lang,['position' => 1]);

        $this->getView()->registerJsFile(Yii::$app->request->baseUrl ."/js/counterMap/billboardmapinitone.js",['position' => 3]);


        echo ' <div id="map" style="width:'.$this->width.'; height:'.$this->height.'"></div>';

    }

}
