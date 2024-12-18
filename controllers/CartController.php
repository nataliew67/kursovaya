<?php
namespace app\controllers;
use app\models\Cart;
use app\models\Paintings;
use Yii;
use yii\web\Response;
use app\models\Users;
class CartController extends FunctionController
{
    public $modelClass = 'appmodelsCart';

    public function actionItems()
    {
        $user = Users::getToken();
        if ($user && $user->isAuthorized()) {
            $cartItems = Cart::find()->where(['user_id' => $user->id_user])->andWhere(['order_id' => null])->all();

            // Если корзина пуста, возвращаем 404
            if (empty($cartItems)) {
                return $this->send(404, [
                    'error' => [
                        'code' => 404,
                        'message' => 'Корзина пуста'
                    ]
                ]);
            }

            // Формируем ответ с данными корзины
            $cartData = [];
            foreach ($cartItems as $item) {
                $painting = Paintings::findOne($item->paint_id); // Получаем продукт по ID из связи
                if ($painting) { // Проверяем, существует ли продукт
                    $totalPrice = $item->count * $painting->price; // Считаем общую цену

                    $cartData[] = [
                        'name' => $painting->name,
                        'count' => $item->count,
                        'total_price' => $totalPrice,
                    ];
                }
            }

            return [
                'data' => [
                    'cart' => $cartData,
                ],
            ];
        } else {
            return $this->send(401, [
                'error' => [
                    'code' => 401,
                    'message' => 'Unauthorized'
                ]
            ]); 
        }
    }
    public function actionEdit()
    {$id_cart = Yii::$app->request->get('id_cart');
        $user = Users::getToken();
        if ($user && $user->isAuthorized()) {
            $data = Yii::$app->request->post();
            $counts = $data['count'];
            if (empty($counts)) {
                return $this->send(422, [
                    'error' => [
                        'code' => 422,
                        'message' => 'Validation error',
                        'error' => 'Не может быть пустым'
                    ]
                    ]);
                
            }
           
          
                if (!is_numeric($counts)) {
                    return $this->send(422, [
                        'error' => [
                            'code' => 422,
                            'message' => 'Validation error',
                            'error' => 'Количество должно быть числом'
                        ]
                    ]);
                }
           
            $cart = Cart::findOne($id_cart);
            if (!$cart) {
                return $this->send(404, [
                    'error' => [
                        'code' => 404,
                        'message' => 'Cart not found'
                    ]
                ]);
            }
            if ($cart->order_id !== null) {
                return $this->send(404, [
                    'error' => [
                        'code' => 404,
                        'message' => 'Cart not found'
                      
                    ]
                ]);
            }
            $paint = Paintings::findOne($cart->paint_id);
            if (!$paint) {
                return $this->send(404, [
                    'error' => [
                        'code' => 404,
                        'message' => 'Painting not found'
                    ]
                ]);
            }
            $cart->count = $data['count'];
            $total_price = $cart->count * $paint->price;
            if ($cart->validate() && $cart->save()) {
                return $this->send(200, [
                    'data' => [
                        'status' => 'ok',
                        'product' => $cart->paint->name,
                        'count' => $cart->count,
                        'total_price' => $total_price
                    ]
                ]);
            }
            return $this->validation($cart);
        } else{

            return $this->send(401, [
                'error' => [
                    'code' => 401,
                    'message' => 'Unauthorized'
                ]
            ]); 
        }
    }

        public function actionNew()
        {
            Yii::$app->response->format = Response::FORMAT_JSON;
    
            $user = Users::getToken();
        if ($user && $user->isAuthorized()) {
                          // Получение данных из запроса
            $request = Yii::$app->request->post();
            if (empty($request['paint_id'])&& empty($request['count'])) {
                return $this->send(422, [
                    'error' => [
                        'code' => 422,
                        'message' => 'Validation error',
                        'error' => 'Не может быть пустым'
                    ]
                ]);
            }
            $paintId = $request['paint_id'];
          $count = $request['count']; // Установим значение по умолчанию
    
            // Проверка наличия товара
            $painting = Paintings::findOne($paintId);
            if (!$painting) {
                return $this->send(404, [
                                    'error' => [
                                        'code' => 404,
                                        'message' => 'Painting not found'
                                    ]
                                ]);
            }
    
            // Создание или обновление записи в корзине
            $cart = Cart::find()->where(['user_id' => $user->id_user, 'paint_id' => $paintId])->one();

        if ($cart === null) {
            // Если товара нет в корзине, создаем новую запись
            $cart = new Cart();
            $cart->user_id = $user->id_user;
            $cart->paint_id = $paintId;
            $cart->count = $count;
        } else {
            if ($cart->order_id !== null) {
            // Создаем новую запись
            $newCartItem = new Cart();
            $newCartItem->user_id = $user->id_user;
            $newCartItem->paint_id = $paintId;
            $newCartItem->count = $count;
            if ($newCartItem->save()) {
                return $this->send(200, [
                    'data' => [
                        'status' => 'ok',
                        'id' => $newCartItem->id_cart,
                    ]
                ]);
            } } else {
                // Если товар уже есть в корзине, обновляем количество
                $cart->count += $count;
            }}
    
            if ($cart->save()) {
                return $this->send(200,[
                    'data' => [
                        'status' => 'ok',
                        'id' => $cart->id_cart,
                    ],
                ]);
             
        }  
            } else{   
                return $this->send(401, [
                'error' => [
                    'code' => 401,
                    'message' => 'Unauthorized'
                ]
            ]); 
        }
    }  
    
    public function actionDelete()
    {
            Yii::$app->response->format = Response::FORMAT_JSON;
            $id_cart = Yii::$app->request->get('id_cart');
            $user = Users::getToken();
            if ($user && $user->isAuthorized()) {
                // Найти товар в корзине по ID
                $cartItem = Cart::find()->where(['id_cart' => $id_cart, 'user_id' =>$user->id_user])->one();
                if ($cartItem) {
                    // Удаляем товар из корзины
                    if ($cartItem->delete()) {
                        return $this->send(200,[
                            'data' => [
                                'status' => 'ok'
                    
                            ],
                        ]);
                    } 
                } else {
                    return $this->send(404, [
                        'error' => [
                            'code' => 404,
                            'message' => 'Item not found or does not belong to you.'
                        ]
                    ]); 
                }
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


