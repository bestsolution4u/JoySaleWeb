<?php
namespace common\models;
use Yii;
/**
 * This is the model class for table "hts_resetpassword".
 *
 * The followings are the available columns in table 'hts_resetpassword':
 * @property integer $resetId
 * @property integer $userId
 * @property string $resetData
 * @property integer $createdDate
 */
class Resetpassword extends \yii\db\ActiveRecord
{
	public $resetpassword;
	public $confirmpassword;
	/**
	 * @return string the associated database table name
	 */
	public static function tableName()
	{
		return 'hts_resetpassword';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return [
			['userId, resetData, createdDate', 'required'],
			//array('userId, createdDate', 'integerOnly'=>true),
			//array('resetData', 'length', 'max'=>50),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			//['resetId, userId, resetData, createdDate, resetpassword, confirmpassword', 'safe', 'on'=>'search'],
		];
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return [
			'resetId' => 'Reset',
			'userId' => 'User',
			'resetData' => 'Reset Data',
			'createdDate' => 'Created Date',
			// 'resetpassword' => Yii::t('app','New Password'),
			// 'confirmpassword' => Yii::t('app','Confirm Password'),
		];
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 *
	 * Typical usecase:
	 * - Initialize the model fields with values from filter form.
	 * - Execute this method to get CActiveDataProvider instance which will filter
	 * models according to data in model fields.
	 * - Pass data provider to CGridView, CListView or any similar widget.
	 *
	 * @return CActiveDataProvider the data provider that can return the models
	 * based on the search/filter conditions.
	 */
	public function search()
	{
		// @todo Please modify the following code to remove attributes that should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('resetId',$this->resetId);
		$criteria->compare('userId',$this->userId);
		$criteria->compare('resetData',$this->resetData,true);
		$criteria->compare('createdDate',$this->createdDate);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Resetpassword the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
