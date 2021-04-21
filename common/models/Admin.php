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

class Admin extends \yii\db\ActiveRecord implements \yii\web\IdentityInterface
{
    public $password = NULL;
    const STATUS_DELETED = 0;
    const STATUS_ACTIVE = 10;
    public static function tableName()
    {
        return "{{%admin}}";
    }
    public function behaviors()
    {
        return array(\yii\behaviors\TimestampBehavior::className());
    }
    public function rules()
    {
        return array(array("status", "default", "value" => self::STATUS_ACTIVE), array("status", "in", "range" => array(self::STATUS_ACTIVE, self::STATUS_DELETED)), array("username", "unique", "targetClass" => "\\common\\models\\Admin", "message" => "This username has already been taken."), array("username", "string", "min" => 2, "max" => 255), array("email", "trim"), array("email", "required"), array("email", "email"), array("email", "string", "max" => 255), array("email", "unique", "targetClass" => "\\common\\models\\Admin", "message" => "This email address has already been taken."), array("email", "unique", "targetClass" => "\\common\\models\\Users", "message" => "This email address has already been taken."), array("email", "checkEmail", "on" => "forgetpassword"), array("role", "string"), array("name", "required"), array("password", "required"), array("password", "string", "min" => 6));
    }
    public static function findIdentity($id)
    {
        return static::findOne(array("id" => $id, "status" => self::STATUS_ACTIVE));
    }
    public static function findIdentityByAccessToken($token, $type = NULL)
    {
        throw new \yii\base\NotSupportedException("\"findIdentityByAccessToken\" is not implemented.");
    }
    public function checkEmail($attribute)
    {
        $check = Admin::find()->where(array("email" => $this->email))->one();
        if (empty($check)) {
            $this->addError($this->attribute, \Yii::t("app", "Email Not Found"));
        }
    }
    public static function findByUsername($username)
    {
        return static::findOne(array("username" => $username, "status" => self::STATUS_ACTIVE));
    }
    public static function findByPasswordResetToken($token)
    {
        if (!static::isPasswordResetTokenValid($token)) {
            return null;
        }
        return static::findOne(array("password_reset_token" => $token, "status" => self::STATUS_ACTIVE));
    }
    public function getCreatedDate()
    {
        if ($this->created_at === null) {
            return NULL;
        }
        return date("d-m-Y", $this->created_at);
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
    public function check_guest()
    {
        if (\Yii::$app->user->isGuest) {
            return $this->goHome();
        }
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
        $this->password_hash = \Yii::$app->security->generatePasswordHash($password);
    }
    public function generateAuthKey()
    {
        $this->auth_key = \Yii::$app->security->generateRandomString();
    }
    public function generatePasswordResetToken()
    {
        $this->password_reset_token = \Yii::$app->security->generateRandomString() . "_" . time();
    }
    public function removePasswordResetToken()
    {
        $this->password_reset_token = null;
    }
    public function attributeLabels()
    {
        return array("id" => "ID", "password_hash" => "Password", "role" => "Role", "name" => \Yii::t("app", "Name"), "email" => \Yii::t("app", "Email"), "created_date" => \Yii::t("app", "Created Date"));
    }
}

?>