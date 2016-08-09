<?php
namespace app\components;

use yii\helpers\Html;
use yii\jui\Tabs;

class CustomTabView extends Tabs{

    public $tabListId;
    public $extraHeader = '';
    public $containerClass = 'tab-content jarviswidget well';
    public $activeTab;
    public $tabs;
    
    public function init()
    {
        foreach($this->tabs as $id=>$tab)
            if(isset($tab['visible']) && $tab['visible']==false)
                unset($this->tabs[$id]);

        if(empty($this->tabs))
            return;

        if($this->activeTab===null || !isset($this->tabs[$this->activeTab]))
        {
            reset($this->tabs);
            list($this->activeTab, )=each($this->tabs);
        }


        //$this->getView()->registerJs('');
        echo Html::tag('div', $this->renderHeader().Html::tag('div',$this->renderBody(),['class'=> $this->containerClass]));
       /* echo Html::openTag('div')."\n";
        $this->renderHeader();
        echo Html::openTag('div', array('class'=> $this->containerClass));
        $this->renderBody();
        echo Html::closeTag('div');
        echo Html::closeTag('div');*/
    }

    /**
     * Renders the header part.
     */
    protected function renderHeader()
    {
        $result='';
        $result.=$this->extraHeader;
        $result.="<ul ".(isset($this->tabListId) ? "id=\"{$this->tabListId}\" " : '')."class=\"nav nav-tabs bordered\">\n";
        foreach($this->tabs as $id=>$tab)
        {
          
            $title=isset($tab['title'])?$tab['title']:'undefined';
            $active=$id===$this->activeTab?' class="active"' : '';
            $url=isset($tab['url'])?$tab['url']:"#{$id}";
            $result.="<li {$active}><a href=\"{$url}\" data-toggle=\"tab\">{$title}</a></li>\n";
        }
        $result.="</ul>\n";
        return $result;
    }


    /**
     * Renders the body part.
     */
    protected function renderBody()
    {
        $result='';
        foreach($this->tabs as $id=>$tab)
        {
            $inactive=$id!==$this->activeTab?'' : 'in active';
            $result.="<div class=\"tab-pane fade {$inactive}\" id=\"{$id}\">\n ";
            if(isset($tab['content']))
                $result.=$tab['content'];
            elseif(isset($tab['view']))
            {
                if(isset($tab['data']))
                {
                    if(is_array($this->viewData))
                        $data=array_merge($this->viewData, $tab['data']);
                    else
                        $data=$tab['data'];
                }
                else
                    $data=$this->viewData;
                
                $this->getController()->render($tab['view'], $data);
            }
           $result.="</div><!-- {$id} -->\n";
        }
        
         return $result;
    }
}