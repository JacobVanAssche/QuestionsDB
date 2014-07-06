<?php

/**
 * This is the model class for table "PotentialAnswers".
 *
 * The followings are the available columns in table 'PotentialAnswers':
 * @property string $Question
 * @property string $Part
 * @property string $PotentialAnswer
 * @property string $PotentialAnswerText
 * @property integer $CorrectAnswer
 * @property string $Comments
 */
class PotentialAnswers extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return PotentialAnswers the static model class
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
		return 'PotentialAnswers';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('Question, PotentialAnswer', 'required'),
			array('CorrectAnswer', 'numerical', 'integerOnly'=>true),
			array('Question, Part', 'length', 'max'=>10),
			array('PotentialAnswer', 'length', 'max'=>50),
			array('PotentialAnswerText, Comments', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('Question, Part, PotentialAnswer, PotentialAnswerText, CorrectAnswer, Comments', 'safe', 'on'=>'search'),
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
			'Question' => 'Question',
			'Part' => 'Part',
			'PotentialAnswer' => 'Potential Answer',
			'PotentialAnswerText' => 'Potential Answer Text',
			'CorrectAnswer' => 'Correct Answer',
			'Comments' => 'Comments',
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

		$criteria->compare('Question',$this->Question,true);
		$criteria->compare('Part',$this->Part,true);
		$criteria->compare('PotentialAnswer',$this->PotentialAnswer,true);
		$criteria->compare('PotentialAnswerText',$this->PotentialAnswerText,true);
		$criteria->compare('CorrectAnswer',$this->CorrectAnswer);
		$criteria->compare('Comments',$this->Comments,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}