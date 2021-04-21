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
class Comments extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'hts_comments';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['userId', 'productId'], 'required'],
            [['userId', 'productId', 'createdDate'], 'integer'],
            [['comment'], 'string'],
            [['productId'], 'exist', 'skipOnError' => true, 'targetClass' => Products::className(), 'targetAttribute' => ['productId' => 'productId']],
            [['userId'], 'exist', 'skipOnError' => true, 'targetClass' => Users::className(), 'targetAttribute' => ['userId' => 'userId']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'commentId' => 'Comment ID',
            'userId' => 'User ID',
            'productId' => 'Product ID',
            'comment' => 'Comment',
            'createdDate' => 'Created Date',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProduct()
    {
        return $this->hasOne(Products::className(), ['productId' => 'productId']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(Users::className(), ['userId' => 'userId']);
    }
}
