<?php
namespace app\modules\admin\controllers;
use yii\data\ArrayDataProvider;
use yii\web\Controller;
use Yii;
use yii\helpers\Json;
use app\models\User;
use app\models\MenuItem;
use app\models\MenuItemsAccess;
use yii\filters\AccessControl;
use app\components\Events;

class MenusController extends Controller 

{
   // public $layout = 'smartAdmin';
    
    
    public function behaviors()
{
    return [
        'access' => [
            'class' => AccessControl::className(),
                    'rules' => 
                        [
                           [ 
                                'actions' => ['index','jstreeactions','menuaccess','deletemenujstree','add','edit'],
                                'allow' => true,
                                'roles' => ['admin'],
                            ],
                        ],
            
                    ],
             ];
}
     public function actionIndex()
            {
        


                $this->getView()->registerJsFile(Yii::$app->request->baseUrl .  '/js/menus/menus_base.js');

                $this->getView()->registerJsFile(Yii::$app->request->baseUrl .  '/js/jquery.ui.sortable.js');
                $this->getView()->registerJsFile(Yii::$app->request->baseUrl .  '/js/menus/jstree.js');
                $this->getView()->registerJsFile(Yii::$app->request->baseUrl .  '/js/menus/menus.js');

                // Load CSS files
                $this->getView()->registerCssFile(Yii::$app->request->baseUrl . '/css/menus/jstree.css');
                $this->getView()->registerCssFile(Yii::$app->request->baseUrl . '/css/menus/menus.css');


                if (isset($_GET['pageSize'])) {
                    Yii::$app->session->set('pageSize', (int) $_GET['pageSize']);
                    unset($_GET['pageSize']);
                }

                
                $menuList=  MenuItem::find()->where(['status'=>'ACTIVE'])->all();

                $dataProvider = new ArrayDataProvider([
                                                        'allModels' => $menuList,
                                                        'sort' => [
                                                            'attributes' => ['position'],
                                                        ],
                                                        'pagination' => [
                                                            'pageSize' => 25,
                                                        ],
                                                    ]);
                
                
                
                
                echo $this->render('index', array(
                    'pageTitle' => "Users",
                    'dataProvider' => $dataProvider,
                    'menuList'=>$menuList,
                ));
        
            }
            
    public function actionJstreeactions(){
		
		// Switch different actions
		switch($_POST['action']){
		
			// Getting all items to load the tree
			case "get_items":
				$menusTree = MenuItem::getTree(MenuItem::TREE_TYPE_DROPDOWN, 0, '');
                                //$menusTree = MenuItem::find()->where(['status'=>'ACTIVE'])->all();
				echo JSON::encode($menusTree);
				break;
				
			// Save items orders
			case "save_items_orders":
			
				// Decode items
				$items = json_decode($_POST['items']);

				// For each item
				foreach($items AS $k => $item){
				
					// If the parent is none - set it to 0
					if($item->parent_id == "none") $item->parent_id = "0";
                                        echo $item->item_id;
					// Update menu data
					$post = MenuItem::find()->where(['id'=>$item->item_id])->one();
					if($post){
						$post->owner_id = $item->parent_id;
						$post->position = $k+1;
						$post->level = $item->depth;
						$post->save();
						unset($post);
					}
				}
				
				// Show status message
				// Yii::app()->user->setFlash('categories', 'Category successfully ordered');
				
				// Return status
				echo "ok";
				break;
		}
	}
        
        // Delete menu
	public function actionDeletemenujstree(){
		
		// Delete current menu
		$menu = MenuItem::find()->where(['id'=>$_POST['idmenu_from']])->one();
                
        if (is_null($menu)) {
            echo JSON::encode(array(
                'status' => 'failure',
                'div' => 'Menu not found',
            ));
            Yii::$app->end();
        }
        
       /* $menu->setScenario("del");
        if (!$menu->validate()) {
            echo JSON::encode(array(
                'status' => 'failure',
                'div' => implode("<br/>", $this->errorsTransform($menu->getErrors())),
            ));
            Yii::$app->end();
        }*/
        
        MenuItem::find()->where(['id'=>$_POST['idmenu_from']])->one()->delete();
		
		//Yii::app()->user->setFlash('categories', 'Category successfully deleted');
        echo JSON::encode(array(
            'status' => 'success',
        ));
        Yii::$app->end();
	}
        
