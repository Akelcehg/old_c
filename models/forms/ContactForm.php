<?php

/**
 * ContactForm class.
 * ContactForm is the data structure for keeping
 * contact form data. It is used by the 'contact' action of 'SiteController'.
 */
class ContactForm extends CFormModel {

    public $phone = '(310) 400-5659';
    public $name;
    public $email = '';
    public $adress = '2001 Wilshire Blvd, #410<br>Santa Monica, CA 90403';
    public $userEmail;
    public $subject;
    public $body;
    public $verifyCode;

    /**
     * Declares the validation rules.
     */
    public function rules() {
        return array(
            array('name, email, userEmail, subject, body', 'default'),
            // name, email, subject and body are required
            array('userEmail, subject, body', 'required'),
            // email has to be a valid email address, NOT INPUTTING
            array('userEmail', 'email'),
            // verifyCode needs to be entered correctly, NOT USED
            // array('verifyCode', 'captcha', 'allowEmpty'=>!CCaptcha::checkRequirements()),
        );
    }

    /**
     * Declares customized attribute labels.
     * If not declared here, an attribute would have a label that is
     * the same as its name with the first letter in upper case.
     */
    public function attributeLabels() {
        return array(
            'verifyCode' => 'Verification Code',
            'userEmail' => 'Your Email'
        );
    }
    
    /**
     * Email Subject
     * @return string
     */
    public function getBody() {
        $subject = '<b>Email:</b> '.$this->userEmail;
        $subject .= '<br><br>'.$this->body;
        
        return $subject;
    }

}
