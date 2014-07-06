<?php

/**
 * This is the model class for table "tmp_5".
 *
 * The followings are the available columns in table 'tmp_5':
 * @property string $Header
 * @property string $Part
 * @property string $PotentialAnswer
 * @property string $PotentialAnswerText
 * @property integer $CorrectAnswer
 *
 * The followings are the available model relations:
 * @property Tmp2 $header
 * @property Tmp2 $part
 */
class Tmp5 extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Tmp5 the static model class
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
		return 'tmp_5';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('Header, PotentialAnswer', 'required'),
			array('CorrectAnswer', 'numerical', 'integerOnly'=>true),
			array('Header', 'length', 'max'=>128),
			array('Part', 'length', 'max'=>10),
			array('PotentialAnswer', 'length', 'max'=>50),
			array('PotentialAnswerText', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('Header, Part, PotentialAnswer, PotentialAnswerText, CorrectAnswer', 'safe', 'on'=>'search'),
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
			'header' => array(self::BELONGS_TO, 'Tmp2', 'Header'),
			'part' => array(self::BELONGS_TO, 'Tmp2', 'Part'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'Header' => 'Header',
			'Part' => 'Part',
			'PotentialAnswer' => 'Potential Answer',
			'PotentialAnswerText' => 'Potential Answer Text',
			'CorrectAnswer' => 'Correct Answer',
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

		$criteria->compare('Header',$this->Header,true);
		$criteria->compare('Part',$this->Part,true);
		$criteria->compare('PotentialAnswer',$this->PotentialAnswer,true);
		$criteria->compare('PotentialAnswerText',$this->PotentialAnswerText,true);
		$criteria->compare('CorrectAnswer',$this->CorrectAnswer);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}