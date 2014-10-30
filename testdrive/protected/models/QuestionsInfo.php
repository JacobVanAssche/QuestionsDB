<?php

class QuestionsInfo extends CFormModel
{
	public $Question;
	public $QuestionText;
	public $Header;
	public $QuestionAverage;
	public $MaxPoints;
	public $AveragePercentage;
	
	public $PotentialAnswer;
	public $CorrectAnswer;
	public $Percentage;

	public function rules()
	{
		return array(
				array('Question, QuestionText, Header, QuestionAverage, MaxPoints, AveragePecentage, PotentialAnswer, CorrectAnswer, Percentage','safe','on'=>'search')
		);
	}

	public function questionAverages($AssignmentID)
	{
		//$Condition = " WHERE cl.Semester =\"" . $Semester . "\" AND cl.Year =\"" . $Year . "\"
		
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
		
		if (!empty($this->CourseName))
		{
			$Condition .= " AND co.Name =\"" . $this->CourseName . "\"";
		}
		else if (!empty($this->CourseNumber))
		{
			$Condition .= " AND co.Number =\"" . $this->CourseNumber . "\"";
		}
		else if (!empty($this->Title))
		{
			$Condition .= " AND a.Title =\"" . $this->Title . "\"";
		}
		else if (!empty($this->Section))
		{
			$Condition .= " AND cl.Section =\"" . $this->Section . "\"";
		}	 
		
		$query = "
				SELECT DISTINCT p.Question, Questions.QuestionText, q.Header, ((COUNT(a.Answer)*q.OutOf)/'" . $TookExam . "') as QuestionAverage, q.OutOf as MaxPoints, (((COUNT(a.Answer)*q.OutOf)/'" . $TookExam . "')/ q.OutOf)*100 as AveragePercentage
				FROM Answers as a
				JOIN PotentialAnswers as p
				ON a.Question = p.Question
				AND a.Answer = p.PotentialAnswer
				AND a.Assignment = " . $AssignmentID . "
				AND a.Part = p.Part
				AND p.CorrectAnswer = 1
				JOIN QuestionsForAssignments as q
				ON 	 p.Question = q.Question
				AND  p.Part = q.Part
				JOIN Questions
				ON 	 a.Question = Questions.QuestionID
				GROUP BY p.Question, p.Part
				
				UNION 
				
				SELECT		a.Question, Questions.QuestionText, q.Header, AVG(a.Answer) as QuestionAverage, q.OutOf as MaxPoints, (AVG(a.Answer)/q.OutOf)*100 as AveragePercentage 
				FROM		Answers as a
				JOIN 		QuestionsForAssignments as q
				ON 			a.Question = q.Question
				AND			a.Part = q.Part
				JOIN Questions
				ON a.Question = Questions.QuestionID
				WHERE 		a.Assignment = " . $AssignmentID . "
				AND 			a.Answer REGEXP '[0-9]'
				GROUP BY	a.Question, a.Part
				
				ORDER BY IF(SUBSTR(Header FROM 9) != '',(SUBSTR(Header FROM 9)+1 ), (Header+1))
				";
		
		$dataProvider = new CSqlDataProvider($query, array(
				'keyField' => 'Question',
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
		// Use this to run the function MultipleChoicePercentage
		
		//$Condition = " WHERE cl.Semester =\"" . $Semester . "\" AND cl.Year =\"" . $Year . "\"
	
		if (!empty($this->CourseName))
		{
			$Condition .= " AND co.Name =\"" . $this->CourseName . "\"";
		}
		else if (!empty($this->CourseNumber))
		{
			$Condition .= " AND co.Number =\"" . $this->CourseNumber . "\"";
		}
		else if (!empty($this->Title))
		{
			$Condition .= " AND a.Title =\"" . $this->Title . "\"";
		}
		else if (!empty($this->Section))
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
				
				ORDER BY Question, Part, PotentialAnswer
				";
	
		$dataProvider = new CSqlDataProvider($query, array(
				'keyField' => 'Question',
				'pagination'=>array(
						'pageSize'=>10000,)
		));
	
		return $dataProvider;
	}
}

?>