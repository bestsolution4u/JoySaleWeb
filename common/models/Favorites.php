<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "hts_favorites".
 *
 * @property int $id
 * @property int $userId
 * @property int $productId
 */
class Favorites extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'hts_favorites';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['userId', 'productId'], 'required'],
            [['userId', 'productId'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'userId' => 'User ID',
            'productId' => 'Product ID',
        ];
    }
}
