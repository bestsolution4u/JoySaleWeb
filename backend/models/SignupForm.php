<?php
namespace backend\models;

use yii\base\Model;
use common\models\Users;


class SignupForm extends Model
{
    public $username;
    public $email;
    public $password;
    public $name;
    public  $userstatus;
    public  $activationStatus;

    public function rules()
    {
        return [
            ['name', 'trim'],
            ['name', 'required'],
            ['name', 'string', 'min' => 3, 'max' => 30],

            ['username', 'trim'],
            ['username', 'required'],
          
            ['username', 'string', 'min' => 2, 'max' => 255],
            ['name','match', 'not' => true, 'pattern' => '/[^a-zA-Z \s\u0600-\u06ff\u0750-\u077f\ufb50-\ufc3f\ufe70-\ufefc]/',
'message' => 'Special characters or numbers not allowed.',
],
           ['username','match', 'not' => true, 'pattern' => '/[^a-zA-Z0-9_\s\u0600-\u06ff\u0750-\u077f\ufb50-\ufc3f\ufe70-\ufefc]/',
'message' => 'Special characters or space not allowed.',
],
  ['username', 'unique', 'targetClass' => '\common\models\Users', 'message' => 'This username has already been taken.'],
            ['email', 'trim'],
            ['email', 'required'],
            ['email', 'email'],
            ['email', 'string', 'max' => 255],
            ['email', 'unique', 'targetClass' => '\common\models\Users', 'message' => 'This email address has already been taken.'],

            ['password', 'required'],
            ['password', 'string', 'min' => 6],
            [['userstatus','activationStatus'],'integer']

         
            
        ];
    }

  
    public function signup()
    {
        if (!$this->validate()) {
            return null;
        }
        $user = new Users();
        $user->userstatus = 1;
        $user->activationStatus = 1;
        $user->username = $this->username;
        $user->email = $this->email;
        $user->name = $this->name;
        $user->setPassword($this->password);
        $user->generateAuthKey();
        
        return $user->save() ? $user : null;
    }
}
