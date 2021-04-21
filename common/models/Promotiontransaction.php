<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "hts_promotiontransaction".
 *
 * @property int $id
 * @property int $productId
 * @property int $userId
 * @property string $promotionName
 * @property int $promotionPrice
 * @property int $promotionTime
 * @property string $status
 * @property string $tranxId
 * @property int $createdDate
 * @property int $initial_check
 * @property int $approvedStatus
 */
class Promotiontransaction extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'hts_promotiontransaction';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['productId', 'userId', 'promotionName', 'promotionPrice', 'promotionTime', 'status', 'tranxId', 'createdDate', 'initial_check', 'approvedStatus'], 'required'],
            [['productId', 'userId', 'promotionPrice', 'promotionTime', 'createdDate', 'initial_check', 'approvedStatus'], 'integer'],
            [['status'], 'string'],
            [['promotionName', 'tranxId'], 'string', 'max' => 250],
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
            'userId' => 'User ID',
            'promotionName' => 'Promotion Name',
            'promotionPrice' => 'Promotion Price',
            'promotionTime' => 'Promotion Time',
            'status' => 'Status',
            'tranxId' => 'Tranx ID',
            'createdDate' => 'Created Date',
            'initial_check' => 'Initial Check',
            'approvedStatus' => 'Approved Status',
        ];
    }

    function getCreatedDate()
    {
        if ($this->createdDate===null)
        return;

        return date("d-m-Y",$this->createdDate);
    }

    function getUserName()
    {
        if ($this->userId===null)
        return;

        return yii::$app->Myclass->getUserDetailss($this->userId)->username;
    }
}
