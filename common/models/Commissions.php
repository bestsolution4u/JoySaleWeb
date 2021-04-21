<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "hts_commissions".
 *
 * @property int $id
 * @property string $percentage
 * @property string $minRate
 * @property string $maxRate
 * @property int $status
 * @property int $date
 */
class Commissions extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'hts_commissions';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['percentage', 'minRate', 'maxRate', 'status', 'date'], 'required'],
            [['status', 'date'], 'integer'],
            [['percentage', 'minRate', 'maxRate'], 'string', 'max' => 25],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'percentage' => 'Percentage',
            'minRate' => 'Min Rate',
            'maxRate' => 'Max Rate',
            'status' => 'Status',
            'date' => 'Date',
        ];
    }
}
