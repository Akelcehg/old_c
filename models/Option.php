<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "options".
 *
 * @property integer $id
 * @property string $label
 * @property string $name
 * @property string $value
 * @property string $input_type
 */
class Option extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    
    const WRONG_PASS_ATTEMPTS = 'wrong_pass_attempts';
    const WRONG_SECRET_PHRASE_ATTEMPTS = 'wrong_secret_phrase_attempts';
    const WRONG_SECRET_QUESTION_ATTEMPTS = 'wrong_secret_question_attempts';
    const ENABLE_SECRET_PHRASE_PROTECTION = 'enable_secret_phrase_protection';
    const ENABLE_SECRET_QUESTION_PROTECTION = 'enable_secret_question_protection';
    const ENABLE_ACCOUNT_LOCK = 'enable_account_lock';

    const USER_ACTIVITY_PERIOD = 'user_activity_period';
    const MAX_TERM_JOB_DUE_DATE_EMAIL_REMINDER = 'max_term_job_due_date_reminder';

    const ENABLE_EXPIRED_PASSWORD_PROTECTION = 'enable_expired_password_protection';

    const CLIENT_LOGO_PATH = 'client_logo_path';
    const CLIENT_NAME = 'client_name';
    const CLIENT_OPERATION_MAIL = 'client_operation_mail';
    const CLIENT_SUPPORT_MAIL = 'client_support_mail';

    const USER_REGISTRATION = 'user_registration';
    const AUTO_TRANSLATE = 'auto_translate';

    const PAYMENT_METHOD_PAYPAL = 'payment_method_paypal';
    const PAYMENT_METHOD_SKRILL = 'payment_method_skrill';
    const PAYMENT_METHOD_WIRETRANSFER = 'payment_method_wiretransfer';
    const ABILITY_TO_GENERATE_INVOICE = 'ability_to_generate_invoice';
    const COMPANY_DETAILS = 'company_details';
    const INVOICE_LOGO_PATH = 'invoice_logo_path'; 
    
    /**
     * Get group list with options
     * @author Igor S <igor.s@scopicsoftware.com>
     *
     * @return array
     */
    public static function getOptionGroups(){
        return [
            'user_activity_period' => [
                'label' => 'User Activity Period',
                'options' => [
                    Option::USER_ACTIVITY_PERIOD,
                    Option::MAX_TERM_JOB_DUE_DATE_EMAIL_REMINDER
                ]
            ],

            'general_cms' => [
                'label' => 'General CMS',
                'options' => [
                    Option::CLIENT_LOGO_PATH,
                    Option::CLIENT_NAME,
                    Option::CLIENT_OPERATION_MAIL,
                    Option::CLIENT_SUPPORT_MAIL,
                ]
            ],

            'general_settings' => [
                'label' => 'General Settings',
                'options' => [
                    Option::CLIENT_NAME,
                    Option::CLIENT_LOGO_PATH,
                    Option::USER_REGISTRATION,
                    Option::AUTO_TRANSLATE,
                ]
            ],            

            'payment_settings' => [
                'label' => 'Payment Settings',
                'options' => [
                    Option::PAYMENT_METHOD_PAYPAL,
                    Option::PAYMENT_METHOD_SKRILL,
                    Option::PAYMENT_METHOD_WIRETRANSFER,
                    Option::ABILITY_TO_GENERATE_INVOICE,
                    Option::COMPANY_DETAILS,
                    Option::INVOICE_LOGO_PATH,
                ]
            ],
        ];
    }

    /**
     * Get option group by name
     * @author Igor S <igor.s@scopicsoftware.com>
     *
     * @param $groupName
     * @return array|false
     */
    public static function getOptionGroup($groupName){
        $groups = self::getOptionGroups();

        return isset($groups[$groupName])? $groups[$groupName] : false;
    }

    /**
     * Get client logo Uri
     * @author Igor S <igor.s@scopicsoftware.com>
     *
     * @return string
     */
    public static function getClientLogoUri() {
        return Yii::$app->s3->assetsPath . Yii::$app->s3->s3_client_logo_path . '/';
    }
    
    public function checkNumber($attribute, $params) {

        if($this->attributes['name'] == "subtitle_vertical_offset" ||
            $this->attributes['name'] == "subtitle_horizontal_offset"
        )
            return;

        if(!is_numeric($this->value)) {
            $this->addError($attribute, 'The value must be numeric');
        } else if($this->value < 1) {
            $this->addError($attribute, 'The value must be positive number');
        } else if(($this->attributes['name'] == "conform_grade" || $this->attributes['name'] == "conform_grade_english") && $this->value > 84) {
            $this->addError($attribute, 'This needs to be value between 0-84');
        }
    }
    
     public static function getByName($name) {
       
        $data = Option::find()->where(['name'=>$name])->select('value')->one();

        if($data instanceof Option)
            return $data->value;
        else
            return false;
    }
    
    public static function tableName()
    {
        return 'options';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['label', 'name', 'value'], 'required'],
            [['label', 'name'], 'string', 'max' => 100],
            [['value'], 'string', 'max' => 255],
            [['input_type'], 'string', 'max' => 30]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'label' => 'Label',
            'name' => 'Name',
            'value' => 'Value',
            'input_type' => 'Input Type',
        ];
    }
    
    
    
}
