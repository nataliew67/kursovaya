<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "type".
 *
 * @property int $type_id
 * @property string $name
 *
 * @property Paintings $paintings
 */
class Type extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'type';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['name'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'type_id' => 'Type ID',
            'name' => 'Name',
        ];
    }

    /**
     * Gets query for [[Paintings]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPaintings()
    {
        return $this->hasMany(Paintings::class, ['type_id' => 'type_id']);
    }
}
