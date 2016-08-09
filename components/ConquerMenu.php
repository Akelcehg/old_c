<?php

namespace app\components;

use app\models\User;
use yii\widgets\Menu;
use yii\helpers\Html;
use Yii;
use app\models\MenuItem;

class ConquerMenu extends Menu {

    public $linkLabelWrapper;
    public $linkLabelWrapperHtmlOptions;
    public $items=false;
    public $alias=null;

    public function init() {

        if(!$this->items){

            $menus=\app\models\Menu::find()->filterWhere(['alias'=>$this->alias])->one();
            if(!empty($menus)) {
                $this->items = MenuItem::getMenusData(0, $menus->id);
            }
        }


        echo $this->renderMenuRecursive($this->items, 0);
    }

    /**
     * Recursively renders the menu items.
     * @param array $items the menu items to be rendered recursively
     */
    protected function checkAccess($item)
    {

        foreach(User::getRoleArray() as $role) {
            if (in_array($role, $item->getMenuItemsAccess())) {
                return true;
            }
        }
        return false;
    }

    protected function renderMenuRecursive($items, $level = 0) {
        $result = '';
        $count = 0;
        $n = count($items);
        foreach ($items as $item) {





            if ($this->checkAccess($item)) {

                $count++;

                $options = array();

                $class = array();
                if (!empty($item['items'])) {
                    $class[] = 'has-sub';
                    if (empty($item['url']) || $item['url'][0] == '#') {
                        $item['url'] = '#';
                    }
                }
                if (!empty($item['active'])) {
                    $class[] = 'active';
                }

                if (!empty($class)) {
                    if (empty($options['class']))
                        $options['class'] = implode(' ', $class);
                    else
                        $options['class'].=' ' . implode(' ', $class);
                }





                $menu = $this->renderMenuItem($item, $level, !empty($item['items']) ? count($item['items']) : 0, null, null, !empty($item['counter']) ? $item['counter'] : 0, (!empty($item['new_window'])) ? $item['new_window'] : 0);



                if (count($item['items']) > 0) {

                    if (!empty($item['active'])) {
                        if (!isset($item['submenuOptions']))
                            $item['submenuOptions'] = array();
                        $item['submenuOptions']['style'] = 'display:block';
                    }
                    $submenu = $this->renderMenuRecursive2($item['items'], $level + 1);
                    $menu.="\n" . Html::tag('ul', $submenu, ['style' => 'display:block;']) . "\n";

                    $menu.="\n";
                }



                $result.=Html::tag('li', $menu, ['id'=>'menu-'.$item->id,'class' => 'has-sub']);
            }
        }
        return Html::tag('ul', $result, ['id' => 'yw1']) . "\n";
    }

    protected function renderMenuRecursive2($items, $level = 0) {
        $result = '';
        $count = 0;
        $n = count($items);
        foreach ($items as $item) {
            if ($this->checkAccess($item)) {
                $count++;

                $options = array();

                $class = array();
                if (!empty($item['items'])) {
                    $class[] = 'has-sub';
                    if (empty($item['url']) || $item['url'][0] == '#') {
                        $item['url'] = '#';
                    }
                }
                if (!empty($item['active'])) {
                    $class[] = 'active';
                }

                if (!empty($class)) {
                    if (empty($options['class']))
                        $options['class'] = implode(' ', $class);
                    else
                        $options['class'] .= ' ' . implode(' ', $class);
                }


                $menu = $this->renderMenuItem($item, $level, !empty($item['items']) ? count($item['items']) : 0, null, null, !empty($item['counter']) ? $item['counter'] : 0, (!empty($item['new_window'])) ? $item['new_window'] : 0);


                if (count($item['items']) > 0) {

                    if (!empty($item['active'])) {
                        if (!isset($item['submenuOptions']))
                            $item['submenuOptions'] = array();
                        $item['submenuOptions']['style'] = 'display:block';
                    }
                    $submenu = $this->renderMenuRecursive($item['items'], $level + 1);
                    $menu .= "\n" . Html::tag('ul', $submenu, ['style' => 'display:none;']) . "\n";

                    $menu .= "\n";
                }


                $result .= Html::tag('li', $menu, ['id'=>'menu-'.$item->id,'class' => 'has-sub']);
            }
        }
        return $result;
    }

    protected function renderMenuItem($item, $level = 0, $count = 0, $linkLabelWrapper = null, $linkLabelWrapperHtmlOptions = null, $counter = null, $new_window = 0) {

        if (isset($item['url'])) {
            $extForLabel = '';
            $extForLabel2 = '';
            if (empty($level)) {
                $linkLabelWrapper = $this->linkLabelWrapper;
                $linkLabelWrapperHtmlOptions = $this->linkLabelWrapperHtmlOptions;
                $icon = !empty($item['icon']) ? $item['icon'] : 'fa-table';
                $extForLabel = "<i class='fa fa-lg fa-fw $icon'></i>";

                if ($counter)
                    $extForLabel2 = '<span class="badge pull-right inbox-badge">' . $counter . '</span>';
//				if ($count>0){
//					$extForLabel2 = '<span class="arrow"></span>';
//				}
            }
            if (!isset($item['linkOptions'])) {
                $item['linkOptions'] = array();
            }
            if ($new_window > 0) {
                //array_push($item['linkOptions'],['target' =>'_blank']);
            }
            //$item['linkOptions']['onclick'] = 'if (this.href != "#") {window.location = this.href;return false;}';

            if ($linkLabelWrapper === null) {
                $label = $item->getLabel();
            } else {
                $label = Html::tag($linkLabelWrapper, $item->getLabel(), $linkLabelWrapperHtmlOptions);
            }
            
            if($item['url']=='#')
                {
                    $linkOptions=['onclick'=>'return false;'];
                }else
                {
                   $linkOptions =$item['linkOptions'];
                }
            $activeBar = "";
            if($item['owner_id']==0){
                //print_r($_SERVER);
                //echo Yii::$app->urlManager->createUrl([$_SERVER['REQUEST_URI']]);
                $menuItem=MenuItem::findOne(['url'=>Yii::$app->urlManager->createUrl([$_SERVER['REQUEST_URI']])]);
                if($menuItem) {
                    if ($menuItem->owner_id == $item['id'] or $menuItem->id == $item['id']) {
                        $activeBar = "<div class='activeMenu'>&nbsp;</div>";
                    }
                    else{
                        $activeBar = "<div class='nonActiveMenu'>&nbsp;</div>";
                    }
                }
            }



            return Html::a($extForLabel . $label . $extForLabel2.$activeBar, Yii::$app->urlManager->createUrl($item['url']), $linkOptions);
        } else
            return Html::tag('p', $item->getLabel(), isset($item['linkOptions']) ? $item['linkOptions'] : array());
    }

    protected function isItemActive($item, $route = 0) {

        if (!empty($item['url'][0])) {
            $url = trim($item['url'][0], '/') . '/';
        } else {
            $url = '';
        }
        $routeTemp = trim($route, '/') . '/';


        if (isset($item['url']) && is_array($item['url']) && stripos($routeTemp, $url) !== false) {

            unset($item['url']['#']);
            if (count($item['url']) > 1) {
                foreach (array_splice($item['url'], 1) as $name => $value) {
                    if (!isset($_GET[$name]) || $_GET[$name] != $value)
                        return false;
                }
            }
            Yii::$app->params['activeMenu'] = $item['label'];
            return true;
        }
        return false;
    }

}
