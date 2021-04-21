<?php

namespace common\models;

use Yii;

class Chats extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'hts_chats';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user1', 'user2', 'lastMessage', 'lastToRead', 'blockedUser', 'lastContacted'], 'required'],
            [['user1', 'user2', 'lastToRead', 'blockedUser', 'lastContacted'], 'integer'],
            [['lastMessage'], 'string', 'max' => 500],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'chatId' => 'Chat ID',
            'user1' => 'User1',
            'user2' => 'User2',
            'lastMessage' => 'Last Message',
            'lastToRead' => 'Last To Read',
            'blockedUser' => 'Blocked User',
            'lastContacted' => 'Last Contacted',
        ];
    }
}
