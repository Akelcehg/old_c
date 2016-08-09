<?php
/**
 * Created by PhpStorm.
 * User: alexey
 * Date: 13.07.16
 * Time: 11:16
 */

namespace app\components;

use app\models\Language;
use Yii;
use yii\base\Widget;
use yii\helpers\Html;

class LanguageWidget extends Widget
{


    public function init()
    {
    }

    public function run()
    {

        $current=Language::getCurrent();
        $langs= Language::find()->where(['enabled'=>1])->all();

        return $this->renderWidget( $current,$langs);

    }


    public function renderWidget($current, $langs)
    {
        ?>
        <div id="lang" style="float: left;margin-top: 20px ;">
                <?php foreach ($langs as $lang): ?>
                        <?= Html::a(Html::tag('img','',['class'=>'flag flag-'.$lang->icon]),Yii::$app->urlManager->createUrl([Yii::$app->request->url,'lang'=>$lang->url]),['style'=>'margin:5px;']) ?>
                <?php endforeach; ?>
        </div>
        <?php
    }


}