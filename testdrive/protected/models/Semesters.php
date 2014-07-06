<?php

/**
 * This is the model class for table "Semesters".
 *
 * The followings are the available columns in table 'Semesters':
 * @property string $Semester
 * @property string $Year
 * @property string $StartDate
 * @property string $EndDate
 *
 * The followings are the available model relations:
 * @property Classes[] $classes
 * @property Classes[] $classes1
 * @property Tmp6[] $tmp6s
 * @property Tmp6[] $tmp6s1
 */
class Semesters extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Semesters the static model class
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
		return 'Semesters';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('Semester, Year, StartDate, EndDate', 'required'),
			array('Semester', 'length', 'max'=>6),
			array('Year', 'length', 'max'=>4),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('Semester, Year, StartDate, EndDate', 'safe', 'on'=>'search'),
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
			'classes' => array(self::HAS_MANY, 'Classes', 'Semester'),
			'classes1' => array(self::HAS_MANY, 'Classes', 'Year'),
			'tmp6s' => array(self::HAS_MANY, 'Tmp6', 'Semester'),
			'tmp6s1' => array(self::HAS_MANY, 'Tmp6', 'Year'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'Semester' => 'Semester',
			'Year' => 'Year',
			'StartDate' => 'Start Date',
			'EndDate' => 'End Date',
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

		$criteria->compare('Semester',$this->Semester,true);
		$criteria->compare('Year',$this->Year,true);
		$criteria->compare('StartDate',$this->StartDate,true);
		$criteria->compare('EndDate',$this->EndDate,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}