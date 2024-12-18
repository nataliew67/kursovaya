<?php
namespace app\controllers;
use app\models\Orders;
use Yii;
use yii\web\Response;
use app\models\Users;
use app\models\Cart;
class OrdersController extends FunctionController
{
 public $modelClass = 'app\models\Orders';

 public function actionCreate()
    {
 Yii::$app->response->format = Response::FORMAT_JSON;

        $user = Users::getToken(); // Получаем пользователя по токену

        
        if ($user && $user->isAuthorized()) {
        $data = Yii::$app->request->post();
// Создание заказа
$cartItems = Cart::findAll(['user_id' => $user->id_user, 'order_id' => null]);
        
if (empty($cartItems)) {
    return $this->send(404, [
        'error' => [
            'code' => 404,
            'message' => 'Cart is empty'
        ]
    ]);
}
$order = new Orders();
$order->load($data, '');
if (!$order->validate()) {
    return $this->validation($order); // Убедитесь, что метод validation выводит ошибки
}
 $order->save();
 if (!$order->save()) {
    return $this->send(500, $order->getErrors()); // Возвращаем ошибки при сохранении
}
    $cartItems = Cart::findAll(['user_id' => $user->id_user, 'order_id' => null]);
        foreach ($cartItems as $cartItem) {
            $cartItem->order_id = $order->id_order; // Устанавливаем order_id
            $cartItem->save();

        }
        return $this->send(200, [
            'data' => [
                'status' => 'ok'
            ]
        ]);   }
 else{
return $this->send(401, [
    'error' => [
        'code' => 401,
        'message' => 'Unauthorized'
    ]
]);}
}

public function actionView()
{
    
    $user = Users::getToken();

    if ($user && $user->isAuthorized()) {
        $cartItems = Cart::find()->where(['user_id' => $user->id])->andWhere(['not', ['order_id' => null]])->all();
        if (empty($cartItems)) {
            return $this->send(404, [
                'error' => [
                    'code' => 404,
                    'message' => 'No orders'
                ]
            ]);
        }
        $orderIds = array_column($cartItems, 'order_id');
        $orders = Orders::find()->where(['id_order' => $orderIds])->all();

        if (empty($orders)) {
            return $this->send(404, [
                'error' => [
                    'code' => 404,
                    'message' => 'Orders not found'
                ]
            ]);
        }

        $orderData = [];
        foreach ($orders as $order) {
            $orderData[] = [
                'id' => $order->id_order,
                'phone' => $order->phone,
                'address' => $order->address,
            ];
        }
        return $this->send(200, [
            'data' => [
                'orders' => $orderData
            ]
        ]);
    } else {
        return $this->send(401, [
            'error' => [
                'code' => 401,
                'message' => 'Unauthorized'
            ]
        ]);
    }
}

}