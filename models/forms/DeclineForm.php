<?php

/**
 * DeclineForm class.
 * DeclineForm is the data structure for keeping
 * user login form data. It is used for sending decline to admin mail in 'ProfileController'.
 */
class DeclineForm extends CFormModel
{
    const DECLINE_MAIL = 'simeon.m@scopicsoftware.com';
    
    public $paymentId;
    public $subject;
    public $detailDescription;

	/**
	 * Declares the validation rules.
	 * The rules state that email and password are required,
	 * and password needs to be authenticated.
	 */
	public function rules()
	{
		return array(
            array('subject, detailDescription', 'required'),
            array('paymentId', 'required', 'message' => '{attribute} cannot be blank. Please contact to administration'),
		);
	}

	/**
	 * Declares attribute labels.
	 */
	public function attributeLabels()
	{
		return array(
			'subject'=> 'Subject',
		    'detailDescription' => 'Detail Description',                      
		);
	}
	
	public function getMailFormText()
	{
	    $userJob = UserJob::model()->findByPk($this->paymentId);
	    
	    if(!is_null($userJob))
	        $user = $userJob->user;
	    
	    $text = '';
	    $text .= '<p><b>Name:</b>: '.$user->getFullWithMiddleName().'</p>';
	    $text .= '<p><b>User e-mail:</b>: '.$user->email.'</p>';
	    $text .= '<p><b>User ID:</b>: '.$user->id.'</p>';
	    $text .= '<p><b>Payment ID</b>: #'.$this->paymentId.'</p>';
	    $text .= '<p><b>Subject</b>: '.$this->subject.'</p>';
	    $text .= '<p><b>Message</b>:'.nl2br($this->detailDescription).'</p>';
	    return $text;
	}
}
