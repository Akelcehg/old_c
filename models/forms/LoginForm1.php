<?php

/**
 * LoginForm class.
 * LoginForm is the data structure for keeping
 * user login form data. It is used by the 'login' action of 'SiteController'.
 */
namespace app\models\forms;
use \yii\base\Model;
class LoginForm extends Model {
    public $email;
    public $password;
    public $rememberMe;
    public $secret_phrase;
    public $secrect_answer;
    public $errors;

    /**
     * Is accepted user agreements
     * @var boolean
     */
    public $isAcceptedUserAgreement = 0;

    private $_identity;

    /**
     * Declares the validation rules.
     * The rules state that email and password are required,
     * and password needs to be authenticated.
     */
    public function rules() {
        return array(
            // email and password are required
            array('email, password', 'required'),

            // delete space email and password
            array('email', 'filter', 'filter' => 'trim'),
            // array('password', 'filter', 'filter'=>'trim'),

            // only correct email is allowed
            // array('email', 'email', 'fullPattern' => '\b[A-Z0-9._%+-]+@[A-Z0-9.-]+\.[A-Z]{2,4}\b'),
            array('email', 'email'),
            // rememberMe needs to be a boolean
            array('rememberMe', 'boolean'),
            // password needs to be authenticated
            array('password', 'authenticate'),

            array('secrect_answer', 'required', 'on' => 'requireSecretQuestion'),
            array('secrect_answer', 'validateSecretParam', 'on' => 'requireSecretQuestion', 'skipOnError' => true),

            /**
             * This part is depricated and replaced to secret_answer
             * since 06.01.2014
             */
            array('secret_phrase', 'required', 'on' => 'requireSecretPhrase'),
            array('secret_phrase', 'validateSecretParam', 'on' => 'requireSecretPhrase'),

            array('isAcceptedUserAgreement', 'safe'),

        );
    }

    /**
     * Declares attribute labels.
     */
    public function attributeLabels() {
        return array(
            'email' => 'Email',
            'password' => 'Password',
            'rememberMe' => 'Remember me next time',
            'secret_phrase' => 'Secret Phrase',
            'secrect_answer' => 'Secret Answer',
        );
    }

    /**
     * Authenticates the password.
     * This is the 'authenticate' validator as declared in rules().
     */
    public function authenticate($attribute, $params) {
        if(!$this->hasErrors()) {
            $this->_identity = new UserIdentity($this->email, self::hashPass($this->password));
            $requirePasswordCheck = (($this->getScenario() == 'requireSecretQuestion')||($this->getScenario() == 'passwordExpired'));
            if(!$this->_identity->authenticate($requirePasswordCheck)) {
                if($this->_identity->errorCode == UserIdentity::ERROR_NOT_ACTIVATED) {
                    $user = User::model()->findByAttributes(array('email' => $this->email));
                    $this->addError('errors', 'You must activate your account before logging in. <a href="#" onClick="$(\'#resendFormUserId\').val('.$user->id.');$(\'#resendActivationEmail\').submit()" style="color: #C00 !important; text-decoration: underline; border-bottom: none; float: none;">Click here</a> if you did not receive the activation email.');
                } else if($this->_identity->errorCode == UserIdentity::ERROR_CLOSED) {
                    $this->addError('password', '');
                    $this->addError('email', '');
                    $this->addError('closed', 'Your account is closed. Use "contact us" to ask reactivation.');
                } else if($this->_identity->errorCode == UserIdentity::ERROR_WAITLISTED) {                    
                    $user = User::model()->findByAttributes(array('email' => $this->email));
                    $lng = LanguagesForUsersOnWaitingList::model()->find('language_id = ' . $user->mother_language_id);                    
                    if (!empty($lng)) {
                        $this->addError('password', '');
                        $this->addError('email', '');
                        $this->addError('closed', 'Your account is not active. Your selected language is in the waiting list.');
                    }
                } else if($this->_identity->errorCode == UserIdentity::ERROR_LOCKED) {
                    $this->addError('password', '');
                    $this->addError('email', '');
                    $this->addError('errors', 'Your account is locked after several sequential failed login attempts. Use "contact us" to ask the unlock.');

                    //Add a new parameter to hide the forgot password in login form
                    $this->addError('locked', true);

                } else {
                    //skip check password on requireSecretQuestion
                    if($this->getScenario() != 'requireSecretQuestion') {
                        $this->addError('password', '');
                        $this->addError('email', '');
                        $this->addError('errors', 'Incorrect email or password.');
                    }
                }
            }
        }
    }

    /**
     * Logs in the user using the given email and password in the model.
     * @param boolean $createLoginSession Use for check if login data corrects or no. Don't create a login session
     * @return boolean whether login is successful
     */
    public function login($createLoginSession = true) {
        if($this->_identity === null) {
            $this->_identity = new UserIdentity($this->email, $this->password);
            $requirePasswordCheck = (($this->getScenario() == 'requireSecretQuestion')||($this->getScenario() == 'passwordExpired'));
            $this->_identity->authenticate($requirePasswordCheck);
        }

        if($this->_identity->errorCode === UserIdentity::ERROR_NONE) {

            // Get session duration from config/main.php - session component
            $timeout = Yii::app()->session->timeout;

            // 30 days or 1 hour if inactive
            $duration = $this->rememberMe ? $timeout * 24 * 30 : $timeout;

            if($createLoginSession)
                Yii::app()->user->login($this->_identity, $duration);
            return true;
        } else
            return false;
    }

    /**
     * @param $sPass
     * @return string
     */
    public static function hashPass($sPass) {
        return sha1(trim($sPass));
    }

    /**
     * @param $sPass
     * @return string
     */    
    public static function cryptPass($sPass) {
        return password_hash($sPass, PASSWORD_BCRYPT);
    }


    /**
     * Validate secret params such as secret answer and secret phrase
     * @param type $attribute
     * @param type $params
     */
    public function validateSecretParam($attribute, $params) {
        if(!User::model()->findByAttributes(array('email' => $this->email, $attribute => $this->$attribute)) instanceof User)
            $this->addError($attribute, $this->getAttributeLabel($attribute) . ' is wrong');
    }
}
