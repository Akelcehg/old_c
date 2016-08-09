<?php

/**
 * Create a form model for general system settings form.
 *
 * @author Jose Bayona <jose.b@scopicsoftware.com>
 */
class GeneralSettingsForm extends CFormModel {

    /**
     * Client name
     * @var string
     */
	public $client_name;

    /**
     * Client logo path
     * @var string
     */
	public $client_logo_path;

    /**
     * User registration enable option
     * @var int
     */
	public $user_registration;

    /**
     * Auto translate enable option
     * @var string
     */
	public $auto_translate;

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'client_name' => 'Client Name:',
            'client_logo_path' => 'Client Logo:',
            'user_registration' => 'User registration:',
            'auto_translate' => 'Auto Translate:',
        );
    }	


	public function __construct()
	{
		$optionGroup = Option::getOptionGroup('general_settings');

		$criteria = new CDbCriteria();
		$criteria->select = 't.name, t.value';
        $criteria->addInCondition('t.name', $optionGroup['options']);

        $options = Option::model()->findAll($criteria);

        foreach($options as $option) {
        	$option_name = $option->name;
        	$this->$option_name = $option->value;
        }
	}

    public function rules() 
    {
        return array(
            array('client_name', 'required', 'message' => 'Client name setting cannot be blank'),
            array('client_logo_path, user_registration, auto_translate', 'safe')
            );
    }    

	public function saveOptions()
	{
		$optionGroup = Option::getOptionGroup('general_settings');

		$criteria = new CDbCriteria();
		$criteria->select = 't.id, t.name, t.value';
        $criteria->addInCondition('t.name', $optionGroup['options']);

        $options = Option::model()->findAll($criteria);

        $saved = true;

        foreach($optionGroup['options'] as $name) {
        	foreach($options as $option) {
        		if($option->name == $name) {
        			$option->value = $this->$name;
        			if(!$option->save(false, array('value'))) $saved = false;
        		}
        	}
        	reset($options);
        }

        return $saved;
	}
}