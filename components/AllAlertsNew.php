<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of AllAlerts
 *
 * @author alks
 */

namespace app\components;

use \yii\base\Widget;
use yii\helpers\Html;
use Yii;

class AllAlertsNew extends Widget {

    //put your code here

    public $mode = 'line';
    public $alerts;
    public $type;

    public function init() {
        $this->type = Yii::$app->request->get('type', null);
        if (is_null($this->alerts)) {
            $this->alerts = new Alerts('');
        }

       

        switch ($this->mode) {
            case 'line':
                $this->lineMode();
                break;
            case 'tab':
                $this->tabMode();
                break;
            case 'header':
                $this->headerMode();
                break;
        }
    }

    private function tabMode() {
        $this->jsArr($this->alerts->getAllJsAlertsNew());
        $alerts = $this->alerts->getAllAlertsNew();
        

        echo '<form class="smart-form">';
        echo ' <div class="row" style="padding-bottom: 20px; padding-top: 10px;">';

        foreach ($alerts as $key => $value) {

            if ('leak' == $key) {
                echo'        <div class="col" style="padding-bottom: 5px;">
                                    <a href="javascript:;"  alerts_type="leak" class="alertsDrillDown smart-btn btn  alert_circle" rel="tooltip" title="Утечка" data-container="body">
                                        <i class="fa fa-warning"> Утечка</i>
                                    </a>
                                    <label id="leak_checkbox" style="width:41px" alerts_type="leak" alerts_id="0" class="alertsDrillDown smart-btn btn btn-danger alert_circle" rel="tooltip" title="Утечка" data-container="body">
                                    <span id="pendingAlerts2">' . $value . '</span>
                                    </label>
                            </div>';
            }

            if ('tamper' == $key) {
                echo' <div class="col" style="padding-bottom: 5px;">
                                    <a href="javascript:;"  alerts_type="tamper" class=" alertsDrillDown smart-btn btn  alert_circle" rel="tooltip" title="Вскрытие оборудования" data-container="body">
                                        <i class="fa fa-shield"> Вскрытие оборудования</i>
                                    </a>
                                    <label id="tamper_checkbox" style="width:41px" alerts_type="tamper" alerts_id="1" class="alertsDrillDown smart-btn btn btn-primary alert_circle" rel="tooltip" title="Вскрытие оборудования" data-container="body">
                                        <span id="missedAssetAlerts">' . $value . '</span>
                                    </label>
                        </div>';
            }

            if ('magnet' == $key) {

                echo'     <div class="col" style="padding-bottom: 5px;">
                                        <a href="javascript:;"  alerts_type="magnet" for="pendingAlerts_checkbox" class="alertsDrillDown smart-btn btn  alert_circle" rel="tooltip" title="Использование магнита" data-container="body">
                                            <i class="fa fa-pause"> Использование магнита</i>

                                            <input id="ytpendingAlerts_checkbox" type="hidden" value="0" name="PProject[pendingAlertsFilterAttr]"><input class="hidden" id="pendingAlerts_checkbox" name="PProject[pendingAlertsFilterAttr]" value="1" type="checkbox">
                                        </a>
                                        <label id="magnet_checkbox" style="width:41px" alerts_type="magnet" alerts_id="2" for="pendingAlerts_checkbox" class="alertsDrillDown smart-btn btn btn-warning alert_circle" rel="tooltip" title="Использование магнита" data-container="body">
                                            <span id="pendingAlerts">' . $value . '</span>
                                        </label>
                            </div>';
            }

            if ('lowBatteryLevel' == $key) {


                echo'         <div class="col" style="padding-bottom: 5px;">
                                        <a href="javascript:;"   alerts_type="lowBatteryLevel" for="missedAssetAlerts_checkbox" class="alertsDrillDownModem smart-btn   alert_circle" rel="tooltip" title="Низкий заряб батареи" data-container="body">
                                            <i class="fa fa-retweet"> Низкий заряд батареи</i>
                                            <input id="ytmissedAssetAlerts_checkbox" type="hidden" value="0" name="PProject[missedAssetAlertsFilterAttr]"><input class="hidden" id="missedAssetAlerts_checkbox" name="PProject[missedAssetAlertsFilterAttr]" value="1" type="checkbox">
                                        </a>
                                        <label id="lowBatteryLevel_checkbox" style="width:41px" alerts_type="lowBatteryLevel" alerts_id="3" for="missedAssetAlerts_checkbox" class="alertsDrillDownModem smart-btn  btn-success alert_circle" rel="tooltip" title="Низкий заряб батареи" data-container="body">
                                            <span id="pendingAlerts">' . $value . '</span>
                                        </label>
                                </div>';
            }

            if ('disconnect' == $key) {

                echo'         <div class="col" style="padding-bottom: 5px;">
                                        <a href="javascript:;"  alerts_type="disconnect" class="alertsDrillDown  smart-btn btn alert_circle " rel="tooltip" title="Обрыв связи" data-container="body">
                                            <i class="fa fa-times"> Обрыв связи</i>
                                        </a>
                                        <label id="disconnect_checkbox" style="width:41px" alerts_id="4" alerts_type="disconnect" class="alertsDrillDown smart-btn btn alert_circle bg-color-blueLight txt-color-white" rel="tooltip" title="Обрыв связи" data-container="body">
                                            <span id="missedAssetAlerts">' . $value . '</span>
                                        </label>
                                </div>';
            }
        }

        echo'    </div>
            </form>
                            <div id="logitSuda"></div>
                            ';
    }

