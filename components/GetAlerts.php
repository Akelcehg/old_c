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
use app\components\Alerts;
use Yii;

class GetAlerts extends Widget {

    //put your code here
    public $oneCounter='';

    public function init() {
        $alert=new Alerts('');
        
        $alerts = $alert->getAlertsNew($this->oneCounter);

        $return ='';
        
        foreach ($alerts as $key => $value) {

            if ('leak' == $key and $value == true) {
                
                $return.='   <div class="project-alerts-icon" title="Утечка">
                            <div class="alert-btn label-danger alert-project-note">
                                <i class="fa fa-warning"></i>
                            </div>
                        </div>';
                
            }

            if ('tamper' == $key and $value == true) {
                
                $return.='   <div class="project-alerts-icon" title="Вскрытие оборудования">
                            <div class="alert-btn label-info">
                                <i class="fa fa-shield"></i>
                            </div>
                        </div>';
                
            }

            if ('magnet' == $key and $value == true) {
                
                $return.='   <div class="project-alerts-icon" title="Использование магнита">
                            <div class="alert-btn label-warning">
                                <i class="fa fa-pause"></i>
                            </div>
                        </div>';
                
            }

            if ('lowBatteryLevel' == $key and $value == true) {
                
               $return.='   <div class="project-alerts-icon" title="Низкий заряб батареи">
                            <div class="alert-btn label-success">
                                <i class="fa fa-retweet"></i>
                            </div>
                        </div>';
                
            }

            if ('disconnect' == $key and $value == true) {
                
                $return.='   <div class="project-alerts-icon" title="Обрыв связи">
                            <div class="alert-btn bg-color-blueLight txt-color-white">
                                <i class="fa fa-times"></i>
                            </div>
                        </div>';
                
            }
        }
        
        echo $return;
       
    }

}
