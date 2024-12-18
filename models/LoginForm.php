<?php
namespace app\models;
use Yii;
use yii\base\Model;
class LoginForm extends Model
{
 public $username;
 public $password;
 /**
 * @return array the validation rules.
 */
 public function rules()
 {
 return [
 // username and password are both required
 [['username', 'password'], 'required'],
 ];
 } }
