<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "menu_items".
 *
 * @property integer $id
 * @property string $label
 * @property string $url
 * @property integer $owner_id
 * @property string $status
 * @property integer $level
 * @property integer $position
 * @property string $icon
 * @property integer $login_state_id
 * @property integer $new_window
 *
 * @property MenuItemsLoginStates $loginState
 */
class MenuItem extends \yii\db\ActiveRecord
{
    public $active;
    public $options=array();
     public $items;
    public $template;
    public $linkOptions;
    
    const TREE_TYPE_MENU = 'TREE_TYPE_MENU';
    const TREE_TYPE_DROPDOWN = 'TREE_TYPE_SELECT';
    
    /**
     * @inheritdoc
     */
    
    
    
    public static function tableName()
    {
        return 'menu_items';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            //[['owner_id', 'level', 'position', 'login_state_id', 'new_window'], 'integer'],
            [['level', 'position','menu_id'], 'integer'],
            [['status'], 'string'],
            [['label'], 'string', 'max' => 100],
            [['url'], 'string', 'max' => 512],
            [['icon'], 'string', 'max' => 30]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'label' => 'Заголовок',
            'url' => 'Url',
            'owner_id' => 'Owner ID',
            'status' => 'Статус',
            'level' => 'Уровень',
            'position' => 'Позиция',
            'icon' => 'Иконка',
            'login_state_id' => 'Login State ID',
            'new_window' => 'New Window',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLoginState()
    {
        return $this->hasOne(MenuItemsLoginStates::className(), ['id' => 'login_state_id']);
    }



    public function getMenuItemsAccess()
    {
        //return $this->hasMany('app\models\MenuItemsAccess', ['menu_id' => 'id'])->select('role_name')->all();

        $menuItemAccess=MenuItemsAccess::findAll(['menu_id'=>$this->id]);

        $roleArray=[];

        foreach($menuItemAccess as $oneRole)
        {
            $roleArray[]=$oneRole->role_name;
        }

        return $roleArray;
    }


       public function getMenuItemsLabel()
    {
        return $this->hasMany(MenusLabel::className(),['menu_item_id'=>'id']);
    }

    public function getI18nLabel($id)
    {
       $menusLabel=$this->hasOne(MenusLabel::className(),['menu_item_id'=>'id'])->where(['lang_id'=>$id])->one();
        if(empty($menusLabel)){
            return '';
        }else{return $menusLabel->label;}

    }

    public function getLabel()
    {


        $lang=Language::find()->where(['local'=>Yii::$app->language])->one();

        if(!empty($lang)){

        $label=$this->getI18nLabel($lang->id);

        if( $label!=''){

            return  $label;
        }else{
            return $this->label;
        }
        }else{
            return $this->label;
        }


    }

    
    
    
    public static function getMenusData($ownerId = 0,$menuId=null)
    {
        $result=[];
       $allKernelMenu=MenuItem::find()->where(['owner_id'=>'0'])->andFilterWhere(['menu_id'=>$menuId])->orderBy('position')->all();
       
       foreach ($allKernelMenu as $oneKernelMenu)
           {
               $allSubMenu=MenuItem::findAll(['owner_id'=>$oneKernelMenu->id]);
               
               $oneKernelMenu['linkOptions']='1';
               
               if($oneKernelMenu['url']=='')
                   {
                    $oneKernelMenu['url']='#';
                   }
               $oneKernelMenu['items']=$allSubMenu;
               
               
               $result[]=$oneKernelMenu;
           }
           return $result;
    }
    
    public static function getTree($type = self::TREE_TYPE_MENU, $ownerId = 0, $dropdownPrefix = '-', $filterRoleAccess = 0) {
        

        $menuId=Yii::$app->request->post('menu',null);
        $menus = MenuItem::find()
            ->where(['owner_id'=>$ownerId])
            ->andFilterWhere(['menu_id'=>$menuId])
            ->orderBy('position')->all();

        $items = array();
        if(empty($menus) and $ownerId==0){
            $items[] = array(
                'id' => '',
                'text' => 'empty',
                'url' => '',
                'icon' => '',
                'login_state_id' => '',
                'status' => '',
                'new_window' => '',
                'childs' => ''
            ); }
        foreach ($menus as $menu)
        {
            switch ($type) {
                // Using in dropdowns
                case self::TREE_TYPE_DROPDOWN : {
                    $prefix = '';
                    if ($menu->level > 1)
                        for ($i = 1; $i < $menu->level; $i++)
                            $prefix .= $dropdownPrefix;

                    $text = $prefix . $menu->label;

                    
                    
                 
                        $items[] = array(
                            'id' => $menu->id,
                            'text' => $text,
                            'url' => $menu->url,
                            'icon' => $menu->icon,
                            'login_state_id' => $menu->login_state_id,
                            'status' => $menu->status,
                            'new_window' => $menu->new_window,
                            'childs' => MenuItem::getTree($type, $menu->id, $dropdownPrefix, $filterRoleAccess)
                        );
                 
                }
            }
        }
        return $items;
    }
}
