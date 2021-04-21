<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "hts_followers".
 *
 * @property int $id
 * @property int $userId
 * @property int $follow_userId
 * @property string $followedOn
 */
class Followers extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'hts_followers';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['userId', 'follow_userId'], 'required'],
            [['userId', 'follow_userId'], 'integer'],
            [['followedOn'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'userId' => 'User ID',
            'follow_userId' => 'Follow User ID',
            'followedOn' => 'Followed On',
        ];
    }

    
    public function sendEmail($emailTo,$fusername,$curentusername){
        
        $siteSettings = Sitesettings::find()->orderBy(['id' => SORT_DESC])->one();
        return Yii::$app
            ->mailer
            ->compose(
                ['html' => 'getfollow-html', 'text' => 'getfollow-text'],
                ['siteSettings' => $siteSettings,'followername' => $fusername, 'username' => $curentusername]
            )
            ->setFrom($siteSettings->smtpEmail, $siteSettings->sitename)
            ->setTo($emailTo)
            ->setSubject($fusername.' Now following you')
            ->send();
    }
}
