<?php

/**
 * Recovery class.
 * LoginForm is the data structure for keeping
 * user login form data. It is used by the 'login' action of 'SiteController'.
 */
class RecoveryForm extends CFormModel {

    public $username;

    /**
     * Declares the validation rules.
     * The rules state that username and password are required,
     * and password needs to be authenticated.
     */
    public function rules() {
        return array(
            // username is required
            array('username', 'email'),
            array('username', 'required'),
            array('username', 'exist', 'attributeName' => 'email'),
            array('username', 'isLocked', 'attributeName' => 'email'),
        );
    }

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'users';
    }

    /**
     * Declares attribute labels.
     */
    public function attributeLabels() {
        return array(
            'username' => 'Email',
        );
    }

    /**
     * Exist database check rule
     * @param string $attribute
     * @param array $params
     */
    public function exist($attribute, $params){
        if(is_null(User::model()->findByAttributes(array(
          $params['attributeName'] => $this->$attribute
        )))){
           $this->addError($attribute, 'The email you entered doesn\'t exist in database');
        }
    }

    /**
    * Verify if the account is unlocked
    */
    public function isLocked($attribute, $params) {
        $user = User::model()->findByAttributes(array($params['attributeName'] => $this->$attribute));
        if(!empty($user->is_locked)) 
            $this->addError($attribute, 'Your account is locked after several sequential failed login attempts. Use "contact" to ask the unlock');
    }
}