<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "hts_helppage".
 *
 * @property int $id
 * @property string $page
 * @property string $pageContent
 * @property string $slug
 */
class Help extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */

    public $helppageContent;
    public $helppagelang;
    public static function tableName()
    {
        return 'hts_helppages';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['page', 'pageContent'], 'required'],
            [['pageContent'], 'string'],
            
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'page' => 'Page',
            'pageContent' => 'Page Content',
           
        ];
    }
}
