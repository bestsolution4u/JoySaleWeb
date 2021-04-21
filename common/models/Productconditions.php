<?php

namespace common\models;

use Yii;


class Productconditions extends \yii\db\ActiveRecord
{
   
    public static function tableName()
    {
        return 'hts_productconditions';
    }

   
    public function rules()
    {
        return [
            [['condition'], 'required'],
            [['condition'], 'string', 'min'=>3, 'max' => 60],
            [['condition'], 'unique', 'message' => Yii::t('app','This Product Conditions has already been taken')],
        ];
    }

   
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'condition' => Yii::t('app','Product Condition'),
        ];
    }
}
