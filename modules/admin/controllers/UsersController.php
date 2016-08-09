<?php

namespace app\modules\admin\controllers;

use app\models\AlertsToUser;
use app\models\TelegramToUser;
use yii\data\ArrayDataProvider;
use yii\web\Controller;
use Yii;
use app\models\User;
use yii\filters\AccessControl;
use app\components\Events;
use yii\web\HttpException;

class UsersController extends Controller
{

    /**
     * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
     * using two-column layout. See 'protected/views/layouts/column2.php'.
     */
   // public $layout = 'smartAdmin';

    /**
     * Specifies the access control rules.
     * This method is used by the 'accessControl' filter.
     *
     * @see http://www.yiiframework.com/wiki/65/how-to-setup-rbac-with-a-php-file/
     * http://www.yiiframework.com/wiki/253/cphpauthmanager-how-it-works-and-when-to-use-it/
     *
     * @return array access control rules
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' =>
                    [
                        [
                            'actions' => ['index', 'adduser', 'edituser', 'deleteuser','deletetelegram','nouser'],
                            'allow' => true,
                            'roles' => ['superadmin'],
                        ],
                        [
                            'actions' => ['selfedit'],
                            'allow' => true,
                            'roles' => ['@'],
                        ],
                    ],
            ],
        ];
    }

    public function actionIndex()
    {

        if(! User::is('superadmin')){
            throw new HttpException(403);
        }



        if (isset($_GET['pageSize'])) {
            Yii::$app->session->set('pageSize', (int)$_GET['pageSize']);
            unset($_GET['pageSize']);
        }

        $userList = User::find()->all();

        $dataProvider = new ArrayDataProvider([
            'allModels' => $userList,
            'sort' => [
                'attributes' => ['id', 'username', 'email'],
            ],
            'pagination' => [
                'pageSize' => 25,
            ],
        ]);


        echo $this->render('index', array(
            'pageTitle' => "Users",
            'dataProvider' => $dataProvider,
            'userList' => $userList,
        ));
    }

    public function actionAdduser()
    {

        $user = new User();
        $userData = Yii::$app->request->post('User', False);

        if ($userData) {
            $user->attributes = $userData;
            $user->password = md5($userData['password']);
            $user->password_repeat = md5($userData['password_repeat']);
            if ($user->save()) {
                Yii::$app->authManager->assign(Yii::$app->authManager->getRole('user'), $user->id);
                return $this->redirect('index');
            }
        }
        echo $this->render('adduser', ['user' => $user]);
    }

    private function EditUser($id,$userData=[]){
        $passchanged=false;
        if ($user = User::find()->where(['id' => $id])->one()) {

            $user->setScenario("editUser");

            if (!empty($userData)) {
                if(User::is('superadmin')) {

                    $user->email = $userData['email'];
                    $user->geo_location_id=$userData['geo_location_id'];

                }
                $user->first_name = $userData['first_name'];

                $user->telegram_notification_enable = $userData['telegram_notification_enable'];
                $user->email_notification_enable = $userData['email_notification_enable'];

                $user->address = $userData['address'];
                $user->legal_address = $userData['legal_address'];
                $user->inn = $userData['inn'];
                $user->facility = $userData['facility'];
                $user->status = $userData['status'];

                $user->language_id=$userData['language_id'];

                if ($userData['password'] != $user->password) {

                    $user->password = md5($userData['password']);
                    $passchanged=true;
                }

                if (!empty($userData['role'])) {

                    Yii::$app->authManager->revokeAll($user->id);

                    foreach ($userData['role'] as $role) {
                        Yii::$app->authManager->assign(Yii::$app->authManager->getRole($role), $user->id);

                    }

                    //$user->role = implode(',', $userData['role']);
                }else{
                   if(User::is('superadmin')) {
                       Yii::$app->authManager->revokeAll($user->id);
                   }
                }

                $currentUser = new  User();
                $events = new Events();

                $events->region_id = $currentUser->getCurrentUserRegionId($user->id);
                $events->oldAttributes = $user->getOldAttributes();

                if ($user->save()) {

                    if($passchanged){
                        Yii::$app->session->setFlash('passchanged','Пароль изменен успешно');
                    }

                    $events->newAttributes = $user->getAttributes();
                    $events->model = $user;
                    $events->type = 'edit';
                    //$events->description = 'Редактирование Юзера №'.$id;
                    $events->AddEvent();
                    // Events::AddEvent('edit','Редактирование Юзера №'.$id,$user);

                    $alertsTypes = Yii::$app->request->post('alertsType');

                    if (Yii::$app->request->isPost) {
                        AlertsToUser::deleteAll(['user_id' => $user->id]);
                        if ($alertsTypes) {
                            foreach ($alertsTypes as $type) {
                                $alertsType = new AlertsToUser();
                                $alertsType->user_id = $user->id;
                                $alertsType->alerts_type_id = $type;
                                $alertsType->save();
                            }
                        }
                    }

                    if(User::is('superadmin')) {
                        return $this->redirect(Yii::$app->urlManager->createUrl(['/admin/users/edituser', 'id' => $user->id]));
                    }else{
                        return $this->redirect(Yii::$app->urlManager->createUrl(['/admin/users/selfedit']));
                    }
                }
            }

            $type = AlertsToUser::find()->where(['user_id' => $user->id])->all();
            $telegrams = TelegramToUser::find()->where(['user_id' => $user->id])->all();

            $userRoles=Yii::$app->authManager->getRolesByUser($user->id);
            $roleArray=[];

            foreach($userRoles as $key=>$value)
            {
                $roleArray[]=$key;
            }

            $user->role = $roleArray;
            echo $this->render('edituser', ['user' => $user, 'type' => $type, 'telegrams' => $telegrams]);
        } else  throw new HttpException(404, 'Пользователь не найден');
    }

    public function actionEdituser()
    {

        $userData = Yii::$app->request->post('User', FALSE);
        if (!($id = Yii::$app->request->get('id', FALSE))) {

            $id = $userData['id'];
        }

        $this->EditUser($id,$userData);

    }

    public function actionSelfedit()
    {
        $userData = Yii::$app->request->post('User', FALSE);
        $this->EditUser(Yii::$app->getUser()->id,$userData);
    }

    public function actionDeletetelegram($id, $user_id){
        if (TelegramToUser::deleteAll(['id'=>$id])) return $this->redirect('edituser?id='.$user_id);

    }

    public function actionDeleteuser($id)
    {

        $backUrl = Yii::$app->request->get('backUrl');
        User::find()->where(['id' => $id])->one()->delete();
        return $this->redirect('index');
    }

    public function actionNouser()
    {

        echo $this->render('nouser');

    }

}
