<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "hts_filter".
 *
 * @property int $id
 * @property int $categoryID
 * @property int $subcategoryID
 * @property string $name
 * @property string $type
 * @property string $value
 * @property int $isRequired
 * @property int $status
 */
class Filter extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'hts_filter';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'type'], 'required'],
            [['isRequired', 'status'], 'integer'],
            [['value','inputtype'], 'string'],
            [['name'], 'string', 'max' => 150],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'categoryID' => 'Category ID',
            'subcategoryID' => 'Subcategory ID',
            'name' => 'Name',
            'type' => 'Type',
            'inputtype' => 'Input Type',
            'value' => 'Value',
            'isRequired' => 'Is Required',
            'status' => 'Status',
        ];
    }
}
