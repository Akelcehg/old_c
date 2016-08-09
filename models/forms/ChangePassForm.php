<?php
/**
 * User: Yuri Datsenko
 * Date: 01.11.13
 * Time: 15:38
 */
namespace app\models\forms;
class ChangePassForm extends \yii\base\Model
{
    public $password;
    public $password_repeat;

    public function rules() {
        return array(
            array('password, password_repeat', 'required'),
            array('password', 'string', 'min' => 8, 'message' => 'Password must be at least 8 characters long'),
            array('password_repeat', 'compare', 'compareAttribute' => 'password'),
            array('password', 'passwordHistoryValidate'),
        );
    }

    public function attributeLabels() {
        return array(
            'password' => 'Password',
            'password_repeat' => 'Password Repeat',
        );
    }

    public function passwordHistoryValidate($attr, $on) {
        if($errorMessage = User::passwordHistoryValidate($this->password))
            $this->addError('password', $errorMessage);
    }
}