        public function actionMenuaccess()
	{
	    if(!Yii::$app->request->get('menuId'))
	        Yii::$app->end();
	        
	    $menuId = Yii::$app->request->get('menuId', 0);
	    $menuAccessPrivelegments = MenuItemsAccess::find()->where(['menu_id'=>$menuId])->all();

	    $access = array();
	    foreach($menuAccessPrivelegments as $menuAccessPrivelegment){
	        $access[] = $menuAccessPrivelegment->role_name;
	   }
	    
	    echo JSON::encode(array('access' => $access));
	     Yii::$app->end();
	}
        
        
        public function actionAdd() {
        //add translate to menu
        if (!Yii::$app->request->get('menuId') || !is_numeric(Yii::$app->request->get('menuId'))) {
            //new menu
            $menu = new MenuItem();
			
            //$menu->setScenario("add");
        } else {
            $menu = MenuItem::find()->where(['id'=>Yii::app()->request->getQuery('menuId')])->one();
            //$menu->setScenario("edit");
        }
            $menu = new MenuItem();
        if (Yii::$app->request->get('owner_id') != '' )
		{
			$menu["owner_id"] = Yii::$app->request->get('owner_id');
		}
		
        
        //$userRoles = Role::model()->findAll();
		//$userRoles = AdminGroups::model()->findAll();
		/*array (
			array('id' => 1, 'name' => 'Super Admin'),
			array('id' => 2, 'name' => 'Admin'),
			array('id' => 3, 'name' => 'Operations Admin'),
			array('id' => 4, 'name' => 'Finance Admin'),
			array('id' => 5, 'name' => 'Moderator'),
			array('id' => 6, 'name' => 'Client'),
			array('id' => 7, 'name' => 'Editor'),
		);
		$userRoles = Yii::app()->authManager->getAuthItems(2);*/

        if (Yii::$app->request->get('ajax') && Yii::$app->request->get('ajax') === 'menu-form') {
            echo ActiveForm::validate(array($menu));
            Yii::$app->end();
        }
		
		if (Yii::$app->request->post('MenuItem')) {
            
            $menu->attributes = Yii::$app->request->post('MenuItem');

			if ($menu->save()) {
				$menusAccess = Yii::$app->request->post('menusAccess', array());
                                foreach ($menusAccess as $menuAccess){


                                    $menuItemAccess = new MenuItemsAccess ();
                                    $menuItemAccess->role_name=$menuAccess;
                                    $menuItemAccess->menu_id=$menu->id;
                                    $menuItemAccess->save();

                                }      
				
				Yii::$app->session->setFlash('menus', 'Menu Added');
				if (Yii::$app->request->isAjax) 
				{
					echo JSON::encode(array(
						'status' => 'success',
						'div' => "Successfully added"
					));
					Yii::$app->end();
				} 
				else
				{
					$this->redirect(array('index'));
				}
			}
		}

        $responseArray = array(
            'menu' => $menu,
            'newRecord' => true,
            //'userRoles' => $userRoles,
        );
        if (Yii::$app->request->isAjax) {
            echo JSON::encode(array(
                'status' => 'failure',
                'div' => $this->render(
                        Yii::$app->request->get('parent') ? '_form_parent' : '_form', $responseArray, true
                )
            ));
            Yii::app()->end();
        }else
            echo $this->render(Yii::$app->request->get('parent') ? '_form_parent' : '_form', $responseArray);
    }
        
        
        
        public function actionEdit($menuId) {

        //if (!MenuItem::model()->editable()->hasMenu($menuId))
          //  return false;
            
        $menu = MenuItem::find()->where(['id'=>$menuId])->one();
        

        if (Yii::$app->request->post('MenuItem')) {
            
            //$menu->setScenario("edit");
            $currentUser = new  User();
            $menu->attributes = Yii::$app->request->post('MenuItem');
            $events = new Events();

            $events->oldAttributes = $menu->getOldAttributes();
            $events->region_id = $currentUser->getCurrentUserRegionId(Yii::$app->user->id);
            if ($menu->save()) {
                
                $events->newAttributes = $menu->getAttributes();
                $events->model = $menu;
                $events->type = 'edit';
               // $events->description = 'Редактирование Меню № пунка '.$menuId;
                $events->AddEvent();
                
                Yii::$app->session->setFlash('menus', 'Menu Edited');
                
                if (Yii::$app->request->isAjax) {
                    //change menu access
                    $menusAccess = Yii::$app->request->post('menusAccess', array());
                   // Yii::app()->user->setFlash('menus', 'Menus Access'.implode('|',$menusAccess));
                   
                  
                       MenuItemsAccess::deleteAll(['menu_id'=>$menuId]);
                       
                   
                    
                    foreach ($menusAccess as $menuAccess){
                        
                      
                        $menuItemAccess = new MenuItemsAccess ();
                        $menuItemAccess->role_name=$menuAccess;
                        $menuItemAccess->menu_id=$menuId;
                        $menuItemAccess->save();
                        
                    }
                    
                    //Events::AddEvent('edit','Редактирование Меню № пунка '.$menuId,$menu);
                    echo JSON::encode(array('status' => 'success', 'div' => "Successfully added"));
                    Yii::$app->end();
                } else
                    $this->redirect(array('index'));
            }
        }

        $menusTreeData = $this->_getMenus();

        $responseArray = array(
            'menu' => $menu,
            'menusTreeData' => $menusTreeData,
            'newRecord' => false
        );
        if (Yii::$app->request->isAjax) {
            echo JSON::encode(array(
                'status' => 'failure',
                'div' => $this->render('_form', $responseArray, true))
            );
            Yii::$app->end();
        } else {
            echo $this->render('_form', $responseArray);
        }
    }

    /**
     * Tree Of Cattegory For 
     * Current Language
     * @return array 
     */
    private function _getMenus() {
        $menusTree = MenuItem::getTree(TutorialCategory::TREE_TYPE_DROPDOWN);
        
        array_unshift($menusTree, array(
            'id' => 0,
            'text' => 'No Menu',
			'url' => 'No URL',
            'childs' => array(),
        ));

        return $menusTree;
    }
        
        
        public function beforeAction($action)
    {
     
        
            $this->enableCsrfValidation = false;
        
        return parent::beforeAction($action);
    } 
}