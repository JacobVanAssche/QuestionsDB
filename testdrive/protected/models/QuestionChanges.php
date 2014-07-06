<?php

/**
 * This is the model class for table "QuestionChanges".
 *
 * The followings are the available columns in table 'QuestionChanges':
 * @property string $OldQuestionID
 * @property string $NewQuestionID
 * @property string $Change
 */
class QuestionChanges extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return QuestionChanges the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'QuestionChanges';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('OldQuestionID, NewQuestionID', 'required'),
			array('OldQuestionID, NewQuestionID', 'length', 'max'=>10),
			array('Change', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('OldQuestionID, NewQuestionID, Change', 'safe', 'on'=>'search'),
		);
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
		return array(
			'OldQuestionID' => 'Old Question',
			'NewQuestionID' => 'New Question',
			'Change' => 'Change',
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
	 */
	public function search()
	{
		// Warning: Please modify the following code to remove attributes that
		// should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('OldQuestionID',$this->OldQuestionID,true);
		$criteria->compare('NewQuestionID',$this->NewQuestionID,true);
		$criteria->compare('Change',$this->Change,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}