<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "hts_adspromotiondetails".
 *
 * @property int $id
 * @property int $productId
 * @property int $promotionTime
 * @property int $promotionTranxId
 * @property int $createdDate
 */
class Adspromotiondetails extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'hts_adspromotiondetails';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['productId', 'promotionTime', 'promotionTranxId', 'createdDate'], 'required'],
            [['productId', 'promotionTime', 'promotionTranxId', 'createdDate'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'productId' => 'Product ID',
            'promotionTime' => 'Promotion Time',
            'promotionTranxId' => 'Promotion Tranx ID',
            'createdDate' => 'Created Date',
        ];
    }
}
