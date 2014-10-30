<?php

class EnrolledInClass extends CFormModel
{
	public $StudentID;
	public $LastName;
	public $FirstName;
	public $Status;
	public $StatusChange;
	public $Username;
	public $Email;
	public $Notes;

	public function rules()
	{
		return array(
				array('StudentID, LastName, FirstName, Status, StatusChange, Username, Email, Notes','safe','on'=>'search')
		);
	}

	public function getSqlDataProvider($classID)
	{

		$Condition = "";
		
		if (!empty($this->StudentID))
		{
			$Condition = " AND StudentID REGEXP '" . $this->StudentID . "'";
		}
		
		if (!empty($this->LastName))
		{
			$Condition = " AND LastName REGEXP '" . $this->LastName . "'";
		}
		if (!empty($this->FirstName))
		{
			$Condition = " AND FirstName REGEXP '" . $this->FirstName . "'";
		}
		if (!empty($this->Status))
		{
			$Condition = " AND Status REGEXP '" . $this->Status . "'";
		}
		if (!empty($this->StatusChange))
		{
			$Condition = " AND StatusChange REGEXP '" . $this->StatusChange . "'";
		}
		if (!empty($this->Username))
		{
			$Condition = " AND Username REGEXP '" . $this->Username . "'";
		}
		if (!empty($this->Email))
		{
			$Condition = " AND Email REGEXP '" . $this->Email . "'";
		}
		if (!empty($this->Notes))
		{
			$Condition = " AND Notes REGEXP '" . $this->Notes . "'";
		}
		
		$query = "SELECT StudentID, LastName, FirstName, Status, StatusChange, Username, Email, Notes
		  FROM EnrolledIn as e
		  JOIN Students as s
		  ON e.Student = s.StudentID
		  WHERE Class = " . $classID . 
		  $Condition;

		$dataProvider = new CSqlDataProvider($query, array(
		'keyField' => 'StudentID',
		'pagination'=>array(
				'pageSize'=>10000,)
		));
		
		return $dataProvider;
	}
}

?>