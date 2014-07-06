<?php

/**
 * This is the model class for table "Questions".
 *
 * The followings are the available columns in table 'Questions':
 * @property string $QuestionID
 * @property string $QuestionText
 * @property string $SolutionText
 * @property string $Comments
 * @property string $Timestamp
 *
 * The followings are the available model relations:
 * @property Answers[] $answers
 * @property Links[] $links
 * @property Potentialanswerchanges[] $potentialanswerchanges
 * @property Potentialanswers[] $potentialanswers
 * @property Questionchanges[] $questionchanges
 * @property Questionchanges[] $questionchanges1
 * @property Questionparts[] $questionparts
 * @property Questionsforassignments[] $questionsforassignments
 * @property Tags[] $tags
 * @property Texcommands[] $texcommands
 */
class Questions extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Questions the static model class
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
		return 'Questions';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('Timestamp', 'required'),
			array('QuestionText, SolutionText, Comments', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('QuestionID, QuestionText, SolutionText, Comments, Timestamp', 'safe', 'on'=>'search'),
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
			'answers' => array(self::HAS_MANY, 'Answers', 'Question'),
			'links' => array(self::HAS_MANY, 'Links', 'Question'),
			'potentialanswerchanges' => array(self::HAS_MANY, 'Potentialanswerchanges', 'NewQuestionID'),
			'potentialanswers' => array(self::HAS_MANY, 'Potentialanswers', 'Question'),
			'questionchanges' => array(self::HAS_MANY, 'Questionchanges', 'OldQuestionID'),
			'questionchanges1' => array(self::HAS_MANY, 'Questionchanges', 'NewQuestionID'),
			'questionparts' => array(self::HAS_MANY, 'Questionparts', 'Question'),
			'questionsforassignments' => array(self::HAS_MANY, 'Questionsforassignments', 'Question'),
			'tags' => array(self::MANY_MANY, 'Tags', 'tagsforquestions(Question, Tag)'),
			'texcommands' => array(self::HAS_MANY, 'Texcommands', 'Question'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'QuestionID' => 'Question',
			'QuestionText' => 'Question Text',
			'SolutionText' => 'Solution Text',
			'Comments' => 'Comments',
			'Timestamp' => 'Timestamp',
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

		$criteria->compare('QuestionID',$this->QuestionID,true);
		$criteria->compare('QuestionText',$this->QuestionText,true);
		$criteria->compare('SolutionText',$this->SolutionText,true);
		$criteria->compare('Comments',$this->Comments,true);
		$criteria->compare('Timestamp',$this->Timestamp,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}