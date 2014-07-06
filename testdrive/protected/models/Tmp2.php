<?php

/**
 * This is the model class for table "tmp_2".
 *
 * The followings are the available columns in table 'tmp_2':
 * @property string $Header
 * @property string $Part
 * @property string $OutOf
 *
 * The followings are the available model relations:
 * @property Tmp1 $header
 * @property Tmp5[] $tmp5s
 * @property Tmp5[] $tmp5s1
 */
class Tmp2 extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Tmp2 the static model class
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
		return 'tmp_2';
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
			array('Part, OutOf', 'length', 'max'=>10),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('Header, Part, OutOf', 'safe', 'on'=>'search'),
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
			'header' => array(self::BELONGS_TO, 'Tmp1', 'Header'),
			'tmp5s' => array(self::HAS_MANY, 'Tmp5', 'Header'),
			'tmp5s1' => array(self::HAS_MANY, 'Tmp5', 'Part'),
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
			'OutOf' => 'Out Of',
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
		$criteria->compare('OutOf',$this->OutOf,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}