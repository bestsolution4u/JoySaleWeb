<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "hts_country".
 *
 * @property int $countryId
 * @property string $code
 * @property string $country
 */
class Country extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'hts_country';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['code'], 'string', 'max' => 2],
            [['country'], 'string', 'max' => 45],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'countryId' => 'Country ID',
            'code' => 'Code',
            'country' => 'Country',
        ];
    }

    public function relations()
	{
		return array(
			'shippings' => array(self::HAS_MANY, 'Shipping', 'countryId'),
			'shippingaddresses' => array(self::HAS_MANY, 'Shippingaddresses', 'countryCode'),
			'tempaddresses' => array(self::HAS_MANY, 'Tempaddresses', 'countryCode'),
		);
	}
}
