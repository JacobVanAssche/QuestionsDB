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
class AllClasses extends CFormModel
{
	public $Name;
	public $Number;
	public $Semester;
	public $Year;
	public $Section;
	
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
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'Name' => 'Name',
			'Number' => 'Number',
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

		$criteria->compare('Name',$this->Name,true);
		$criteria->compare('Number',$this->Number,true);
		$criteria->compare('Semester',$this->Semester,true);
		$criteria->compare('Year',$this->Year,true);
		$criteria->compare('Section',$this->Section,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}