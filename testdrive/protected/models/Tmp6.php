<?php

/**
 * This is the model class for table "tmp_6".
 *
 * The followings are the available columns in table 'tmp_6':
 * @property string $Title
 * @property string $Version
 * @property string $CourseName
 * @property string $Section
 * @property string $Semester
 * @property string $Year
 * @property string $TexFile
 * @property string $Date
 *
 * The followings are the available model relations:
 * @property Semesters $semester
 * @property Semesters $year
 */
class Tmp6 extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Tmp6 the static model class
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
		return 'tmp_6';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('Title', 'length', 'max'=>100),
			array('Title', 'required'),
			array('CourseName', 'length', 'max'=>50),
			array('CourseName', 'required'),
			array('Section', 'length', 'max'=>3),
			array('Semester', 'length', 'max'=>6),
			array('Semester', 'required'),
			array('Year', 'length', 'max'=>4),
			array('Year', 'required'),
			array('TexFile, Date, Version', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			// array('Title, CourseName, Section, Semester, Year, TexFile, Date', 'safe', 'on'=>'search'),
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
			'semester' => array(self::BELONGS_TO, 'Semesters', 'Semester'),
			'year' => array(self::BELONGS_TO, 'Semesters', 'Year'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'Title' => 'Title',
			'CourseName' => 'Course Name',
			'Section' => 'Section',
			'Semester' => 'Semester',
			'Year' => 'Year',
			'TexFile' => 'Tex File',
			'Date' => 'Date',
			'Version' => 'Version',
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

		//$criteria->compare('Title',$this->Title,true);
		//$criteria->compare('CourseName',$this->CourseName,true);
		//$criteria->compare('Section',$this->Section,true);
		//$criteria->compare('Semester',$this->Semester,true);
		//$criteria->compare('Year',$this->Year,true);
		//$criteria->compare('TexFile',$this->TexFile,true);
		//$criteria->compare('Date',$this->Date,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}