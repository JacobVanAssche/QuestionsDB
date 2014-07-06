<?php
class StudentEnrollment extends CFormModel
{
	public $StudentID;
	public $LastName;
	public $FirstName;
	public $CourseName;
	public $CourseNumber;
	public $Semester;
	public $Year;
	public $Status;
	public $StatusChange;
	public $Section;

	public function rules()
	{
		return array(
				array('StudentID, LastName, FirstName, CourseName, CourseNumber, Semester, Year, Status','safe','on'=>'search')
		);
	}

	public function getSqlDataProvider($Semester, $Year, $StudentID)
	{
		$Condition = " WHERE Students.StudentID ='" . $StudentID ."'";
		
		if (strpos($Semester, "previous") !== FALSE)
		{
			$Semester = substr($Semester, 0, strpos($Semester, "previous"));
			$Condition .= " AND class.Year !='" . $Year . "'
					   AND class.Semester !='" . $Semester . "'";
		}
		else
		{
			$Condition .= " AND class.Year ='" . $Year . "'
					   AND class.Semester ='" . $Semester . "'";
		}

		if (!empty($this->StudentID))
		{
			$Condition .= " AND Students.StudentID REGEXP '" . $this->StudentID . "'";
		}
		if (!empty($this->LastName))
		{
			$Condition .= " AND Students.LastName REGEXP '" . $this->LastName . "'";
		}
		if (!empty($this->FirstName))
		{
			$Condition .= " AND Students.FirstName REGEXP '" . $this->FirstName . "'";
		}
		if (!empty($this->CourseName))
		{
			$Condition .= " AND course.Name REGEXP '" . $this->CourseName . "'";
		}
		if (!empty($this->CourseNumber))
		{
			$Condition .= " AND course.Number REGEXP '" . $this->CourseNumber . "'";
		}
		if (!empty($this->Semester))
		{
			$Condition .= " AND class.Semester REGEXP '" . $this->Semester . "'";
		}
		if (!empty($this->Year))
		{
			$Condition .= " AND class.Year REGEXP '" . $this->Year . "'";
		}
		if (!empty($this->Status))
		{
			$Condition .= " AND enrolled.Status REGEXP '" . $this->Status . "'";
		}
		if (!empty($this->StatusChange))
		{
			$Condition .= " AND enrolled.StatusChange REGEXP '" . $this->StatusChange . "'";
		}
		if (!empty($this->Section))
		{
			$Condition .= " AND class.Section REGEXP '" . $this->Section . "'";
		}
				 
		
		$query = "SELECT	Students.StudentID, Students.LastName, Students.FirstName, 
			course.Name as CourseName, course.Number as CourseNumber, class.Section, class.Semester, 
			class.Year, enrolled.Status, enrolled.StatusChange
			FROM 	Students
			JOIN	EnrolledIn as enrolled
			ON 	 	Students.StudentID = enrolled.Student
			JOIN   Classes as class
			ON 	   ClassID = enrolled.Class
			JOIN   Courses as course
			ON	   CourseID = class.Course " . $Condition . " 
			ORDER BY enrolled.status asc, course.Number, Students.LastName";
		
		$dataProvider = new CSqlDataProvider($query, array(
				'keyField' => 'StudentID',
				'pagination'=>array(
						'pageSize'=>10000,)
		));
		
		return $dataProvider;
	}
}

?>