<?php
namespace frontend\models;

use Yii;
use yii\base\Model;
use common\models\Users;


class SignupForms extends Model
{
    public $username;
    public $email;
    public $password;
    public $name;
    public $password_repeat;
    public $userstatus;
    public $activationStatus;

    public function rules()
    {
        return [
            ['name', 'trim'],
            // ['name', 'required'],
            // ['name', 'string', 'min' => 3, 'max' => 30],

            ['username', 'trim'],
            //['username', 'required'],
            ['username', 'unique', 'targetClass' => '\common\models\Users', 'message' => Yii::t('app','This username has already been taken')],
            //['username', 'string', 'min' => 2, 'max' => 255],

            ['email', 'trim'],
            // ['email', 'required'],
            // ['email', 'email'],
            ['email', 'string', 'max' => 255],
            ['email', 'unique', 'targetClass' => '\common\models\Users', 'message' => Yii::t('app','This email address has already been taken')],

             ['password', 'trim'],
            // ['password', 'string','min' => 6],
             ['password_repeat', 'trim'],
            // ['password_repeat', 'required'],
            // ['password_repeat', 'compare', 'compareAttribute'=>'password', 'message'=>Yii::t('app',"Passwords don't match") ],
         
         
            
        ];
    }

  
    public function signup()
    {
        if (!$this->validate()) {
            return null;
        }
        $user = new Users();
        $user->username = $this->username;
        $user->email = $this->email;
        $user->name = $this->name;
        $user->userstatus = $this->userstatus;
        $user->activationStatus = $this->activationStatus;
        $user->password_encrypt=base64_encode($this->password);
        $user->setPassword($this->password);
        $user->generateAuthKey();
        
        return $user->save() ? $user : null;
    }

        public function attributeLabels()
    {
        return [
            'name'=>Yii::t('app','Name'),
            'username'=>Yii::t('app','Username'),
            'email'=>Yii::t('app','Email'),
            'password'=>Yii::t('app','Password'),
            'password_repeat' => Yii::t('app','Confirm Password'),
        ];
    }
}
