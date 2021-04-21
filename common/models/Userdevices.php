<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "hts_userdevices".
 *
 * @property int $id
 * @property string $deviceToken
 * @property int $user_id
 * @property int $badge
 * @property int $type
 * @property int $mode
 * @property string $lang_type
 * @property int $cdate
 * @property string $deviceId
 */
class Userdevices extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
     public $cnt;
    public static function tableName()
    {
        return 'hts_userdevices';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'badge', 'type', 'mode', 'cdate'], 'integer'],
            [['type', 'mode', 'lang_type', 'deviceId'], 'required'],
            [['deviceToken', 'deviceId'], 'string', 'max' => 200],
            [['deviceModel', 'deviceOS'], 'string', 'max' => 100],
            [['lang_type'], 'string', 'max' => 50],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'deviceToken' => 'Device Token',
            'user_id' => 'User ID',
            'badge' => 'Badge',
            'type' => 'Type',
            'mode' => 'Mode',
            'lang_type' => 'Lang Type',
            'cdate' => 'Cdate',
            'deviceId' => 'Device ID',
        ];
    }
}
