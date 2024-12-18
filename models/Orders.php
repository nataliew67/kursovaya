<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "orders".
 *
 * @property int $id_order
 * @property string $phone
 * @property string $address
 * @property string $date_create
 * @property string $payment
 *
 * @property Cart[] $carts
 */
class Orders extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'orders';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {

        return [
            [['phone', 'address'], 'required'],
            [['date_create'], 'safe'],
            [['payment'], 'string'],
            [['phone'], 'string', 'max' => 20],
            [['address'], 'string', 'max' => 100],
            ['phone', 'match', 'pattern' => '/^(\+)[\d]+$/iu', 'message'=>'Только цифры и +, в виде +79348544858'],
            ['address', 'match', 'pattern' => '/^[а-яёА-ЯЁ\-\s\.\,\d]+$/iu', 'message'=>'Кириллица, дефис, запятая, точка, пробел'],
            ['payment', 'match', 'pattern' => '/^[а-я\s]+$/iu', 'message'=>'наличными или банковской картой'],
            

        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_order' => 'Id Order',
            'phone' => 'Phone',
            'address' => 'Address',
            'date_create' => 'Date Create',
            'payment' => 'Payment',
        ];
    }

    /**
     * Gets query for [[Carts]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCarts()
    {
        return $this->hasMany(Cart::class, ['order_id' => 'id_order']);
    }
}
