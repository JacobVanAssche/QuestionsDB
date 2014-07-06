<?php

/**
 * This is the model class for table "tmp_1".
 *
 * The followings are the available columns in table 'tmp_1':
 * @property string $Header
 * @property string $QuestionText
 *
 * The followings are the available model relations:
 * @property Tmp2[] $tmp2s
 * @property Tmp3[] $tmp3s
 * @property Tmp4[] $tmp4s
 */
class Tmp1 extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Tmp1 the static model class
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
		return 'tmp_1';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('Header', 'required'),
			array('Header', 'length', 'max'=>128),
			array('QuestionText', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('Header, QuestionText', 'safe', 'on'=>'search'),
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
			'tmp2s' => array(self::HAS_MANY, 'Tmp2', 'Header'),
			'tmp3s' => array(self::HAS_MANY, 'Tmp3', 'Header'),
			'tmp4s' => array(self::HAS_MANY, 'Tmp4', 'Header'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'Header' => 'Header',
			'QuestionText' => 'Question Text',
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
		$criteria->compare('QuestionText',$this->QuestionText,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}