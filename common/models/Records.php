<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "hts_records".
 *
 * @property int $id
 * @property int $productId
 * @property string $records
 */
class Records extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'hts_records';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['productId', 'records'], 'required'],
            [['productId'], 'integer'],
            [['records'], 'string'],
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
            'records' => 'Records',
        ];
    }
}
