<?php

/**
 * This is the model class for table "Answers".
 *
 * The followings are the available columns in table 'Answers':
 * @property string $Student
 * @property string $Assignment
 * @property string $Question
 * @property string $Part
 * @property string $Answer
 *
 * The followings are the available model relations:
 * @property Questions $question
 * @property Students $student
 * @property Assignments $assignment
 */
class Answers extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Answers the static model class
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
		return 'Answers';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('Student, Assignment, Question, Answer', 'required'),
			array('Student', 'length', 'max'=>45),
			array('Assignment, Question, Part, Answer', 'length', 'max'=>10),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('Student, Assignment, Question, Part, Answer', 'safe', 'on'=>'search'),
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
			'question' => array(self::BELONGS_TO, 'Questions', 'Question'),
			'student' => array(self::BELONGS_TO, 'Students', 'Student'),
			'assignment' => array(self::BELONGS_TO, 'Assignments', 'Assignment'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'Student' => 'Student',
			'Assignment' => 'Assignment',
			'Question' => 'Question',
			'Part' => 'Part',
			'Answer' => 'Answer',
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
		$criteria->compare('Assignment',$this->Assignment,true);
		//$criteria->compare('Question',$this->Question,true);
		//$criteria->compare('Part',$this->Part,true);
		//$criteria->compare('Answer',$this->Answer,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}