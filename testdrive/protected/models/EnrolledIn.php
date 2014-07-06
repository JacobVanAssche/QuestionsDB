<?php

/**
 * This is the model class for table "EnrolledIn".
 *
 * The followings are the available columns in table 'EnrolledIn':
 * @property string $Student
 * @property string $Class
 * @property string $Status
 * @property string $StatusChange
 */
class EnrolledIn extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return EnrolledIn the static model class
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
		return 'EnrolledIn';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('Student, Class, Status', 'required'),
			array('Student', 'length', 'max'=>45),
			array('Class, Status', 'length', 'max'=>10),
			array('StatusChange', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('Student, Class, Status, StatusChange', 'safe', 'on'=>'search'),
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
			'Student' => 'Student',
			'Class' => 'Class',
			'Status' => 'Status',
			'StatusChange' => 'Status Change',
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

		$criteria->compare('Student',$this->Student,true);
		$criteria->compare('Class',$this->Class,true);
		$criteria->compare('Status',$this->Status,true);
		$criteria->compare('StatusChange',$this->StatusChange,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}