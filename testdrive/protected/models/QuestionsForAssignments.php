<?php

/**
 * This is the model class for table "QuestionsForAssignments".
 *
 * The followings are the available columns in table 'QuestionsForAssignments':
 * @property string $Assignment
 * @property string $Header
 * @property string $Question
 * @property string $Part
 * @property string $OutOf
 * @property string $GradingComments
 */
class QuestionsForAssignments extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return QuestionsForAssignments the static model class
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
		return 'QuestionsForAssignments';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('Assignment, Header, Question, OutOf', 'required'),
			array('Assignment, Question, Part, OutOf', 'length', 'max'=>10),
			array('Header', 'length', 'max'=>128),
			array('GradingComments', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('Assignment, Header, Question, Part, OutOf, GradingComments', 'safe', 'on'=>'search'),
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
			'Assignment' => 'Assignment',
			'Header' => 'Header',
			'Question' => 'Question',
			'Part' => 'Part',
			'OutOf' => 'Out Of',
			'GradingComments' => 'Grading Comments',
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

		$criteria->compare('Assignment',$this->Assignment,true);
		$criteria->compare('Header',$this->Header,true);
		$criteria->compare('Question',$this->Question,true);
		$criteria->compare('Part',$this->Part,true);
		$criteria->compare('OutOf',$this->OutOf,true);
		$criteria->compare('GradingComments',$this->GradingComments,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}