<?php
namespace app\components;
use app\components\Events\Events;
use app\components\Events\UserTracking;
use app\models\Language;
use app\models\User;
use Yii;

/**
 * Class will run anytime before any action in system will start
 * @package app\components
 */
class GlobalControllerComponent extends \yii\base\Component{
    public function init() {



        date_default_timezone_set("Europe/Kiev");

        $ut=new UserTracking();
        $ut->id=Yii::$app->user->id;
        $ut->getUserAction();

        $language=Yii::$app->request->get('lang',false);
        if($language){
            Language::setCurrent($language);
        }else{
            $user=User::findOne(Yii::$app->user->id);
            if(!empty($user)and isset($user->language)) {
                Language::setCurrent($user->language->url);
            }else{

                $language=Language::getDefaultLang();
                Language::setCurrent($language->url);

            }
        }

        $lang=Language::getCurrent();
        \Yii::$app->view->registerJs("var app = ".json_encode([
                'baseUrl' => \Yii::$app->getRequest()->getBaseUrl(),
            ]).";

        var curLocale = '" . $lang->local ."';
        var curLang = '" . $lang->url ."';

        $(document).ready(function(){
            pageSetUp();
        });
        ", \yii\web\View::POS_END, 'app');

    }
}