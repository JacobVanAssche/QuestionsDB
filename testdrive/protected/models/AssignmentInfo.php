<?php

class AssignmentInfo extends CFormModel
{
	// Participation
	public $TookExam;
	public $MissedExam;
	public $Participation;
	
	// Missing exam
	public $Username;
	public $Email;

	// Student Scores
	public $StudentID;
	public $LastName;
	public $FirstName;
	public $Version;
	public $Score;
	public $Total;
	public $Percent;
	
	// Assignment average
	public $ExamAverage;
	public $MaximumPoints;
	public $AssignmentAveragePercentage;
	
	// Questions
	public $Title;
	public $Question;
	public $Header;
	public $QuestionAverage;
	public $AveragePercentage;
	public $PotentialAnswer;
	public $CorrectAnswer;
	public $Percentage;
	
	// Problems
	public $Problem;
	public $ProblemAverage;
	public $ProblemAveragePercentage;

	public function rules()
	{
		return array(
				array('Part, Title, Problem, ProblemAverage, ProblemAveragePercentage, Question, Username, Email, TookExam, MissedExam, Participation, 
					   StudentID, LastName, FirstName, Version, Score, Total, Percent',
					    'safe','on'=>'search')
		);
	}
	
	public function participation($AssignmentID, $classID)
	{
		$con = Yii::App()->db;
		
		// Find the total number of students in the class
		$query = "SELECT 		COUNT(Student)
					FROM 		EnrolledIn
					WHERE 		Class = '" . $classID . "'
					AND 		Status = 'Enrolled'";
		$command = $con->createcommand($query);
		$TotalStudents = $command->queryScalar();
		
		// Find the number of students who took the exam
		$query = "SELECT 	Count(t.Student)
		 		  FROM 		(
								SELECT	Student, COUNT(Student)
								FROM	Answers
								WHERE 	Assignment = '" . $AssignmentID . "'
								AND 	Student IN 
							(
								SELECT 	Student FROM EnrolledIn WHERE Status = 'Enrolled' AND Class = '" . $classID . "'
							)
								GROUP BY 	Student
							) as 	t";
		$command = $con->createCommand($query);
		$TookExam = $command->queryScalar();
	
		$query = "SELECT '" . $TookExam . "' as TookExam, 
				('" . $TotalStudents . "'-'" . $TookExam . "') as MissedExam, 
				('" . $TookExam . "'/'" . $TotalStudents . "')*100 as Participation";
	
		$dataProvider = new CSqlDataProvider($query, array(
				'keyField' => 'Participation',
				'pagination'=>array(
						'pageSize'=>10000,)
		));
	
		return $dataProvider;
	}
	
	public function studentScores($AssignmentID, $classID, $Title)
	{
		$con = Yii::App()->db;
		
		// Find the version of the Assignment
		$query = "	SELECT 	Version
					FROM	Assignments as a
					WHERE 	AssignmentID = " . $AssignmentID;
		$command = $con->createcommand($query);
		$Version = $command->queryScalar();
		
		// Find the total points of the Assignment
		$query = "	SELECT 	SUM(OutOf)
					FROM 	QuestionsForAssignments
					WHERE  	Assignment = '" . $AssignmentID . "'";
		$command = $con->createcommand($query);
		$TotalPoints = $command->queryScalar();
	
		// Find the total number of students in the class
		$query = "SELECT 		COUNT(Student)
					FROM 		EnrolledIn
					WHERE 		Class = '" . $classID . "'
					AND 		Status = 'Enrolled'";
		$command = $con->createcommand($query);
		$TotalStudents = $command->queryScalar();
		
		
	
		$query = "	SELECT 		s.StudentID, s.LastName, s.FirstName, '" . $Version . "' as Version, (SUM(a.Answer)+MC.MCScore) as Score, '" . $TotalPoints . "' as Total, ((SUM(a.Answer)+MC.MCScore)/" . $TotalPoints . ")*100 as Percent
					FROM 		Answers as a
					JOIN 		Students as s
					ON 			a.Student = s.StudentID
					#Add the correct multiple choice questions to exam total
					JOIN (SELECT a.Student, IFNULL(SUM(q.OutOf), 0) as MCScore
					FROM Answers as a
					LEFT JOIN PotentialAnswers as p
					ON a.Question = p.Question
					AND a.Answer = p.PotentialAnswer
					AND a.Assignment = '" . $AssignmentID . "'
					AND a.Part = p.Part
					AND p.CorrectAnswer = 1
					LEFT JOIN QuestionsForAssignments as q
					ON 	 p.Question = q.Question
					AND  p.Part = q.Part
					GROUP BY a.Student) as MC
					ON MC.Student = a.Student
					WHERE 	a.Assignment = '" . $AssignmentID . "'
					GROUP BY a.Student
					ORDER BY s.LastName";
	
		$dataProvider = new CSqlDataProvider($query, array(
				'keyField' => 'StudentID',
				'pagination'=>array(
						'pageSize'=>10000,)
		));
	
		return $dataProvider;
	}
	
