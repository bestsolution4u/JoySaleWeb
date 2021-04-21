<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "hts_currencies".
 *
 * @property int $id
 * @property string $currency_name
 * @property string $currency_shortcode
 * @property string $currency_image
 * @property string $currency_symbol
 */
class Currencies extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'hts_currencies';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['currency_name', 'currency_symbol','currency_mode','currency_position'], 'required'],
            [['currency_name'], 'string', 'max' => 50],
          //  [['currency_shortcode', 'currency_symbol'], 'string', 'max' => 10],
            [['currency_image'], 'string', 'max' => 100],
            [['currency_name'],'unique'],
            ['currency_name', 'unique', 'message' => Yii::t('app','This Currency name is already taken')],
            ['currency_shortcode', 'required', 'message' => ''],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'currency_name' => 'Name',
            'currency_shortcode' => 'Shortcode',
            'currency_image' => 'Image',
            'currency_symbol' => 'Symbol',
        ];
    }
}
