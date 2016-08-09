<?php

namespace app\models;

use Yii;
use yii\data\ActiveDataProvider;

/**
 * This is the model class for table "users".
 *
 * @property integer $id
 * @property string $email
 * @property string $password
 * @property string $first_name
 * @property string $last_name
 * @property string $nick_name
 * @property string $ip
 * @property string $reg_date
 * @property string $role
 * @property string $status
 */
class User extends \yii\db\ActiveRecord implements \yii\web\IdentityInterface
{
    public $password_repeat;
    public $authKey;
    public $accessToken;
    public $role;
    private static $users = [];


    const USER_TYPE_INDIVIDUAL = 'individual';
    const USER_TYPE_LEGAL_ENTITY = 'legal_entity';

    public static function getUserTypeList()
    {
        return [
            self::USER_TYPE_INDIVIDUAL => 'Физ.',
            self::USER_TYPE_LEGAL_ENTITY => 'Юр.',

        ];
    }

    public function getUserTypeText()
    {
        $userTypeList = self::getUserTypeList();
        if (isset($userTypeList[$this->user_type])) {
            return $userTypeList[$this->user_type];
        } else {
            return $this->user_type;
        }
    }

    /**
     * @inheritdoc
     */


    const STATUS_ACTIVE = 'ACTIVE';
    const STATUS_DEACTIVATED = 'DEACTIVATED';
    const STATUS_WAITING_EMAIL_APPROVE = 'WAITING_EMAIL_APPROVE';
    const STATUS_DELETED = 'DELETED';


    const ROLE_USER = '';


    /**
     *
     * Get user rolres
     * @author Igor
     */
    public static function getAllStatuses()
    {
        return [
            self::STATUS_ACTIVE => Yii::t('users','Active'),//'Активен',
            self::STATUS_DEACTIVATED => Yii::t('users','Deactivated'),//'Неактивен',
            self::STATUS_WAITING_EMAIL_APPROVE =>Yii::t('users','Waiting_email_approve'),// 'Подтверждение Email',
        ];
    }

    /**
     *
     * Get user status human name
     * @return string|false
     * @author Igor
     */
    public function getStatusName()
    {
        $statuses = self::getAllStatuses();

        if (isset($statuses[$this->status]))
            return $statuses[$this->status];
        else
            return false;
    }


    public static function tableName()
    {
        return 'users';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['email', 'password'], 'required', 'on' => 'login'],
            [['reg_date', 'nick_name', 'ip'], 'safe', 'on' => 'login'],
            [['status'], 'string', 'on' => 'login'],
            [['email', 'password'], 'string', 'max' => 100, 'on' => 'login'],
            [['password_repeat'], 'compare', 'compareAttribute' => 'password', 'on' => 'login'],
            [['first_name', 'last_name'], 'string', 'max' => 50, 'on' => 'login'],
            [['nick_name'], 'string', 'max' => 255, 'on' => 'login'],
            [['role'], 'string', 'max' => 30, 'on' => 'login'],
            [['email'], 'unique', 'on' => 'login'],

            [['first_name', 'last_name','password','password_repeat'], 'string', 'min' => 6, 'max' => 50, 'on' => 'registerUser'],
            [['email', 'password', 'address', 'legal_address', 'inn', 'facility','first_name'], 'required', 'on' => 'registerUser'],
            [['email'], 'email', 'on' => 'registerUser'],
            [['email'], 'unique', 'on' => 'registerUser'],
            [['password_repeat'], 'compare', 'compareAttribute' => 'password', 'on' => 'registerUser'],
            [['address', 'legal_address', 'inn', 'facility'], 'string', 'max' => 255, 'on' => 'registerUser'],
            [['nick_name'], 'string', 'max' => 255, 'on' => 'registerUser'],
            [['role'], 'string', 'max' => 30, 'on' => 'registerUser'],
            [['reg_date', 'nick_name', 'ip', 'role','geo_location_id','status'], 'safe', 'on' => 'registerUser'],

