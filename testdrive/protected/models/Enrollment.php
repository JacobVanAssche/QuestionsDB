<?php
class Enrollment extends CFormModel
{
	public $StudentID;
	public $LastName;
	public $FirstName;
	public $CourseName;
	public $CourseNumber;
	public $Semester;
	public $Year;
	public $Status;
	public $Section;

	public function rules()
	{
		return array(
				array('StudentID, LastName, FirstName, CourseName, CourseNumber, Semester, Year, Status','safe','on'=>'search')
		);
	}

	public function getSqlDataProvider($Semester, $Year)
	{

		$Condition = " WHERE class.Semester =\"" . $Semester . "\" AND class.Year =\"" . $Year . "\"";
		
		if (!empty($this->StudentID))
		{
			$Condition .= " AND Students.StudentID =\"" . $this->StudentID . "\"";
		}
		else if (!empty($this->LastName))
		{
			$Condition .= " AND Students.LastName =\"" . $this->LastName . "\"";
		}
		else if (!empty($this->FirstName))
		{
			$Condition .= " AND Students.FirstName =\"" . $this->FirstName . "\"";
		}
		else if (!empty($this->CourseName))
		{
			$Condition .= " AND course.Name =\"" . $this->CourseName . "\"";
		}
		else if (!empty($this->CourseNumber))
		{
			$Condition .= " AND course.Number =\"" . $this->CourseNumber . "\"";
		}
		else if (!empty($this->Semester))
		{
			$Condition .= " AND class.Semester =\"" . $this->Semester . "\"";
		}
		else if (!empty($this->Year))
		{
			$Condition .= " AND class.Year =\"" . $this->Year . "\"";
		}
		else if (!empty($this->Status))
		{
			$Condition .= " AND enrolled.status =\"" . $this->Status . "\"";
		}
		else if (!empty($this->Section))
		{
			$Condition .= " AND class.Section =\"" . $this->Section . "\"";
		}
				 
		
		$query = "SELECT	Students.StudentID, Students.LastName, Students.FirstName, 
			course.Name as CourseName, course.Number as CourseNumber, class.Section, class.Semester, 
			class.Year, enrolled.Status
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