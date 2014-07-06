<?php

/**
 * This is the model class for table "Assignments".
 *
 * The followings are the available columns in table 'Assignments':
 * @property string $AssignmentID
 * @property string $Class
 * @property string $Title
 * @property string $Version
 * @property string $TexFile
 * @property string $Date
 * @property string $Comments
 *
 * The followings are the available model relations:
 * @property Answers[] $answers
 * @property Classes $class
 * @property Questionsforassignments[] $questionsforassignments
 */
class Assignments extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Assignments the static model class
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
		return 'Assignments';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('Class, Title', 'required'),
			array('Class', 'length', 'max'=>10),
			array('Title', 'length', 'max'=>100),
			array('Version', 'length', 'max'=>15),
			array('TexFile, Date, Comments', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('AssignmentID, Class, Title, Version, TexFile, Date, Comments', 'safe', 'on'=>'search'),
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
			'answers' => array(self::HAS_MANY, 'Answers', 'Assignment'),
			'class' => array(self::BELONGS_TO, 'Classes', 'Class'),
			'questionsforassignments' => array(self::HAS_MANY, 'Questionsforassignments', 'Assignment'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'AssignmentID' => 'Assignment',
			'Class' => 'Class',
			'Title' => 'Title',
			'Version' => 'Version',
			'TexFile' => 'Tex File',
			'Date' => 'Date',
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

		$criteria->compare('AssignmentID',$this->AssignmentID,true);
		$criteria->compare('Class',$this->Class,true);
		$criteria->compare('Title',$this->Title,true);
		$criteria->compare('Version',$this->Version,true);
		$criteria->compare('TexFile',$this->TexFile,true);
		$criteria->compare('Date',$this->Date,true);
		$criteria->compare('Comments',$this->Comments,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}