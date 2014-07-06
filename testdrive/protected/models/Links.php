<?php

/**
 * This is the model class for table "Links".
 *
 * The followings are the available columns in table 'Links':
 * @property string $LinkID
 * @property string $URI
 * @property string $Question
 *
 * The followings are the available model relations:
 * @property Questions $question
 */
class Links extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Links the static model class
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
		return 'Links';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('URI', 'required'),
			array('URI', 'length', 'max'=>1024),
			array('Question', 'length', 'max'=>10),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('LinkID, URI, Question', 'safe', 'on'=>'search'),
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
			'question' => array(self::BELONGS_TO, 'Questions', 'Question'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'LinkID' => 'Link',
			'URI' => 'Uri',
			'Question' => 'Question',
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

		$criteria->compare('LinkID',$this->LinkID,true);
		$criteria->compare('URI',$this->URI,true);
		$criteria->compare('Question',$this->Question,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}