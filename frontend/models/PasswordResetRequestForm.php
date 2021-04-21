<?php
namespace frontend\models;

use Yii;
use yii\base\Model;
use common\models\Users;
use common\models\Sitesettings;


class PasswordResetRequestForm extends Model
{
    public $email;


    public function rules()
    {
        return [
            ['email', 'trim'],
           // ['email', 'required'],
            //['email', 'email'],
            ['email', 'exist',
                'targetClass' => '\common\models\Users',
                'filter' => ['status' => Users::STATUS_ACTIVE],
                'message' => 'There is no user with this email address.'
            ],
        ];
    }

  
    public function sendEmail()
    {

         $siteSettings = Sitesettings::find()->orderBy(['id' => SORT_DESC])->one();
        /* @var $user User */
        $user = Users::findOne([
            'status' => Users::STATUS_ACTIVE,
            'email' => $this->email,
        ]);

        if (!$user) {
            return false;
        }
        
        if (!Users::isPasswordResetTokenValid($user->password_reset_token)) {
            $user->generatePasswordResetToken();
            if (!$user->save()) {
                return false;
            }
        }

        return Yii::$app
            ->mailer
            ->compose(
                ['html' => 'passwordResetTokenUser-html', 'text' => 'passwordResetTokenUser-text'],
                ['siteSettings' => $siteSettings,'user' => $user]
            )
            ->setFrom($siteSettings->smtpEmail, $siteSettings->sitename)
            ->setTo($this->email)
            ->setSubject(Yii::t('app','Password reset for').' ' . $siteSettings->sitename)
            ->send();
    }

      public function sendForgotEmail($email)
    {

         $siteSettings = Sitesettings::find()->orderBy(['id' => SORT_DESC])->one();
     
        /* @var $user User */
        $user = Users::findOne([
            'status' => Users::STATUS_ACTIVE,
            'email' => $email,
        ]);



        if (!$user) {
            return false;
        }
        
        if (!Users::isPasswordResetTokenValid($user->password_reset_token)) {

            $user->generatePasswordResetToken();
            if (!$user->save()) {
                return false;
            }
        }

        return Yii::$app
            ->mailer
            ->compose(
                ['html' => 'passwordResetTokenUser-html', 'text' => 'passwordResetTokenUser-text'],
                ['siteSettings' => $siteSettings,'user' => $user]
            )
            ->setFrom($siteSettings->smtpEmail, $siteSettings->sitename)
            ->setTo($email)
            ->setSubject(Yii::t('app','Password reset for').' ' . $siteSettings->sitename)
            ->send();
    }


    public function sendMailForget()
    {

         $siteSettings = Sitesettings::find()->orderBy(['id' => SORT_DESC])->one();
        /* @var $user User */
        $user = Users::findOne([
            'status' => Users::STATUS_ACTIVE,
            'email' => $this->email,
        ]);

        if (!$user) {
            return false;
        }
        
        if (!Users::isPasswordResetTokenValid($user->password_reset_token)) {
            $user->generatePasswordResetToken();
            if (!$user->save()) {
                return false;
            }
        }

        return Yii::$app
            ->mailer
            ->compose(
                ['html' => 'passwordResetTokenUser-html', 'text' => 'passwordResetTokenUser-text'],
                ['siteSettings' => $siteSettings,'user' => $user]
            )
            ->setFrom($siteSettings->smtpEmail, $siteSettings->sitename)
            ->setTo($this->email)
            ->setSubject(Yii::t('app','Password reset for').' ' . $siteSettings->sitename)
            ->send();
    }
}
