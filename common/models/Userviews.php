<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "hts_comments".
 *
 * @property int $commentId
 * @property int $userId
 * @property int $productId
 * @property string $comment
 * @property int $createdDate
 *
 * @property HtsProducts $product
 * @property HtsUsers $user
 */
class Userviews extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'hts_userviews';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'product_id','seller_id','cdate'], 'required'],
            [['user_id', 'product_id','seller_id'], 'integer'],
            [['city'],'string']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'user_id' => 'User ID',
            'product_id' => 'Product ID',
            'seller_id' => 'Seller ID',
            'city' => 'City',
            'created_at' => 'Created Date',
            'cdate' => 'Created Date',
        ];
    }
}
