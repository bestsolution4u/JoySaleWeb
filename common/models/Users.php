<?php
/*
 * @ https://doniaweb.com - IonCube v10 Decoder Online
 * @ PHP 7.1
 * @ Decoder version: 1.0.4
 * @ Release: 02/06/2020
 *
 * @ ZendGuard Decoder PHP 7.1
 */

namespace common\models;

class Users extends \yii\db\ActiveRecord implements \yii\web\IdentityInterface
{
    public $stripeprivatekey = NULL;
    public $stripepublickey = NULL;
    public $cty = NULL;
    const STATUS_DELETED = 0;
    const STATUS_ACTIVE = 10;
    public static function tableName()
    {
        return "users";
    }
    public function behaviors()
    {
        return array(\yii\behaviors\TimestampBehavior::className());
    }
    public function rules()
    {
        return array(array("status", "default", "value" => self::STATUS_ACTIVE), array("status", "in", "range" => array(self::STATUS_ACTIVE, self::STATUS_DELETED)), array("username", "trim"), array("username", "required"), array("username", "unique", "targetClass" => "\\common\\models\\Users", "message" => "This username has already been taken."), array("username", "string", "min" => 2, "max" => 255), array(array("city"), "string", "max" => 50), array("email", "trim"), array("email", "required"), array("email", "email"), array("email", "string", "max" => 255), array("email", "unique", "targetClass" => "\\common\\models\\Users", "message" => "This email address has already been taken."), array("email", "unique", "targetClass" => "\\common\\models\\Admin", "message" => "This email address has already been taken."), array("email", "checkEmail", "on" => "forgetpassword"));
    }
    public static function findIdentity($id)
    {
        return static::findOne(array("userId" => $id, "status" => self::STATUS_ACTIVE));
    }
    public static function findIdentityByAccessToken($token, $type = NULL)
    {
        throw new \yii\base\NotSupportedException("\"findIdentityByAccessToken\" is not implemented.");
    }
    public function checkEmail($attribute)
    {
        $check = Users::find()->where(array("email" => $this->email))->one();
        if (empty($check)) {
            $this->addError($this->attribute, \Yii::t("app", "Email Not Found"));
        }
    }
    public static function findByUsername($username)
    {
        return static::findOne(array("username" => $username, "status" => self::STATUS_ACTIVE));
    }
    public static function findUserByEmail($email)
    {
        return static::findOne(array("email" => $email));
    }
    public function getReviewRating()
    {
    }
    public static function findByPasswordResetToken($token)
    {
        if (!static::isPasswordResetTokenValid($token)) {
            return null;
        }
        return static::findOne(array("password_reset_token" => $token, "status" => self::STATUS_ACTIVE));
    }
    public static function isPasswordResetTokenValid($token)
    {
        if (empty($token)) {
            return false;
        }
        $timestamp = (int) substr($token, strrpos($token, "_") + 1);
        $expire = \Yii::$app->params["user.passwordResetTokenExpire"];
        return time() <= $timestamp + $expire;
    }
    public function getId()
    {
        return $this->getPrimaryKey();
    }
    public function getAuthKey()
    {
        return $this->auth_key;
    }
    public function validateAuthKey($authKey)
    {
        return $this->getAuthKey() === $authKey;
    }
    public function validatePassword($password)
    {
        return \Yii::$app->security->validatePassword($password, $this->password_hash);
    }
    public function setPassword($password)
    {
        return $this->password_hash = \Yii::$app->security->generatePasswordHash($password);
    }
    public function generateAuthKey()
    {
        return $this->auth_key = \Yii::$app->security->generateRandomString();
    }
    public function generatePasswordResetToken()
    {
        $this->password_reset_token = \Yii::$app->security->generateRandomString() . "_" . time();
    }
    public function removePasswordResetToken()
    {
        return $this->password_reset_token = null;
    }
    public function generateFbdtails()
    {
        if (!empty($this->fbdetails)) {
            $details = \yii\helpers\Json::decode($this->fbdetails, true);
            $output = "";
            foreach ($details as $fbKey => $fbvalue) {
                $output .= $fbKey . ": " . $fbvalue . "</br>";
            }
            return $output;
        }
    }
    public function sendEmail($email, $verifyLink, $name)
    {
        $siteSettings = Sitesettings::find()->orderBy(array("id" => SORT_DESC))->one();
        return \Yii::$app->mailer->compose(array("html" => "signup-html", "text" => "signup-text"), array("siteSettings" => $siteSettings, "access_url" => $verifyLink, "name" => $name))->setFrom($siteSettings->smtpEmail, $siteSettings->sitename)->setTo($email)->setSubject($siteSettings->sitename . \Yii::t("app", " Welcome Mail"))->send();
    }
    public function sendForgotmail($email, $name, $uniquecode_pass)
    {
        $siteSettings = Sitesettings::find()->orderBy(array("id" => SORT_DESC))->one();
        return \Yii::$app->mailer->compose(array("html" => "forget-html", "text" => "forget-text"), array("siteSettings" => $siteSettings, "name" => $name, "uniquecode_pass" => $uniquecode_pass))->setFrom($siteSettings->smtpEmail, $siteSettings->sitename)->setTo($email)->setSubject($siteSettings->sitename . \Yii::t("app", "Reset Password Mail"))->send();
    }
    public function sendAdminEmail($email, $name, $password)
    {
        $siteSettings = Sitesettings::find()->orderBy(array("id" => SORT_DESC))->one();
        return \Yii::$app->mailer->compose(array("html" => "adminsignup-html", "text" => "adminsignup-html"), array("siteSettings" => $siteSettings, "name" => $name, "useremail" => $email, "password" => $password))->setFrom($siteSettings->smtpEmail, $siteSettings->sitename)->setTo($email)->setSubject($siteSettings->sitename . " " . \Yii::t("app", "Welcome Mail"))->send();
    }
    public function sendForgetPasswordEmail($email, $name, $resetPasswordData)
    {
        $resetPasswordLink = \Yii::$app->urlManager->createAbsoluteUrl(array("/resetpassword?resetLink=" . $resetPasswordData));
        $siteSettings = Sitesettings::find()->orderBy(array("id" => SORT_DESC))->one();
        return \Yii::$app->mailer->compose(array("html" => "forget-html", "text" => "forget-text"), array("siteSettings" => $siteSettings, "uniquecode_pass" => $resetPasswordLink, "name" => $name))->setFrom($siteSettings->smtpEmail, $siteSettings->sitename)->setTo($email)->setSubject($siteSettings->sitename . " " . \Yii::t("app", "Forget Password Request"))->send();
    }
    public function sendSellerOrderMail($sellerEmail, $orderId, $custom, $userModel, $sellerName, $keyarray, $tempShippingModel)
    {
        $siteSettings = Sitesettings::find()->orderBy(array("id" => SORT_DESC))->one();
        return \Yii::$app->mailer->compose(array("html" => "sellerorderintimation-html", "text" => "sellerorderintimation-text"), array("siteSettings" => $siteSettings, "orderId" => $orderId, "custom" => $custom, "userModel" => $userModel, "sellerName" => $sellerName, "keyarray" => $keyarray, "tempShippingModel" => $tempShippingModel))->setFrom($siteSettings->smtpEmail, $siteSettings->sitename)->setTo($sellerEmail)->setSubject($siteSettings->sitename . " " . \Yii::t("app", "Seller Order Information"))->send();
    }
    public function sendbuyerorderintimation($email, $orderId, $custom, $userModel, $sellerName, $keyarray, $tempShippingModel)
    {
        $siteSettings = Sitesettings::find()->orderBy(array("id" => SORT_DESC))->one();
        return \Yii::$app->mailer->compose(array("html" => "buyerorderintimation-html", "text" => "buyerorderintimation-text"), array("siteSettings" => $siteSettings, "orderId" => $orderId, "custom" => $custom, "userModel" => $userModel, "sellerName" => $sellerName, "keyarray" => $keyarray, "tempShippingModel" => $tempShippingModel))->setFrom($siteSettings->smtpEmail, $siteSettings->sitename)->setTo($email)->setSubject($siteSettings->sitename . " " . \Yii::t("app", "Buyer Order Information"))->send();
    }
    public function sendBuyerEmail($email, $shipping, $buyerModel, $name, $track, $orderModel)
    {
        $siteSettings = Sitesettings::find()->orderBy(array("id" => SORT_DESC))->one();
        return \Yii::$app->mailer->compose(array("html" => "trackdetailsmail-html", "text" => "trackdetailsmail-text"), array("siteSettings" => $siteSettings, "tempShippingModel" => $shipping, "userModel" => $buyerModel, "sellerName" => $name, "tracking" => $track, "model" => $orderModel))->setFrom($siteSettings->smtpEmail, $siteSettings->sitename)->setTo($email)->setSubject($siteSettings->sitename . " " . \Yii::t("app", "Tracking Details Mail"))->send();
    }
    public function verifyEmail($email, $verifyLink, $name)
    {
        $siteSettings = Sitesettings::find()->orderBy(array("id" => SORT_DESC))->one();
        return \Yii::$app->mailer->compose(array("html" => "signup-html", "text" => "signup-text"), array("siteSettings" => $siteSettings, "access_url" => $verifyLink, "name" => $name))->setFrom($siteSettings->smtpEmail, $siteSettings->sitename)->setTo($email)->setSubject($siteSettings->sitename . " " . \Yii::t("app", "Signup Verification Mail"))->send();
    }
    public function reverifyEmail($email, $verifyLink, $name)
    {
        $siteSettings = Sitesettings::find()->orderBy(array("id" => SORT_DESC))->one();
        return \Yii::$app->mailer->compose(array("html" => "adminreverify-html", "text" => "adminreverify-text"), array("siteSettings" => $siteSettings, "access_url" => $verifyLink, "name" => $name))->setFrom($siteSettings->smtpEmail, $siteSettings->sitename)->setTo($email)->setSubject($siteSettings->sitename . " " . \Yii::t("app", "Reverification Mail"))->send();
    }
    public function UserverifyEmail($email, $verifyLink, $name)
    {
        $siteSettings = Sitesettings::find()->orderBy(array("id" => SORT_DESC))->one();
        return \Yii::$app->mailer->compose(array("html" => "reverify-html", "text" => "reverify-text"), array("siteSettings" => $siteSettings, "access_url" => $verifyLink, "name" => $name))->setFrom($siteSettings->smtpEmail, $siteSettings->sitename)->setTo($email)->setSubject($siteSettings->sitename . " " . \Yii::t("app", "Reverification Mail"))->send();
    }
}

?>