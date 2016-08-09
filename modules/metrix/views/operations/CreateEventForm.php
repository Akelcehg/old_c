<?php
/**
 * Created by PhpStorm.
 * User: alexey
 * Date: 23.05.16
 * Time: 16:52
 */
use yii\helpers\Html;

$content=Html::tag('tr',Html::tag('td','Дата').Html::textInput('','',['id'=>'eventdate']));
$content.=Html::tag('tr',Html::tag('td','Время').Html::textInput('','',['id'=>'eventtime']));
$content.=Html::tag('tr',Html::tag('td','Время').Html::dropDownList('',[],['open','close'],['id'=>'eventtype']));
$content.=Html::tag('tr',Html::submitInput('Cоздать',['id'=>'submit']));


echo \yii\helpers\Html::tag('table',$content);