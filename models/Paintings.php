<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "paintings".
 *
 * @property int $id_painting
 * @property string $name
 * @property int $type_id
 * @property string $description
 * @property string|null $photo
 * @property int $price
 *
 * @property Cart[] $carts
 * @property Type $type
 */
class Paintings extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'paintings';
    }

    /**
     * {@inheritdoc}
     */

     
    public function rules()
    {
        return [
            [['name', 'type_id', 'photo', 'description', 'price'], 'required'],
            [['type_id', 'price'], 'integer'],
            [['description'], 'string', 'max' => 255],
            ['name', 'match', 'pattern' => '/^[а-яёА-ЯЁ\"\s]+$/iu', 'message'=>'Только кириллица, пробелы и "'],
            ['type_id', 'match', 'pattern' => '/^[0-9]+$/iu', 'message'=>'цифра'],
            ['price', 'match', 'pattern' => '/^[\d]+$/iu', 'message'=>'Только цифры'],
       // ['photo', 'file', 'extensions' => 'jpg, jpeg, png', 'message' => 'Допустимые форматы: jpg, jpeg, png'],
            [['name'], 'string', 'max' => 100],
            [['photo'], 'string', 'max' => 255],
            [['type_id'], 'exist', 'skipOnError' => true, 'targetClass' => Type::class, 'targetAttribute' => ['type_id' => 'type_id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_painting' => 'Id Painting',
            'name' => 'Name',
            'type_id' => 'Type ID',
            'description' => 'Description',
            'photo' => 'Photo',
            'price' => 'Price',
        ];
    }

    /**
     * Gets query for [[Carts]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCarts()
    {
        return $this->hasMany(Cart::class, ['paint_id' => 'id_painting']);
    }

    /**
     * Gets query for [[Type]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getType()
    {
        return $this->hasOne(Type::class, ['type_id' => 'type_id']);
    }
}