	public function assignmentAverage($AssignmentID, $ClassID)
	{
		$con = Yii::App()->db;

		$query = "SELECT 			SUM(q.OutOf) as MaximumPoints, e.ExamAverage, (e.ExamAverage/SUM(q.OutOf))*100 as AssignmentAveragePercentage
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
				
					WHERE a.Student IN (SELECT Student FROM EnrolledIn WHERE Class = '" . $ClassID . "' AND Status = 'Enrolled')
					GROUP BY 	a.Assignment
					ORDER BY 	a.Assignment
				) as e
				ON		    e.Assignment = '" . $AssignmentID . "'
					
				JOIN 	Assignments as a
				ON 		e.Assignment = a.AssignmentID
					
				JOIN 	Classes as cl
				ON		a.Class = cl.ClassID
					
				JOIN 	Courses as co
				ON 		cl.Course = co.CourseID
					
				WHERE q.Assignment = '" . $AssignmentID . "'
					
				GROUP BY 	co.Number, cl.Semester, cl.Year, a.AssignmentID";
	
		$dataProvider = new CSqlDataProvider($query, array(
				'keyField' => 'ExamAverage',
				'pagination'=>array(
						'pageSize'=>10000,)
		));
	
		return $dataProvider;
	}
	
	public function missingAssignment($AssignmentID, $classID)
	{
		$query = "	SELECT 	StudentID, LastName, FirstName, Username, Email
					FROM 	Students
					WHERE 	StudentID NOT IN
					#students who took the exam
					(
					SELECT		Student
					FROM		Answers
					WHERE		Assignment = '" . $AssignmentID . "'
					)
					AND 	StudentID IN
					#Student is enrolled
					(
					SELECT		Student
					FROM		EnrolledIn
					WHERE		Class = '" . $classID . "'
					AND			Status = 'Enrolled'
					)
					ORDER BY LastName";
	
		$dataProvider = new CSqlDataProvider($query, array(
				'keyField' => 'StudentID',
				'pagination'=>array(
						'pageSize'=>10000,)
		));
	
		return $dataProvider;
	}
	
	public function questionsAveragePercentage($AssignmentID, $classID)
	{
		$con = Yii::App()->db;
		
		// Find the total number of students who took exam
		$query = "SELECT 	Count(t.Student)
					FROM 		(
					SELECT				 	Student, COUNT(Student)
					FROM					Answers
					WHERE 					Assignment = '" . $AssignmentID . "'
					AND 	Student IN
					#Student is enrolled
					(
					SELECT		Student
					FROM		EnrolledIn
					WHERE		Class = '" . $classID . "'
					AND			Status = 'Enrolled'
					)
					GROUP BY 				Student
				) as 							t";
		$command = $con->createcommand($query);
		$TookExam = $command->queryScalar();
		
		$query = "	
				SELECT DISTINCT p.Question, q.Header, ((COUNT(a.Answer)*q.OutOf)/'" . $TookExam . "') as QuestionAverage, q.OutOf as MaximumPoints, (((COUNT(a.Answer)*q.OutOf)/'" . $TookExam . "')/ q.OutOf)*100 as AveragePercentage
				FROM Answers as a
				JOIN PotentialAnswers as p
				ON a.Question = p.Question
				AND a.Answer = p.PotentialAnswer
				AND a.Assignment ='" . $AssignmentID . "'
				AND a.Part = p.Part
				AND p.CorrectAnswer = 1
				JOIN QuestionsForAssignments as q
				ON 	 p.Question = q.Question
				AND  p.Part = q.Part
				GROUP BY p.Question, p.Part
				
				UNION 
				
				SELECT		a.Question, q.Header, AVG(a.Answer) as QuestionAverage, q.OutOf as MaximumPoints, (AVG(a.Answer)/q.OutOf)*100 as AveragePercentage 
				FROM		Answers as a
				JOIN 		QuestionsForAssignments as q
				ON 			a.Question = q.Question
				AND			a.Part = q.Part
				WHERE 		a.Assignment ='" .  $AssignmentID . "'
				AND 			a.Answer REGEXP '[0-9]'
				GROUP BY	a.Question, a.Part
				
				ORDER BY Header";
	
		$dataProvider = new CSqlDataProvider($query, array(
				'keyField' => 'Question',
				'pagination'=>array(
						'pageSize'=>10000,)
		));
	
		return $dataProvider;
	}
	
	public function problemsAveragePercentage($AssignmentID, $classID)
	{
		$con = Yii::App()->db;
		
		// Find the total number of students who took exam
		$query = "SELECT 	Count(t.Student)
					FROM 		(
					SELECT				 	Student, COUNT(Student)
					FROM					Answers
					WHERE 					Assignment = '" . $AssignmentID . "'
					AND 	Student IN
					#Student is enrolled
					(
					SELECT		Student
					FROM		EnrolledIn
					WHERE		Class = '" . $classID . "'
					AND			Status = 'Enrolled'
					)
					GROUP BY 				Student
				) as 							t";
		$command = $con->createcommand($query);
		$TookExam = $command->queryScalar();
		
		$query = "SELECT a.Problem, SUM(a.QuestionAverage) as ProblemAverage, SUM(a.MaximumPoints) as MaximumPoints, SUM(a.AveragePercentage)/COUNT(a.Problem) as ProblemAveragePercentage
		FROM (
		SELECT DISTINCT p.Question, q.Header, ((COUNT(a.Answer)*q.OutOf)/'" . $TookExam . "') as QuestionAverage, q.OutOf as MaximumPoints, (((COUNT(a.Answer)*q.OutOf)/'" . $TookExam . "')/ q.OutOf)*100 as AveragePercentage, IF(SUBSTRING(Header FROM 9) != '',(SUBSTRING(q.Header, 9, 1)), (Header)) as Problem
		FROM Answers as a
		JOIN PotentialAnswers as p
		ON a.Question = p.Question
		AND a.Answer = p.PotentialAnswer
		AND a.Assignment ='" . $AssignmentID . "'
		AND a.Part = p.Part
		AND p.CorrectAnswer = 1
		JOIN QuestionsForAssignments as q
		ON 	 p.Question = q.Question
		AND  p.Part = q.Part
		GROUP BY p.Question, p.Part
		
		UNION
		
		SELECT		a.Question, q.Header, AVG(a.Answer) as QuestionAverage, q.OutOf as MaximumPoints, (AVG(a.Answer)/q.OutOf)*100 as AveragePercentage, IF(SUBSTRING(Header FROM 9) != '',(SUBSTRING(q.Header, 9, 1)), (Header)) as Problem
		FROM		Answers as a
		JOIN 		QuestionsForAssignments as q
		ON 			a.Question = q.Question
		AND			a.Part = q.Part
		WHERE 		a.Assignment ='" . $AssignmentID . "'
		AND 			a.Answer REGEXP '[0-9]'
				GROUP BY	a.Question, a.Part
		) as a
		GROUP BY a.Problem
		ORDER BY IF(SUBSTR(a.Problem FROM 9) != '',(SUBSTR(a.Problem FROM 9)+1 ), (a.Problem+1))";
	
		$dataProvider = new CSqlDataProvider($query, array(
			'keyField' => 'Problem',
			'pagination'=>array(
					'pageSize'=>10000,)
		));
	
		return $dataProvider;
	}
	
	public function multipleChoice($AssignmentID)
	{
		$con = Yii::App()->db;
		// Get total Took Exam
		$query = "SELECT 	Count(t.Student)
					FROM 	(
					SELECT				 	Student, COUNT(Student)
					FROM					Answers
					WHERE 					Assignment = '" . $AssignmentID . "'
					GROUP BY 				Student
							 ) as t";
		$command = $con->createCommand($query);
		$TookExam = $command->queryScalar();
		
		$Condition = "";
	
		if (!empty($this->Header))
		{
			$Condition = " WHERE Header REGEXP '" . $this->CourseName . "'";
		}
		if (!empty($this->CourseNumber))
		{
			$Condition .= " AND co.Number =\"" . $this->CourseNumber . "\"";
		}
		if (!empty($this->Title))
		{
			$Condition .= " AND a.Title =\"" . $this->Title . "\"";
		}
		if (!empty($this->Section))
		{
			$Condition .= " AND cl.Section =\"" . $this->Section . "\"";
		}
	
		$query = "
				SELECT		Header, ans.Question, p.Part, p.PotentialAnswer, IF(p.CorrectAnswer = 1, 'Yes', 'No') as CorrectAnswer, (COUNT(ans.Answer)/'" . $TookExam . "')*100 as Percentage
				FROM		Answers as ans
				JOIN 		(SELECT * FROM PotentialAnswers WHERE Question IN (SELECT	DISTINCT ans.Question
				FROM		Answers as ans
				JOIN 		PotentialAnswers as p
				ON			p.Question = ans.Question
				AND 		p.Part = ans.Part
				WHERE 		Assignment = '" . $AssignmentID . "'
				AND 		p.CorrectAnswer = 1)
				AND 		Part IN (SELECT	DISTINCT ans.Part
				FROM		Answers as ans
				JOIN 		PotentialAnswers as p
				ON			p.Question = ans.Question
				AND 		p.Part = ans.Part
				WHERE 		Assignment = '" . $AssignmentID . "'
				AND 		p.CorrectAnswer = 1)) as p
				ON			p.Question = ans.Question
				AND  		p.Part = ans.Part
				JOIN        QuestionsForAssignments as q
				ON	        q.Assignment=ans. Assignment
				AND 		q.Question=ans.Question
				AND         q.Part=ans.Part
				WHERE 		ans.Assignment = '" . $AssignmentID . "'
				AND			p.PotentialAnswer = ans.Answer
				GROUP BY 	ans.Question, p.Part, p.CorrectAnswer, p.PotentialAnswer
	
				UNION
	
				SELECT DISTINCT Header,p.Question, p.Part, p.PotentialAnswer, IF(p.CorrectAnswer = 1, 'Yes', 'No') as CorrectAnswer, 0 as Percentage
				FROM PotentialAnswers as p
				JOIN Answers as a
				ON p.Question = a.Question
				AND p.Part = a.Part
				JOIN        QuestionsForAssignments as q
				ON	        q.Assignment=a. Assignment
				AND 		q.Question=a.Question
				AND         q.Part=a.Part
				WHERE 		p.PotentialAnswer NOT IN (SELECT Answer FROM Answers WHERE Question = p.Question AND Part = p.Part)
				AND			p.Question IN (SELECT	DISTINCT ans.Question
				FROM		Answers as ans
				JOIN 		PotentialAnswers as p
				ON			p.Question = ans.Question
				AND 		p.Part = ans.Part
				WHERE 		Assignment = '" . $AssignmentID . "'
				AND 		p.CorrectAnswer = 1)
				AND 		p.Part IN (SELECT	DISTINCT ans.Part
				FROM		Answers as ans
				JOIN 		PotentialAnswers as p
				ON			p.Question = ans.Question
				AND 		p.Part = ans.Part
				WHERE 		Assignment = '" . $AssignmentID . "'
				AND 		p.CorrectAnswer = 1)
				AND a.Assignment = '" . $AssignmentID . "'
				" . $Condition . "
	
				ORDER BY Question, Part, PotentialAnswer
				";
	
		$dataProvider = new CSqlDataProvider($query, array(
				'keyField' => 'Question',
				'pagination'=>array(
						'pageSize'=>10000,)
		));
	
		return $dataProvider;
	}
	
	public function correctAnswers($AssignmentID)
	{	
	
		$query = " SELECT			b.Title, q.Header,  p.PotentialAnswer as CorrectAnswer
					FROM			Answers as a
					
					JOIN 			QuestionsForAssignments as q
					ON 				a.Question = q.Question
					AND 			a.Part = q.Part
					
					JOIN 			Assignments as b
					ON 				'" . $AssignmentID . "'= b.AssignmentID

					JOIN			PotentialAnswers as p
					ON				p.Question = a.Question
					AND 			p.Part = a.Part
					
					WHERE 		a.Question IN
					(
						SELECT 	Question
						FROM 	QuestionsForAssignments
						WHERE 	Assignment = '" . $AssignmentID . "'
					)
					AND p.CorrectAnswer = 1
					
					GROUP BY	q.Header, q.Question, q.Part
					ORDER BY 	q.Header
				";
	
		$dataProvider = new CSqlDataProvider($query, array(
				'keyField' => 'Title',
				'pagination'=>array(
						'pageSize'=>10000,)
		));
	
		return $dataProvider;
	}
}

?>