            [['email', 'password', 'address', 'legal_address', 'inn', 'facility'], 'required', 'on' => 'editUser'],
            [['email'], 'email', 'on' => 'editUser'],
            [['email'], 'unique', 'on' => 'editUser'],
            [['reg_date', 'nick_name', 'ip', 'role'], 'safe', 'on' => 'editUser'],
            [['password','password_repeat'], 'string', 'min' => 6, 'max' => 100, 'on' => 'editUser'],
            [['password_repeat'], 'compare', 'compareAttribute' => 'password', 'on' => 'editUser'],
            [['first_name', 'last_name'], 'string', 'min' => 6, 'max' => 50, 'on' => 'editUser'],
            [['address', 'legal_address', 'inn', 'facility'], 'string', 'max' => 255, 'on' => 'editUser'],
            [['nick_name'], 'string', 'max' => 255, 'on' => 'editUser'],
            [['role'], 'string', 'max' => 30, 'on' => 'editUser'],
            [['geo_location_id'],'isSetLocation', 'on' => 'editUser']

        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'address' => Yii::t('users','address'),//'Адресс',
            'inn' => Yii::t('users','inn'),// 'ИНН',
            'legal_address' => Yii::t('users','legal_address'),//'Юридический адресс',
            'email' => Yii::t('users','email'),//'Email',
            'password' =>Yii::t('users','password'),// 'Пароль',
            'password_repeat' => Yii::t('users','password_repeat'),//' повторите Пароль',
            'first_name' => Yii::t('users','first_name'),//''Имя',
            'last_name' =>  Yii::t('users','last_name'),//'Фамилия',
            'nick_name' => Yii::t('users','nick_name'),// 'Никнейм',
            'ip' => 'Ip',
            'reg_date' => Yii::t('users','reg_date'),// 'дата регистрации',
            'role' => Yii::t('users','role'),//'Роль',
            'status' => Yii::t('users','status'),//'Статус',
            'facility' =>Yii::t('users','facility'),// 'Предприятие',
            'geo_location_id'=>Yii::t('users','geo_location_id'),//'Город'
            'language_id'=>Yii::t('users','language_id'),
        ];
    }

    public function isSetLocation($attribute,$params)
    {
            if($this->geo_location_id>0)
            {
                return true;
            }
            else{
                $this->addError('geo_location_id', 'Город и Регион не выбраны');
                return false;
            }

    }

    public static function findIdentity($id)
    {

        self::$users = User::find()->where(['id' => $id])->one();

        return isset(self::$users) ? new static(self::$users) : null;
    }

    /**
     * @inheritdoc
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {

        return static::findOne(['token' => $token]);
    }

    /**
     * Finds user by username
     *
     * @param  string $email
     * @return static|null
     */
    public static function findByUsername2($email)
    {
        $user = '';
        $oneUser = User::find()->where(['email' => $email])->all();
        foreach ($oneUser as $oUser) {
            if (strcasecmp($oUser['email'], $email) === 0) {
                return new static($user);
            }
        }


        return null;
    }

    public static function findByUsername($email)
    {
        self::$users = User::find()->all();
        // $this->authKey=Yii::$app->getSecurity()->generateRandomKey();

        foreach (self::$users as $user) {

            if (strcasecmp($user['email'], $email) == 0) {
                return new static($user);
            }
        }

        return null;
    }

    /**
     * @inheritdoc
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @inheritdoc
     */
    public function getAuthKey()
    {
        return $this->authKey;
    }

    /**
     * @inheritdoc
     */
    public function validateAuthKey($authKey)
    {
        return $this->authKey === $authKey;
    }

    /**
     * Validates password
     *
     * @param  string $password password to validate
     * @return boolean if password provided is valid for current user
     */
    public function validatePassword($password)
    {
        return $this->password === $password;
    }

    public function getFullname()
    {
        return $this->first_name . ' ' . $this->last_name;
    }


    public static function is($role)
    {
        //Get role by name
        return array_key_exists($role, Yii::$app->authManager->getRolesByUser(Yii::$app->user->id));
    }


    public static function getRoleArray()
    {
        $userRoles = Yii::$app->authManager->getRolesByUser(Yii::$app->user->id);
        $roleArray = [];

        foreach ($userRoles as $key => $value) {
            $roleArray[] = $key;
        }
        return $roleArray;
    }

    public function getLastUserLogin() {

        $logoutObject=EventLog::find()->where(['user_id'=>$this->id])
            //->where(['user_id' => $this->id])
            ->where(['type'=>'logout'])
            ->orderBy(['created_at' => SORT_DESC])->one();

        echo $logoutObject->created_at;

        if($logoutObject) {


            $loginObject = EventLog::find()->where(['user_id'=>$this->id])
                //->where(['user_id' => $this->id])
                ->where(['type' => 'login'])
                ->andWhere(['>', 'created_at', $logoutObject->created_at])
                ->andFilterWhere(['<', 'created_at', 'DATE_SUB(NOW(), INTERVAL 20 MINUTE)'])
                ->orderBy(['created_at' => SORT_DESC])->one();

            if ($loginObject) {

                return $loginObject->created_at;

            } else {

                return false;

            }
        }else{
            return false;
        }

    }


    public function getCurrentUserRegionId($userId)
    {
        return User::findOne([
            'id' => $userId
        ])['geo_location_id'];
    }

    public function getLanguage()
    {
        return $this->hasOne(Language::className(),['id'=>'language_id']);
    }

}
