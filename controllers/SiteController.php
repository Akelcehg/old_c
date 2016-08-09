<?php

namespace app\controllers;

use app\models\Language;
use Yii;
use yii\filters\AccessControl;
use yii\helpers\BaseUrl;
use yii\web\Controller;
use yii\filters\VerbFilter;
use app\models\forms\LoginForm;
use app\models\ContactForm;
use app\models\User;
use app\models\UserId;
use yii\rbac;
use app\models\MenuItem;
use yii\helpers\Json;
use yii\helpers\Html;
use Swift_Plugins_Loggers_ArrayLogger;
use Swift_Plugins_LoggerPlugin;
use app\components\Events;

class SiteController extends Controller
{
    public $layout = 'smartAdmin';

    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',

            ],
        ];


    }

    public function beforeAction($action)
    {


        $this->enableCsrfValidation = false;

        return parent::beforeAction($action);
    }

    /**
     * This is the default 'index' action that is invoked
     * when an action is not explicitly requested by users.
     */
    public function actionIndex()
    {
        $this->layout = 'site';
        echo $this->render('index');
    }

    public function actionRegistration()
    {
        $user = new User();

        $register = Yii::$app->request->post('User', false);
        $user->setScenario('registerUser');
        $user->setAttributes($register, false);

        if ($register and $user->validate()) {

            $code = md5(implode('', $register));
            $user->password = md5($register['password']);
            $user->status = 'WAITING_EMAIL_APPROVE';
            $user->confirm_code = $code;

            if ($user->save()) {

                $this->sendConfirmMail($register['email'], $code);
                echo $this->redirect(['/site/message', 'status' => 'confirm']);
            }else{
                $user->password = "";
            }
        }
        $this->layout = 'site';
        echo $this->render('registration', ['user' => $user]);
    }

    public function sendConfirmMail($email, $code)
    {

        $content = 'Чтобы активировать Ваш аккаунт, пожалуйста, используйте ссылку:';
        $content .= Html::a('Подтвердить регистрацию', Yii::$app->request->hostInfo . Yii::$app->urlManager->createUrl(['/site/confirm', 'code' => $code]));
        $content .= '. Вы получили это сообщение потому, что Ваш e-mail адрес был зарегистрирован на сайте ' . Yii::$app->request->hostInfo . ' Если вы не регистрировались на этом сайте, пожалуйста, проигнорируйте это письмо или сообщите администрации. ';
        $content .= '<br/><br/>
                С Уважением Система учета энергоресурсов Украины
                сл. технической поддержки (048) 7702487
                г.Одесса, Польская 18';

        $text = Html::tag('div', $content);
        $body = $text;
        $mailer = Yii::$app->mail;
        $mailer->compose()
            ->setTo($email)
            ->setFrom([Yii::$app->params['adminEmail']])
            ->setSubject('Подтверждение регистрации на ' . Yii::$app->request->hostInfo)
            ->setHtmlBody($body)
            ->send();

        $logger = new Swift_Plugins_Loggers_ArrayLogger();
        $message = $mailer->getSwiftMailer()->registerPlugin(new Swift_Plugins_LoggerPlugin($logger));
        if (!$message) {
            echo $logger->dump();
        }

    }


    public function sendConfirmedMail($user)
    {

        $content = 'Пользователь ' . $user->first_name . ' подтвердил свой почтовый ящик ';
        $content .= Html::a('редактировать профиль пользователя', Yii::$app->request->hostInfo . Yii::$app->urlManager->createUrl(["admin/users/edituser", 'id' => $user->id,]));
        $text = Html::tag('div', $content);
        $body = $text;
        $mailer = Yii::$app->mail;
        $mailer->compose()
            ->setTo('support@aser.com.ua')
            ->setFrom([Yii::$app->params['adminEmail']])
            ->setSubject('account confirm')
            ->setHtmlBody($body)
            ->send();

        $logger = new Swift_Plugins_Loggers_ArrayLogger();
        $message = $mailer->getSwiftMailer()->registerPlugin(new Swift_Plugins_LoggerPlugin($logger));
        if (!$message) {
            echo $logger->dump();
        }

    }

    public function actionConfirm()
    {

        $code = Yii::$app->request->get('code', 0);

        $user = User::find()->where(['confirm_code' => $code])->one();

        if (isset($user)) {

            if ($user->status != 'ACTIVE') {

                $user->status = 'DEACTIVATED';

            }
            $user->save();

            $this->sendConfirmedMail($user);
            $this->redirect(['/site/message', 'status' => 'succes']);
        } else {
            $this->redirect(['/site/message', 'status' => 'fail']);
        }
    }

    public function actionMessage()
    {
        $this->layout = 'site';
        $status = Yii::$app->request->get('status', false);


        if (isset($status)) {
            switch ($status) {

                case 'confirm':
                    $text = 'На ваш почтовый ящик выслан запрос на  подтверждение';
                    break;
                case 'succes':
                    $text = 'Email подтвержден , ждите активации профиля';
                    break;
                case 'fail':
                    $text = 'Ошибка подтверждения Email';
                    break;
            }
            $this->layout = 'site';
            echo $this->render('message', ['text' => $text]);
        }
        $this->redirect(Yii::$app->request->hostInfo);
    }

    public function actionLogin()
    {

        $model = new LoginForm;

        // collect user input data
        if (isset($_POST['LoginForm'])) {

            // validate user input and redirect to the previous page if valid
            if (User::find()->where(['email' => $_POST['LoginForm']['email'], 'password' => md5($_POST['LoginForm']['password']), 'status' => 'ACTIVE'])->one() and Yii::$app->user->login(User::findByUsername($_POST['LoginForm']['email']), 3600 * 24 * 30)) {

                //Yii::$app->session->set(User::ADMIN_USER_SESSION,1);
                Yii::$app->user->login(User::findByUsername($_POST['LoginForm']['email']), 60*20);

                $homepage = Yii::$app->getUrlManager()->createUrl(['admin/counter/index', 'type' => 'gas']);
                $roles = array_keys(\Yii::$app->authManager->getRolesByUser(Yii::$app->user->getId()));

                //@TODO - refactor on upper level

                $user=User::findOne(Yii::$app->user->id);
                $language=Language::find()->where(['id'=>$user->language_id])->one();
                    if(empty($language)){
                        $language=Language::getDefaultLang()->url;}
                else{
                    $language=$language->url;
                }

                $homePages = [
                    'admin' => Yii::$app->getUrlManager()->createUrl(['counter/search','lang'=>$language]),
                    'waterWatcher' => Yii::$app->getUrlManager()->createUrl(['counter/search','lang'=>$language]),
                    'gasWatcher' => Yii::$app->getUrlManager()->createUrl(['counter/search','lang'=>$language]),
                    'regionWatcher' => Yii::$app->getUrlManager()->createUrl(['counter/search','lang'=>$language]),
                    'PromAdmin' => Yii::$app->getUrlManager()->createUrl(['prom/correctors','lang'=>$language]),
                    'metrix'=>Yii::$app->getUrlManager()->createUrl(['metrix/counters/','lang'=>$language]),];

                foreach ($roles as $role) {
                    if (isset($homePages[$role])) {
                        $homepage = $homePages[$role];
                        break;
                    }
                }

                $currentUser = new  User();
                $events = new Events();
                $events->type = 'login';
                $events->region_id = $currentUser->getCurrentUserRegionId(Yii::$app->user->id);
                $events->model = User::findByUsername($_POST['LoginForm']['email']);
                ///$events->ip = Yii::$app->getRequest()->getUserIP();
                $events->AddEvent();

                if($currentUser->is('PromAdmin'))
                {
                    $this->redirect(Yii::$app->getUrlManager()->createUrl(['admin/default/correctors']));
                }

                if (\Yii::$app->mobileDetect->isMobile() && $currentUser->is('adjuster')) {
                    $this->redirect(BaseUrl::base() . '/mount');
                } else {
                    $this->redirect($homepage);
                }
                //$this->redirect(Yii::$app->getUrlManager()->createUrl('/'));
            } else {

                Yii::$app->session->addFlash('loginError','неправильный email или пароль',false);
                $this->redirect(Yii::$app->getUrlManager()->createUrl('/'));


            }
        } else {
            $this->redirect(Yii::$app->getUrlManager()->createUrl('/'));
        }
    }

    /**
     * Logs out the current user and redirect to homepage.
     */
    public function actionLogout()
    {


        Yii::$app->user->logout();

        Yii::$app->session->destroy();


        $this->redirect(Yii::$app->homeUrl);
    }

    public function actionErorooo()
    {


    }

}
