<?php
namespace frontend\models;

use Yii;
use yii\base\Model;
use yii\base\InvalidParamException;
use common\models\Users;


class ResetPasswordForm extends Model
{
    public $password;
    public $confirmpassword;

   
    private $_user;



    public function __construct($token, $config = [])
    {
        if (empty($token) || !is_string($token)) {
            throw new InvalidParamException('Password reset token cannot be blank.');
        }
        $this->_user = Users::findByPasswordResetToken($token);
        if (!$this->_user) {
            throw new InvalidParamException('Wrong password reset token.');
        }
        parent::__construct($config);
    }

  
    public function rules()
    {
        return [
            [['password','confirmpassword'], 'required'],
            ['password', 'string', 'min' => 6],
            ['confirmpassword', 'compare', 'compareAttribute' => 'password','message' => Yii::t('app','Confirm password should match with password')],
        ];
    }

  
    public function resetPassword()
    {
        $user = $this->_user;
        $user->setPassword($this->password);
        $user->password_encrypt = base64_encode($this->password);
        $user->removePasswordResetToken();

        return $user->save(false);
    }
}
