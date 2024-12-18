<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "cart".
 *
 * @property int $id_cart
 * @property int $user_id
 * @property int $paint_id
 * @property int $count
 * @property int|null $order_id
 *
 * @property Orders $order
 * @property Paintings $paint
 * @property Users $user
 */
class Cart extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'cart';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id', 'paint_id', 'count'], 'required'],
            [['user_id', 'paint_id', 'count', 'order_id'], 'integer'],

            ['count', 'match', 'pattern' => '/[0-9]+$/iu', 'message' =>'Только цифры'],
            [['user_id', 'paint_id', 'order_id'], 'unique', 'targetAttribute' => ['user_id', 'paint_id', 'order_id']],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => Users::class, 'targetAttribute' => ['user_id' => 'id_user']],
            [['order_id'], 'exist', 'skipOnError' => true, 'targetClass' => Orders::class, 'targetAttribute' => ['order_id' => 'id_order']],
            [['paint_id'], 'exist', 'skipOnError' => true, 'targetClass' => Paintings::class, 'targetAttribute' => ['paint_id' => 'id_painting']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_cart' => 'Id Cart',
            'user_id' => 'User ID',
            'paint_id' => 'Paint ID',
            'count' => 'Count',
            'order_id' => 'Order ID',
        ];
    }

    /**
     * Gets query for [[Order]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getOrder()
    {
        return $this->hasOne(Orders::class, ['id_order' => 'order_id']);
    }

    /**
     * Gets query for [[Paint]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPaint()
    {
        return $this->hasOne(Paintings::class, ['id_painting' => 'paint_id']);
    }

    /**
     * Gets query for [[User]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(Users::class, ['id_user' => 'user_id']);
    }
}
