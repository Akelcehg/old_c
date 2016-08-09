<?php
/**
 * Created by PhpStorm.
 * User: alexey
 * Date: 13.07.16
 * Time: 10:04
 */

namespace app\components;


use app\models\Language;
use app\models\User;
use Yii;
use yii\web\UrlManager;

class LanguageUrlManager extends UrlManager
{

    public function createUrl($params)
    {
        $language = Yii::$app->request->get('lang', false);
        if (isset($params['lang'])) {
            $language = $params['lang'];
        }

        if (!is_array($params) or stripos($params[0], "?")) {
            $params = $this->parseurl($params);
        }


        $params['lang'] = $language;
        return parent::createUrl($params);
    }


    private function parseurl($string)
    {

        $arr = [];
        if (is_array($string)) {
            $paramsArr = explode('?', $string[0]);
        } else {
            $paramsArr = explode('?', $string);
        }


        $url = $paramsArr[0];
        $variableStrings = [];

        if (isset($paramsArr[1])) {
            $variableStrings = explode('&', $paramsArr[1]);
        }


        $varArr = [];
        foreach ($variableStrings as $variableString) {
            $variableString = htmlspecialchars_decode($variableString);
            $va = explode('=', $variableString);
            if (count($va) > 1) {
                if ($va[0])
                    $varArr[$va[0]] = $va[1];
            }
        }

        $arr[0] = $url;
        $arr = array_merge($arr, $varArr);
        return $arr;

    }

}