<?php

class AllExamAverages extends CFormModel
{
	public $AssignmentID;
	public $Semester;
	public $Year;
	public $CourseName;
	public $CourseNumber;
	public $Section;
	public $Title;
	public $MaximumPoints;
	public $Average;
	public $AveragePercentage;
	public $ParticipationPercentage;

	public function rules()
	{
		return array(
				array('AssignmentID, Semester, Year, Section, CourseName, CourseNumber, Title, MaximumPoints, Average, AveragePercentage, ParticipationPercentage','safe','on'=>'search')
		);
	}

	public function getSqlDataProvider($Semester, $Year)
	{

		$Condition = "";
		
		if (!empty($this->CourseName))
		{
			$Condition .= " AND co.Name REGEXP '" . $this->CourseName . "'";
		}
		if (!empty($this->CourseNumber))
		{
			$Condition .= " AND p.Number REGEXP '" . $this->CourseNumber . "'";
		}
		if (!empty($this->Title))
		{
			$Condition .= " AND a.Title REGEXP '" . $this->Title . "'";
		}
		if (!empty($this->Section))
		{
			$Condition .= " AND cl.Section REGEXP '" . $this->Section . "'";
		}

		
		$query = "
				SELECT 			cl.Semester, cl.Year, co.Name as CourseName, co.Number as CourseNumber, cl.Section, a.Title as Title, a.AssignmentID, SUM(q.OutOf) as MaximumPoints, e.ExamAverage, 
								(e.ExamAverage/SUM(q.OutOf))*100 as AveragePercentage, p.ParticipationPercentage
				FROM 			QuestionsForAssignments as q
				JOIN 
				(
					SELECT 		a.Assignment, (SUM(Answer)+MC.MCScore)/COUNT(DISTINCT Student) as ExamAverage
					FROM 		Answers as a
					JOIN (SELECT a.Assignment, IFNULL(SUM(q.OutOf), 0) as MCScore
					FROM Answers as a
					LEFT JOIN PotentialAnswers as p
					ON a.Question = p.Question
					AND a.Answer = p.PotentialAnswer
					AND a.Part = p.Part
					AND p.CorrectAnswer = 1
					LEFT JOIN QuestionsForAssignments as q
					ON 	 p.Question = q.Question
					AND  p.Part = q.Part
					GROUP BY a.Assignment) as MC
					ON a.Assignment = MC.Assignment
				
					JOIN 	Assignments as assign
					ON 		a.Assignment = assign.AssignmentID
					AND     a.Student IN (SELECT Student FROM EnrolledIn WHERE Class = assign.Class AND Status = 'Enrolled')
							
					GROUP BY 	a.Assignment
					ORDER BY 	a.Assignment
				) as e
				ON		    e.Assignment = q.Assignment
				
				JOIN 	Assignments as a
				ON 		e.Assignment = a.AssignmentID
				
				JOIN 	Classes as cl
				ON		a.Class = cl.ClassID
				
				JOIN 	Courses as co 
				ON 		cl.Course = co.CourseID
				
				JOIN 
				(
				SELECT			co.Name, co.Number, c.Section, Assignments.Title, (COUNT(DISTINCT a.Student)/e.TotalStudents)*100 as ParticipationPercentage
				FROM				Answers as a
				
				JOIN 				(
										SELECT Class, COUNT(Student) as TotalStudents
										FROM EnrolledIn WHERE Status = 'Enrolled' AND Class IN 
											(
												SELECT ClassID FROM Classes
												WHERE Semester = '" . $Semester . "'
												AND Year = '" . $Year . "'
											) 
										GROUP BY Class
									) as e
				JOIN 				Assignments
				ON					a.Assignment = Assignments.AssignmentID
				AND 				e.Class = Assignments.Class
				
				JOIN 				Classes as c
				ON 					Assignments.Class = c.ClassID
				JOIN 				Courses as co
				ON 					c.Course = co.CourseID
				
				WHERE a.Student IN (SELECT Student FROM EnrolledIn WHERE Class = e.Class AND Status = 'Enrolled')
				
				GROUP BY 		co.Name, co.Number, c.Section, a.Assignment, Assignments.Title
				) as p
				ON p.Title = a.Title
				AND p.Name = co.Name
				AND p.Number = co.Number
				AND p.Section = cl.Section
				" . $Condition . "
				GROUP BY 	co.Name, co.Number, cl.Semester, cl.Year, cl.Section, a.AssignmentID
				ORDER BY co.Number, a.Title
				";
		
		$dataProvider = new CSqlDataProvider($query, array(
				'keyField' => 'CourseNumber',
				'pagination'=>array(
						'pageSize'=>10000,)
		));
		
		return $dataProvider;
	}
}

?>