<?php
class StudentExamPercentages extends CFormModel
{
	public $CourseName;
	public $CourseNumber;
	public $Semester;
	public $Year;
	public $Section;
	public $Title;
	public $Score;
	public $MaximumPoints;
	public $Percentage;
	public $Assignment;

	public function rules()
	{
		return array(
				array('Assignment, CourseName, CourseNumber, Semester, Year, Section, Title, Score, MaximumPoints, Percentage','safe','on'=>'search')
		);
	}

	public function getSqlDataProvider($Semester, $Year, $StudentID)
	{
		$Condition = " WHERE a.Student ='" . $StudentID ."' 
				       AND c.Year ='" . $Year . "'
					   AND c.Semester ='" . $Semester . "'";
		
		if (!empty($this->CourseName))
		{
			$Condition .= " AND co.Name REGEXP '" . $this->CourseName . "'";
		}
		if (!empty($this->Semester))
		{
			$Condition .= " AND c.Semester REGEXP '" . $this->Semester . "'";
		}
		if (!empty($this->Year))
		{
			$Condition .= " AND c.Year REGEXP '" . $this->Year . "'";
		}
		if (!empty($this->Title))
		{
			$Condition .= " AND assign.Title REGEXP '" . $this->Title . "'";
		}
		if (!empty($this->Score))
		{
			$Condition .= " AND (SUM(a.Answer)+MC.MCScore) REGEXP '" . $this->Score . "'";
		}
		if (!empty($this->MaximumPoints))
		{
			$Condition .= " AND q.MaximumPoints REGEXP '" . $this->MaximumPoints . "'";
		}
		if (!empty($this->Percentage))
		{
			$Condition .= " AND Percentage REGEXP '" . $this->Percentage . "'";
		}
		
		
		$query = "
					SELECT a.Assignment, co.Name as CourseName, co.Number as CourseNumber, c.Section, c.Semester, c.Year, 
				           assign.Title, (SUM(a.Answer)+MC.MCScore) as Score, q.MaximumPoints as MaximumPoints, 
				           ((SUM(a.Answer)+MC.MCScore)/q.MaximumPoints)*100 as Percentage
					FROM 		Answers as a
					JOIN 		Students as s
					ON 			a.Student = s.StudentID
					#Add the correct multiple choice questions to exam total
					JOIN 
					(
						SELECT a.Assignment, IFNULL(SUM(q.OutOf), 0) as MCScore
						FROM Answers as a
						LEFT JOIN PotentialAnswers as p
						ON a.Question = p.Question
						AND a.Answer = p.PotentialAnswer
						AND a.Part = p.Part
						AND p.CorrectAnswer = 1
						LEFT JOIN QuestionsForAssignments as q
						ON 	 p.Question = q.Question
						AND  p.Part = q.Part
						WHERE a.Student ='" . $StudentID . "'
						GROUP BY a.Assignment
					) as MC
					ON MC.Assignment = a.Assignment
					
					JOIN 
					(
						SELECT		Assignment, SUM(OutOf) as MaximumPoints
						FROM			QuestionsForAssignments 
						GROUP BY 	Assignment
					) as q
					ON q.Assignment = a.Assignment
					
					JOIN
					(
						SELECT AssignmentID, Title, Class
						FROM 	 Assignments
					) as assign
					ON assign.AssignmentID = a.Assignment
					
					JOIN Classes as c
					ON assign.Class = c.ClassID
					
					JOIN Courses as co
					ON co.CourseID = c.Course
					" . $Condition . "
					GROUP BY a.Assignment
					ORDER BY c.Year desc,c.Semester asc,co.Name asc, assign.Title";
		
		$dataProvider = new CSqlDataProvider($query, array(
				'keyField' => 'CourseName',
				'pagination'=>array(
						'pageSize'=>10000,)
		));
		
		
		return $dataProvider;
	}
}

?>