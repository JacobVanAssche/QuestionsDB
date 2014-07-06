<?php

/**
 * This is the model class for table "Courses".
 *
 * The followings are the available columns in table 'Courses':
 * @property string $CourseID
 * @property string $Name
 * @property string $Number
 * @property string $University
 * @property integer $Credits
 * @property string $Level
 *
 * The followings are the available model relations:
 * @property Classes[] $classes
 * @property Universities $university
 */
class Courses extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Courses the static model class
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
		return 'Courses';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('Name, Number', 'required'),
			array('Credits', 'numerical', 'integerOnly'=>true),
			array('Name, Number', 'length', 'max'=>50),
			array('University', 'length', 'max'=>200),
			array('Level', 'length', 'max'=>13),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('CourseID, Name, Number, University, Credits, Level', 'safe', 'on'=>'search'),
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
			'classes' => array(self::HAS_MANY, 'Classes', 'Course'),
			'university' => array(self::BELONGS_TO, 'Universities', 'University'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'CourseID' => 'Course',
			'Name' => 'Name',
			'Number' => 'Number',
			'University' => 'University',
			'Credits' => 'Credits',
			'Level' => 'Level',
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

		$criteria->compare('CourseID',$this->CourseID,true);
		$criteria->compare('Name',$this->Name,true);
		$criteria->compare('Number',$this->Number,true);
		$criteria->compare('University',$this->University,true);
		$criteria->compare('Credits',$this->Credits);
		$criteria->compare('Level',$this->Level,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}