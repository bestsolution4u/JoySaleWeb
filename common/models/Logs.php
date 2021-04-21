<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "hts_logs".
 *
 * @property int $id
 * @property string $type
 * @property int $userid
 * @property string $notifyto
 * @property int $sourceid
 * @property int $itemid
 * @property string $notifymessage
 * @property int $notification_id
 * @property string $message
 * @property int $createddate
 */
class Logs extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'hts_logs';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['type', 'userid', 'notifyto', 'sourceid', 'notifymessage', 'notification_id', 'createddate'], 'required'],
            [['type', 'notifyto', 'notifymessage', 'message'], 'string'],
            [['userid', 'sourceid', 'itemid', 'notification_id', 'createddate'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'type' => 'Type',
            'userid' => 'Userid',
            'notifyto' => 'Notifyto',
            'sourceid' => 'Sourceid',
            'itemid' => 'Itemid',
            'notifymessage' => 'Notifymessage',
            'notification_id' => 'Notification ID',
            'message' => 'Message',
            'createddate' => 'Createddate',
        ];
    }
}
