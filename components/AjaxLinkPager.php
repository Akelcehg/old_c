<?php
namespace app\components;
use \yii\widgets\LinkPager;
use yii\helpers\Html;
use Yii;
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of AjaxLinkPager
 *
 * @author alks
 */
class AjaxLinkPager extends LinkPager{
    
    public $parentId;
    public $data;
    
      protected function renderPageButton($label, $page, $class, $disabled, $active)
    {
          
          
        $options = ['class' => $class === '' ? null : $class];
        if ($active) {
            Html::addCssClass($options, $this->activePageCssClass);
        }
        if ($disabled) {
            Html::addCssClass($options, $this->disabledPageCssClass);

            return Html::tag('li', Html::tag('span', $label), $options);
        }
        $linkOptions = $this->linkOptions;
        $linkOptions['data-page'] = $page;
        $linkOptions['url'] =$this->pagination->createUrl($page);
        $linkOptions['onclick'] ='return false';
        $linkOptions['parentId']=$this->parentId;
        
        $linkOptions['class']='ajax-pager';
        
        
        
       
        return Html::tag('li', Html::a($label, '#', $linkOptions), $options);
    }
    
}
