<?php
namespace common\models;

use Yii;
use yii\base\Model;

class LoginForm extends Model
{
    public $username;
    public $password;
    public $rememberMe = true;
    private $_user;


    
    public function rules()
    {
        return [
            [['username', 'password'],'string'],
            [['username', 'password'], 'required', 'on' => 'login'],
            ['username','email', 'on' => 'login'],
            ['rememberMe', 'boolean'],
            ['password', 'validatePassword'],
            ['password', 'validatePassword', 'on' => 'login'],
        ];
    }

  
    public function validatePassword($attribute, $params)
    {
        if (!$this->hasErrors()) {
            $user = $this->getUser();

            if (!$user || !$user->validatePassword($this->password)) {
                $this->addError($attribute, Yii::t('app','Incorrect password'));
             // print_r($this->password);  print_r($this->username);exit;
            }
             else
            {
                //$date = strtotime(date());
                $user->lastLoginDate=time();
                $user->save();
               //  print_r("cccc");exit;
            }
        }
    }

 
    public function login($rem=0)
    {
       if ($this->validate()) {
            return Yii::$app->user->login($this->getUser(), $rem ? 3600 * 24 * 30 : 0);
        } else {
            return false;
        }
    }


    
    protected function getUser()
    {
        if ($this->_user === null) {
            $this->_user = Users::findUserByEmail($this->username);
          }

        return $this->_user;
    }
    public function attributeLabels()
    {
        return [
            'username' => Yii::t('app','Email'),
            'password' => Yii::t('app','Password'),

       ];
    }
}
