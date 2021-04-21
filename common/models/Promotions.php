<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "hts_promotions".
 *
 * @property int $id
 * @property string $name
 * @property int $days
 * @property int $price
 */
class Promotions extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'hts_promotions';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'days', 'price'], 'required'],
            [['name'], 'string'],
            [['days', 'price'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'days' => 'Days',
            'price' => 'Price',
        ];
    }
}
