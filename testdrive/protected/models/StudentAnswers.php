<?php

class StudentAnswers extends CFormModel
{
	public $Student;
	public $Assignment;
	public $Question;
	public $Part;
	public $Answer;
	public $Header;

	public function rules()
	{
		return array(
				array('Header, Student, Assignment, Question, Part, Answer',
					    'safe','on'=>'search')
		);
	}
	
	public function answers($Student, $Assignment)
	{
		
		$query = "SELECT q.Header, a.Question, a.Part, a.Answer 
				  FROM Answers as a
				  JOIN QuestionsForAssignments as q
				  ON q.Question = a.Question
				  AND q.Part = a.Part
				  WHERE a.Assignment ='" . $Assignment . "' AND a.Student ='" . $Student . "'
				  ORDER BY q.Header
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
