<?php
namespace app\controllers;
use app\models\Users;
use app\models\LoginForm;
use Yii;
class UsersController extends FunctionController
{
 public $modelClass = 'app\models\Users';

 public function actionCreate()
 {
 $data=Yii::$app->request->post();
 $user=new Users();
 $user->load($data, '');
 if (!$user->validate()) {
    return $this->validation($user); // Убедитесь, что метод validation выводит ошибки
}
 $user->password=Yii::$app->getSecurity()->generatePasswordHash($user->password);;
 $user->save();
 if (!$user->save()) {
    return $this->send(500, $user->getErrors()); // Возвращаем ошибки при сохранении
}
return $this->send(204, null);
 } 

 public function actionLogin(){
    $data=Yii::$app->request->post();
    $login_data=new LoginForm();
    $login_data->load($data, '');
   if (!$login_data->validate()) return $this->validation($login_data);
    $user=Users::find()->where(['username'=>$login_data->username])->one();
    if (!is_null($user)) {
    if (Yii::$app->getSecurity()->validatePassword($login_data->password, $user->password)) {
    $token = Yii::$app->getSecurity()->generateRandomString();
    $user->token = $token;
    $user->save(false);//false — произвести запись без валидации
    $data = ['data' => ['token' => $token]];
    return $this->send(200, $data);
    }
    }
    return $this->send(401, ['error'=>['code'=>401, 'message'=>'Unauthorized', 'errors'=>['username'=>'Неверный логин или пароль']]]);
    }

    public function actionView() {
            
            $user = Users::getToken();
            if ($user && $user->isAuthorized()) {
                $data = [
                    'data' => [
                        'user' => [
                            'name' => $user->name,
                            'last_name' => $user->last_name,
                            'username' => $user->username,
                            'phone' => $user->phone,
                            'email' => $user->email,
                        ]
                    ]
                ];
                return $this->send(200, $data);
            }       
            return $this->send(401, [
                'error' => [
                    'code' => 401,
                    'message' => 'Unauthorized'
                ]
            ]); 
        }
        public function actionChange() {
            $user = Users::getToken();
            if ($user && $user->isAuthorized()) { 
                $data=Yii::$app->request->post();
// Проверка на наличие необходимых полей
if (empty($data['phone']) || empty($data['email'])) {
    return $this->send(422, [
        'error' => [
            'code' => 422,
            'message' => 'Validation error',
            'error' => 'The phone or the email should not be empty'
        ]
    ]);
}

// Обновляем данные пользователя
$user->phone = $data['phone'];
$user->email = $data['email'];

// Сохраняем изменения и проверяем на ошибки
if ($user->validate() && $user->save()) {
    return $this->send(200, [
        'data' => [
            'status' => 'ok'
        ]
    ]);
}

// Если валидация не прошла
return $this->validation($user);
        }
        return $this->send(401, [
            'error' => [
                'code' => 401,
                'message' => 'Unauthorized'
            ]
        ]); 
    }

    
}
