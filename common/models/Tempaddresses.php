<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "hts_tempaddresses".
 *
 * @property int $shippingaddressId
 * @property int $userId
 * @property string $nickname
 * @property string $name
 * @property string $address1
 * @property string $address2
 * @property string $city
 * @property string $state
 * @property string $country
 * @property string $zipcode
 * @property string $phone
 * @property int $countryCode
 * @property string $slug
 */
class Tempaddresses extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'hts_tempaddresses';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['userId', 'countryCode'], 'integer'],
            [['slug'], 'required'],
            [['nickname'], 'string', 'max' => 45],
            [['name', 'country'], 'string', 'max' => 50],
            [['address1', 'address2'], 'string', 'max' => 60],
            [['city', 'state'], 'string', 'max' => 40],
            [['zipcode', 'phone', 'slug'], 'string', 'max' => 20],
            [['zipcode', 'phone'], 'integer'],
            [['nickname'], 'unique', 'targetAttribute'=>['condition'=>'`userId`=:secondKey',
            'params'=>[':secondKey'=>Yii::$app->user->id]],'on'=>'create,update'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'shippingaddressId' => 'Shippingaddress ID',
            'userId' => 'User ID',
            'nickname' => 'Nickname',
            'name' => 'Name',
            'address1' => 'Address1',
            'address2' => 'Address2',
            'city' => 'City',
            'state' => 'State',
            'country' => 'Country',
            'zipcode' => 'Zipcode',
            'phone' => 'Phone',
            'countryCode' => 'Country Code',
            'slug' => 'Slug',
        ];
    }

    public function relations()
	{
	
		return array(
			'user' => array(self::BELONGS_TO, 'Users', 'userId'),
			'countryCode0' => array(self::BELONGS_TO, 'Country', 'countryCode'),
		);
	}

}
