<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "hts_photos".
 *
 * @property int $photoId
 * @property int $productId
 *  @property int $createdDate
 */
class Photos extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'hts_photos';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['photoId', 'productId', 'name', 'createdDate'], 'required'],
            [['photoId', 'productId', 'createdDate'], 'integer'],
            [['name'], 'string', 'max' => 60],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'photoId' => 'Photo ID',
            'productId' => 'Product ID',
            'name' => 'Name',
            'createdDate' => 'Created Date',
        ];
    }
}