    private function lineMode() {
        $alerts = $this->alerts->getAllAlertsNew();
      

        /*echo'<div class="row" style="padding-bottom: 20px; padding-top: 10px;">
                        <div class="pull-right">
                            <label class="control-label col" style="padding-top: 8px"><strong>Предупреждения</strong></label>
                            <div id="alert_container" class="btn-group col">';

        foreach ($alerts as $key => $value) {

            if ('leak' == $key) {

                echo'<label for="leak_checkbox" class="smart-btn btn btn-danger alert_circle" rel="tooltip" title="Утечка" data-container="body">
                                    <i class="fa fa-warning"></i> <span id="leak">' . $value . '</span>
                                    <input class="hidden" id="leak_checkbox" name="CounterAddressSearch[leak]" value="1" type="checkbox">
                             </label>';
            }

            if ('tamper' == $key) {
                echo'<label for="tamper_checkbox" class="smart-btn btn btn-primary alert_circle" rel="tooltip" title="Вскрытие оборудования" data-container="body">
                                        <i class="fa fa-shield"></i> <span id="tamper">' . $value . '</span>
                                        <input class="hidden" id="tamper_checkbox" name="CounterAddressSearch[tamper]" value="1" type="checkbox">
                                    </label>';
            }

            if ('magnet' == $key) {
                echo'<label for="magnet_checkbox"  class="smart-btn btn btn-warning alert_circle" rel="tooltip" title="Использование магнита" data-container="body">
                                    <i class="fa fa-pause"></i> <span id="magnet">' . $value . '</span>
                                    <input class="hidden" id="magnet_checkbox" name="CounterAddressSearch[magnet]" value="1" type="checkbox">
                                </label>';
            }

            if ('lowBatteryLevel' == $key) {
                echo' <label for="lowBatteryLevel_checkbox" class="smart-btn btn btn-success alert_circle" rel="tooltip" title="Низкий заряб батареи" data-container="body">
                                    <i class="fa fa-retweet"></i> <span id="lowBatteryLevel">' . $value . '</span>
                                    <input class="hidden" id="lowBatteryLevel_checkbox" name="CounterAddressSearch[lowBatteryLevel]" value="1" type="checkbox">
                                </label>';
            }

            if ('disconnect' == $key) {
                echo'  <label for="disconnect_checkbox" class="smart-btn btn alert_circle bg-color-blueLight txt-color-white" rel="tooltip" title="Обрыв связи" data-container="body">
                                    <i class="fa fa-times"></i> <span id="disconnect">' . $value . '</span>
                                    <input class="hidden" id="disconnect_checkbox" name="CounterAddressSearch[disconnect]" value="1" type="checkbox">
                                </label>';
            }
        }
        echo'           </div>
                        </div>
                    </div>';*/
    }
    
    private function headerMode() {
        $alerts = $this->alerts->getAllAlertsNew();
       

        echo'<div class="row" style="padding-bottom: 20px; padding-top: 10px;">
                        <div class="pull-right">
                            
                            <div id="alert_container" class="btn-group col">';

        foreach ($alerts as $key => $value) {

            if ('leak' == $key) {

                echo'<a href="/admin/counter/alerts?type='.$this->type.'&alerts=leak_checkbox"><label  class="smart-btn btn_header btn-danger alert_circle" rel="tooltip" title="Утечка" data-container="body">
                                    <i class="fa fa-warning"></i> <span id="leak">' . $value . '</span>
                                   
                             </label></a>';
            }

            if ('tamper' == $key) {
                echo'<a href="/admin/counter/alerts?type='.$this->type.'&alerts=tamper_checkbox"><label  class="smart-btn btn_header btn-primary alert_circle" rel="tooltip" title="Вскрытие оборудования" data-container="body">
                                        <i class="fa fa-shield"></i> <span id="tamper">' . $value . '</span>
                                        
                                    </label></a>';
            }

            if ('magnet' == $key) {
                echo'<a href="/admin/counter/alerts?type='.$this->type.'&alerts=magnet_checkbox"><label   class="smart-btn btn_header btn-warning alert_circle" rel="tooltip" title="Использование магнита" data-container="body">
                                    <i class="fa fa-pause"></i> <span id="magnet">' . $value . '</span>
                                   
                                </label></a>';
            }

            if ('lowBatteryLevel' == $key) {
                echo'<a href="/admin/counter/alerts?type='.$this->type.'&alerts=lowBatteryLevel_checkbox"><label  class="smart-btn btn_header btn-success alert_circle" rel="tooltip" title="Низкий заряб батареи" data-container="body">
                                    <i class="fa fa-retweet"></i> <span id="lowBatteryLevel">' . $value . '</span>
                                    
                                </label></a>';
            }

            if ('disconnect' == $key) {
                echo'<a href="/admin/counter/alerts?type='.$this->type.'&alerts=disconnect_checkbox"><label  class="smart-btn btn_header alert_circle bg-color-blueLight txt-color-white" rel="tooltip" title="Обрыв связи" data-container="body">
                                    <i class="fa fa-times"></i> <span id="disconnect">' . $value . '</span>
                                   
                                </label></a>';
            }
        }
        echo'           </div>
                        </div>
                    </div>';
    }

    private function jsArr($jsArr) {
        $string = '';
        foreach ($jsArr as $key => $value) {

            $string.= 'var ' . $key . 'A = [' . $value . '];';
        }
        $this->getView()->registerJs($string, $position = 3);
    }

}
