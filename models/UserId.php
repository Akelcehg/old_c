<?php

namespace app\models;
use Yii;

class UserId extends \yii\base\Object implements \yii\web\IdentityInterface
{
    public $id;
    public $email;
    public $password;
    public $first_name;
    public $last_name;
    public $nick_name;
    public $ip;
    public $reg_date;
    public $role;
    public $status;
    public $authKey;
    public $accessToken;

    private static $users = [];
    
   
    
    

    /**
     * @inheritdoc
     */
    public static function findIdentity($id)
    {
         self::$users=User::find($id)->one();
        return isset(self::$users) ? new static(self::$users) : null;
    }

    /**
     * @inheritdoc
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        foreach (self::$users as $user) {
            if ($user['accessToken'] === $token) {
                return new static($user);
            }
        }

        return null;
    }

    /**
     * Finds user by username
     *
     * @param  string      $email
     * @return static|null
     */
    public static function findByUsername2($email)
    {   $user='';
        $oneUser=User::find()->where(['email'=>$email])->all();
        foreach ($oneUser as $oUser){
            if (strcasecmp($oUser['email'], $email) === 0) {
                return new static($user);
            }
        }
        

        return null;
    }
    
        public static function findByUsername($email)
    {      
            self::$users=User::find()->all();
           // $this->authKey=Yii::$app->getSecurity()->generateRandomKey();
            
        foreach (self::$users as $user) {
            
            if (strcasecmp($user['email'], $email) === 0) {
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
     * @param  string  $password password to validate
     * @return boolean if password provided is valid for current user
     */
    public function validatePassword($password)
    {
        return $this->password === $password;
    }
}
