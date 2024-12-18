<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord; 
use yii\web\IdentityInterface; 
/**
 * This is the model class for table "users".
 *
 * @property int $id_user
 * @property string $name
 * @property string $last_name
 * @property string $username
 * @property string $phone
 * @property string $email
 * @property string $password
 * @property string|null $token
 * @property string $admin
 *
 * @property Cart[] $carts
 */
class Users extends ActiveRecord implements IdentityInterface
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'users';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'last_name', 'username', 'phone', 'email', 'password'], 'required'],
            [['admin'], 'string'],
        ['password', 'match', 'pattern' => '/^.{6,}+$/iu','message'=>' не менее 6 символов'],
            ['name', 'match', 'pattern' => '/^[а-яёА-ЯЁ]+$/iu', 'message'=>'Только кириллица'],
            ['last_name', 'match', 'pattern' => '/^[а-яёА-ЯЁ\-]+$/iu', 'message'=>'Кириллица, дефис'],
            ['username', 'match', 'pattern' => '/^[a-zA-Z\-\*\d\_]{4,}+$/iu', 'message'=>'Латиница, дефис, *, цифры, _. Минимум 4 символа'],
            ['phone', 'match', 'pattern' => '/^(\+)[\d]+$/iu', 'message'=>'Только цифры и +, в виде +79348544858'],
            ['email', 'email'],
            [['name', 'last_name', 'username'], 'string', 'max' => 80],
            [['phone'], 'string', 'max' => 20],
            [['email'], 'string', 'max' => 40],
            [['password'], 'string', 'max' => 100],
            [['token'], 'string', 'max' => 255],
            [['username'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_user' => 'Id User',
            'name' => 'Name',
            'last_name' => 'Last Name',
            'username' => 'Username',
            'phone' => 'Phone',
            'email' => 'Email',
            'password' => 'Password',
            'token' => 'Token',
            'admin' => 'Admin',
        ];
    }

    /**
     * Gets query for [[Carts]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCarts()
    {
        return $this->hasMany(Cart::class, ['user_id' => 'id_user']);
    }

    public static function findIdentity($id_user)
    {
    }
   
    public static function findIdentityByAccessToken($token, $type = null)
    {
        return static::findOne(['token' => $token]);
    }
   
    public function getId()
    {
        return $this->id_user;
    }
   
    public function getAuthKey()
    {
      
    }
   
    public function validateAuthKey($authKey)
    {
        
    }
   
    public function isAdmin()
    {
    return $this->admin ==='admin';
    }
    public function isAuthorized() {
       
       $token = str_replace('Bearer ', '', Yii::$app->request->headers->get('Authorization'));
       if (!$token||$token != $this->token) {
           return false;
       }
       return true; 
   }
   
   public static function getToken(){
       return self::findOne(['token'=>str_replace('Bearer ', '', Yii::$app->request->headers->get('Authorization'))]);
   }
}
