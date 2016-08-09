<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace app\assets;

use yii\web\AssetBundle;

class AdminAppAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [

        'css/smart/bootstrap.min.css',
        'css/bootstrap.min.css',
        'css/smart/font-awesome.min.css',
        'css/smart/smartadmin-production.css',
        'css/smart/smartadmin-production.min.css',
        'css/smart/smartadmin-skins.css',
        'css/smart/smartadmin-skins.min.css',
        'css/administration.css',
        'css/smart/smart-admin.css',
        '//fonts.googleapis.com/css?family=Open+Sans:400italic,700italic,300,400,700',
        'css/smart/footer.css',
        'css/smart/demo.css',
        'css/demo.min.css',
        'css/datePicker.css',
        'css/smart/custom-theme/jquery-ui-1.10.3.custom.css',
        '//cdnjs.cloudflare.com/ajax/libs/select2/4.0.0/css/select2.min.css',
        'css/site.css',
        
        
    ];
    public $js = [
        '//ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js',
        '//ajax.googleapis.com/ajax/libs/jqueryui/1.11.3/jquery-ui.min.js',
       'js/smart/bootstrap/bootstrap.min.js',


        'js/smart/jquery.cookie.js',
        'js/smart/smartwidgets/jarvis.widget.min.js',
        'js/smart/app.js',
        'js/jquery.activity-indicator.js',
        'js/main.js',
        '//cdnjs.cloudflare.com/ajax/libs/select2/4.0.0/js/select2.min.js'
        
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
    ];
    
   public $jsOptions = ['position' => \yii\web\View::POS_HEAD];
    
    
   
}