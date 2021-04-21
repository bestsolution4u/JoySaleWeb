<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "hts_helppages".
 *
 * @property int $id
 * @property string $page
 * @property string $pageContent
 * @property string $slug
 */
class Helppages extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
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
            [['page', 'pageContent', 'slug'], 'required'],
            [['pageContent'], 'string'],
            [['page', 'slug'], 'string', 'max' => 60],
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
            'slug' => 'Slug',
        ];
    }
}
