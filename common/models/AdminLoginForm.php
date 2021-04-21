<?php
namespace common\models;
use common\models\Admin;
use Yii;
use yii\base\Model;

class AdminLoginForm extends Model
{
    public $username;
    public $password;
    public $rememberMe = true;
    private $_user;


    
    public function rules()
    {
        return [
            [['username', 'password'], 'required'],
            ['rememberMe', 'boolean'],
            ['password', 'validatePassword'],
        ];
    }

  
    public function validatePassword($attribute, $params)
    {
        if (!$this->hasErrors()) {
            $user = $this->getUser();
            if (!$user || !$user->validatePassword($this->password)) {
                $this->addError($attribute, Yii::t('app','Incorrect email or password'));
            }
        }
    }

 
    public function login()
    {
        if ($this->validate()) {
            return Yii::$app->user->login($this->getUser(), $this->rememberMe ? 3600 * 24 * 30 : 0);
        }
        
        return false;
    }


    
    protected function getUser()
    {
        if ($this->_user === null) {
           return  $this->_user = Admin::findByUsername($this->username);
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
