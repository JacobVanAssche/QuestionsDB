<?php

/**
 * This is the model class for table "Classes".
 *
 * The followings are the available columns in table 'Classes':
 * @property string $ClassID
 * @property string $Course
 * @property string $Semester
 * @property string $Year
 * @property string $Section
 *
 * The followings are the available model relations:
 * @property Assignments[] $assignments
 * @property Semesters $semester
 * @property Semesters $year
 * @property Courses $course
 * @property Students[] $students
 */
class Classes extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Classes the static model class
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
		return 'Classes';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('Course, Semester, Year', 'required'),
			array('Course', 'length', 'max'=>10),
			array('Semester', 'length', 'max'=>6),
			array('Year', 'length', 'max'=>4),
			array('Section', 'length', 'max'=>3),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('ClassID, Course, Semester, Year, Section', 'safe', 'on'=>'search'),
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
			'assignments' => array(self::HAS_MANY, 'Assignments', 'Class'),
			'semester' => array(self::BELONGS_TO, 'Semesters', 'Semester'),
			'year' => array(self::BELONGS_TO, 'Semesters', 'Year'),
			'course' => array(self::BELONGS_TO, 'Courses', 'Course'),
			'students' => array(self::MANY_MANY, 'Students', 'enrolledin(Class, Student)'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'ClassID' => 'Class',
			'Course' => 'Course',
			'Semester' => 'Semester',
			'Year' => 'Year',
			'Section' => 'Section',
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

		$criteria->compare('ClassID',$this->ClassID,true);
		$criteria->compare('Course',$this->Course,true);
		$criteria->compare('Semester',$this->Semester,true);
		$criteria->compare('Year',$this->Year,true);
		$criteria->compare('Section',$this->Section,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}