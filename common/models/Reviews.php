<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "hts_reviews".
 *
 * @property int $reviewId
 * @property int $senderId
 * @property int $receiverId
 * @property string $reviewTitle
 * @property string $review
 * @property int $rating
 * @property string $reviewType
 * @property int $sourceId
 * @property int $createdDate
 */
class Reviews extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'hts_reviews';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['senderId', 'receiverId', 'reviewTitle', 'review', 'rating', 'reviewType', 'sourceId', 'createdDate'], 'required'],
            [['senderId', 'receiverId', 'rating', 'sourceId', 'createdDate'], 'integer'],
            [['reviewTitle'], 'string', 'max' => 60],
            [['review'], 'string', 'max' => 500],
            [['reviewType'], 'string', 'max' => 30],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'reviewId' => 'Review ID',
            'senderId' => 'Sender ID',
            'receiverId' => 'Receiver ID',
            'reviewTitle' => 'Review Title',
            'review' => 'Review',
            'rating' => 'Rating',
            'reviewType' => 'Review Type',
            'sourceId' => 'Source ID',
            'createdDate' => 'Created Date',
        ];
    }

    public function getOrders()
    {
        return $this->hasMany(Orders::className(), ['orderId' => 'sourceId']);
    }

    public function getUser()
    {
        return $this->hasMany(Users::className(), ['userId' => 'receiverId']);
    }
}
