<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "hts_roles".
 *
 * @property int $id
 * @property string $name
 * @property string $comments
 * @property string $priviliges
 * @property string $created_date
 */
class Roles extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'hts_roles';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'comments'], 'required'],
            [['priviliges'], 'string'],
            [['created_date'], 'safe'],
            [['name', 'comments'], 'string', 'max' => 100],
            
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => Yii::t('app','Role'),
            'comments' => Yii::t('app','Description'),
            'priviliges' => 'Priviliges',
            'created_date' => Yii::t('app','Date')
        ];
    }
}
