<?php

/**
 * LoginForm class.
 * LoginForm is the data structure for keeping
 * user login form data. It is used by the 'login' action of 'SiteController'.
 */

namespace app\models\forms;

use Yii;
use yii\base\Model;
use app\models\User;
class LoginForm extends Model
{
	
    
    public $email;
    public $password;
    public $rememberMe;
    private $_identity;

	/**
	 * Declares the validation rules.
	 * The rules state that username and password are required,
	 * and password needs to be authenticated.
	 */
	public function rules()
	{
		return array(
			// username and password are required
			array('email, password', 'required'),
			// rememberMe needs to be a boolean
			array('rememberMe', 'boolean'),
			// password needs to be authenticated
			
		);
	}

	/**
	 * Declares attribute labels.
	 */
	public function attributeLabels()
	{
		return array(
				'email'=>'Email',
				'password'=>' Пароль',
				'rememberMe'=>'Запомнить меня',
		);
	}

	/**
	 * Authenticates the password.
	 * This is the 'authenticate' validator as declared in rules().
	 */
	

	/**
	 * Logs in the user using the given username and password in the model.
	 * @return boolean whether login is successful
	 */
	public function login()
	{
	
		if(User::find()->where(['email'=>$this->email,'password'=>md5($this->password)])->one())
		{
			Yii::$app->session->set(User::ADMIN_USER_SESSION,1);
			return true;
		}
		else
			return false;
	}
}
