<?php
namespace app\controllers;
use app\models\Paintings;
use Yii;
use app\models\Users;
use yii\web\UploadedFile;
class PaintingsController extends FunctionController
{
 public $modelClass = 'app\models\Paintings';

    public function actionView() {  
        $paintings = Paintings::find()->all();
        
        if (empty($paintings)) {
            return $this->send(404, [
                'error' => [
                    'code' => 404,
                    'message' => 'Products not found'
                ]
            ]);
        }
        $productList = [];
        foreach ($paintings as $paint) {
            $paintList[] = [
                'id' => $paint->id_painting,
                'name' => $paint->name,
                'type' => $paint->type->name,
                'description' => $paint->description,
                'photo' => $paint->photo, 
                'price' => $paint->price,
            ];
        }
    
        // Возврат успешного ответа с данными о продуктах
        return $this->send(200, [
            'data' => [
                'paintings' => $paintList
            ]
        ]);
    }






    public function actionSearch()
{
    $queryP = Yii::$app->request->get('type');
     // Выполняем поиск по товарам
     $paintings = Paintings::find()->joinWith('type')->where(['like', 'type.name', $queryP])->all() ;
 if (empty($paintings)) {
     return $this->send(404, [
         'error' => [
             'code' => 404,
             'message' => 'Painting not found'
         ]
     ]);
 }

 // Формируем ответ
 $result = [];
 foreach ($paintings as $painting) {
     $result[] = [
         'id' => $painting->id_painting,
         'name' => $painting->name,
         'type' => $painting->type->name, // Получаем название типа
         'description' => $painting->description,
         'photo' => $painting->photo,
         'price' => $painting->price
     ];
 }

 return $this->send(200, [
     'data' => [
         'paintings' => $result
     ]
 ]);
}

public function actionCreate()
{
    $user = Users::getToken();
    $post_data=\Yii::$app->request->post();
    if (!($user && $user->isAuthorized() && $user->isAdmin())) {
       return $this->send(403, ['error' => ['message' => 'Forbidden']]);
   }
   $post_data=\Yii::$app->request->post();
   $painting=new Paintings();
   $painting->load($post_data, '');
   $painting->photo=UploadedFile::getInstanceByName('photo');
   
   if ($painting->photo) { $hash=hash('sha256', $painting->photo->baseName) . '.' . $painting -> photo->extension;
    $painting->photo->saveAs(\Yii::$app->basePath. '/api/assets/upload/' . $hash);
    $painting->photo=$hash;}else {
        return $this->send(400, ['error' => ['message' => 'Файл не загружен или такая фотография уже есть']]);
    }
    if (!$painting->validate()) return $this->validation($painting);
    $painting->save(false);
    return $this->send(200,[
        'data' => [
            'status' => 'ok',
            'id' => $painting->id_painting,

        ],
    ]);
}

public function actionEdit()
{
    $id_painting = Yii::$app->request->get('id_painting');
        $user = Users::getToken();
        if (!($user && $user->isAuthorized() && $user->isAdmin())) {
            return $this->send(403, ['error' => ['message' => 'Forbidden']]);
        }
            $data = Yii::$app->request->post();
            $painting = Paintings::findOne($id_painting);
            if (!$painting) {
                return $this->send(404, [
                    'error' => [
                        'code' => 404,
                        'message' => 'Painting not found'
                    ]
                ]);
            }
            if (isset($data['name'])) {
                $painting->name = $data['name'];
            }
            
            if (isset($data['type_id'])) {
                $painting->type_id = $data['type_id'];
            }
        
            if (isset($data['price'])) {
                $painting->price = $data['price'];
            }
        
            if (isset($data['description'])) {
                $painting->description = $data['description'];
            }
            if ($painting->validate() && $painting->save()) {
                return $this->send(200, [
                    'data' => [
                        'status' => 'ok'
                        
                    ]
                ]);
            }
            return $this->validation($painting);
}
}
