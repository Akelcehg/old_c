<?php

/**
 * Create a form model for payment system settings form.
 *
 * @author Jose Bayona <jose.b@scopicsoftware.com>
 */
class PaymentSettingsForm extends CFormModel {

    /**
     * Payment Method Paypal
     * @var int
     */
	public $payment_method_paypal;

    /**
     * Payment method skrill
     * @var int
     */
	public $payment_method_skrill;

    /**
     * Payment method wiretransfer
     * @var int
     */
	public $payment_method_wiretransfer;

    /**
     * Ability to generate invoice
     * @var int
     */
	public $ability_to_generate_invoice;

    /**
     * Company details
     * @var string
     */
    public $company_details;

    /**
     * Invoice logo path
     * @var string
     */    
    public $invoice_logo_path;

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'payment_method_paypal' => 'PayPal:',
            'payment_method_skrill' => 'Skrill:',
            'payment_method_wiretransfer' => 'Wiretransfer:',
            'ability_to_generate_invoice' => 'Ability to generate invoice:',
            'company_details' => 'Company details:',
            'invoice_logo_path' => 'Logo:',
        );
    }	


	public function __construct()
	{
		$optionGroup = Option::getOptionGroup('payment_settings');

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
            array('payment_method_paypal, payment_method_skrill, payment_method_wiretransfer, ability_to_generate_invoice, company_details, invoice_logo_path', 'safe')
            );
    }    

	public function saveOptions()
	{
		$optionGroup = Option::getOptionGroup('payment_settings');

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