<?php

class QueriesController extends Controller
{
	public function actionStudentAnswers($Student, $Assignment)
	{
		$StudentAnswers=new StudentAnswers('search');
		$StudentAnswers->unsetAttributes();
		
		if (isset($_GET['StudentAnswers'])){
			$StudentAnswers->attributes = $_GET['StudentAnswers'];
		}
		

		$this->render('StudentAnswers', array(
				'StudentAnswers'=>$StudentAnswers,
				'Student' => $Student,
				'Assignment' => $Assignment,
		));
	}
	
	public function actionAssignments($Semester, $Year)
	{
		$allExamAverages = new AllExamAverages('search');
		$allExamAverages->unsetAttributes();
		
		if (isset($_GET['AllExamAverages'])){
			$allExamAverages->attributes = $_GET['AllExamAverages'];
		}
	
		if (isset($_POST['semester']))
		{
			$semester = $_POST['semester'];
			$position = strpos($semester, ":");
	
			$newSemester = substr($semester, 0, $position);
			$newYear = substr($semester, $position+1);
	
			$this->redirect('index-test.php?r=queries/Assignments&Semester=' .
					$newSemester . "&Year=" . $newYear
			);
		}
	
		$this->render('Assignments', array(
				'allExamAverages'=> $allExamAverages,
				'Semester'=>$Semester,
				'Year'=>$Year
		));
	}
	
	public function actionAdministrative()
	{
		$this->render('Administrative', array());
	}
	
	public function actionAllClasses($Semester, $Year)
	{
		
		if (isset($_POST['semester']))
		{
			$semester = $_POST['semester'];
			$position = strpos($semester, ":");
			
			$newSemester = substr($semester, 0, $position);
			$newYear = substr($semester, $position+1);
			
			$this->redirect('index-test.php?r=queries/allClasses&Semester=' . 
					$newSemester . "&Year=" . $newYear
					);
		}
		
		$this->render('AllClasses', array(
				'Semester'=>$Semester,
				'Year'=>$Year,
				));
	}
	
	public function actionEnrolledInClass($CourseName, $CourseNumber, $Section, $Semester, $Year)
	{
		$model = new EnrolledInClass('search');
		$model->unsetAttributes();
		
		if (isset($_GET['EnrolledInClass'])){
			$model->attributes = $_GET['EnrolledInClass'];
		}
		
		$this->render('EnrolledInClass', array(
				'model'=>$model,
				'CourseName'=>$CourseName, 
				'CourseNumber'=>$CourseNumber,
				'Section'=>$Section,
				'Semester'=>$Semester,
				'Year'=>$Year,
				));
	}
	
	
	public function actionStudentInfo($StudentID, $LastName, $FirstName, $Semester, $Year)
	{
		$studentEnrollment = new StudentEnrollment('search');
		$studentEnrollment->unsetAttributes();
		
		$studentExamPercentages = new StudentExamPercentages('search');
		$studentExamPercentages->unsetAttributes();
		
		if (isset($_GET['StudentEnrollment'])){
			$studentEnrollment->attributes = $_GET['StudentEnrollment'];
		}
		
		if (isset($_GET['StudentExamPercentages'])){
			$studentExamPercentages->attributes = $_GET['StudentExamPercentages'];
		}
		
		$this->render('StudentInfo', array(
				'studentEnrollment'=>$studentEnrollment,
				'studentExamPercentages'=>$studentExamPercentages,
				'StudentID'=>$StudentID,
				'LastName'=>$LastName,
				'FirstName'=>$FirstName,
				'Semester'=>$Semester,
				'Year'=>$Year
		));
	}
	
	public function actionQuestionsInfo($AssignmentID, $Title, $CourseName, $CourseNumber, $Section, $Semester, $Year)
	{
		
		$QuestionsInfo = new QuestionsInfo('search');
		$QuestionsInfo->unsetAttributes();
	
		if (isset($_GET['$QuestionsInfo'])){
			$QuestionsInfo->attributes = $_GET['QuestionsInfo'];
		}
	
		$this->render('questionsInfo', array(
				'AssignmentID'=>$AssignmentID,
				'QuestionsInfo'=>$QuestionsInfo,
				'Title'=>$Title,
				'CourseName'=>$CourseName,
				'CourseNumber'=>$CourseNumber,
				'Section'=>$Section,
				'Semester'=>$Semester,
				'Year'=>$Year
		));
	}
	
	public function actionAssignmentInfo($AssignmentID, $Title, $CourseName, $CourseNumber, $Section, $Semester, $Year)
	{
		$con = Yii::App()->db;
		$AssignmentInfo = new AssignmentInfo('search');
		$AssignmentInfo->unsetAttributes();
	
		if (isset($_GET['$AssignmentInfo'])){
			$AssignmentInfo->attributes = $_GET['AssignmentInfo'];
		}
		
		if (isset($_POST['export_scores']))
		{
			$query = "SELECT Aux_ClassID('" . $CourseName . "','" . $Section . "','" . $Semester . "','" . $Year . "');";
			$command = $con->createCommand($query);
			$classID = $command->queryScalar();
			
			$this->actionExportScores($AssignmentID, $classID, $Title);
		}
		
		if (isset($_POST['export_answers']))
		{
			$this->actionExportAnswers($AssignmentID);
			$this->redirect("yes");
		}
		
	
		$this->render('assignmentInfo', array(
				'AssignmentID'=>$AssignmentID,
				'AssignmentInfo'=>$AssignmentInfo,
				'Title'=>$Title,
				'CourseName'=>$CourseName,
				'CourseNumber'=>$CourseNumber,
				'Section'=>$Section,
				'Semester'=>$Semester,
				'Year'=>$Year
		));
	}
	
	public function actionExportScores($AssignmentID, $classID, $Title)
	{
		$fp = fopen('php://temp', 'w');
		
		// Write column headers of csv file
		
		$headers = array(
				'StudentID',
				'LastName',
				'FirstName',
				'Version',
				'Score',
				'Total',
				'Percent',
		);
		
		fputcsv($fp,$headers);
		
		$model=new AssignmentInfo('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['AssignmentInfo'])) {
			$model->attributes=$_GET['AssignmentInfo'];
		}
		
		$dp = $model->studentScores($AssignmentID, $classID, $Title);
		$dp->setPagination(false);
		
		/*
		 * Get models, write to a file
		*/
		
		$models = $dp->getData();
		foreach($models as $model) {
			$row = array();
			foreach($headers as $head) {
				$row[] = CHtml::value($model,$head);
			}
			fputcsv($fp,$row);
		}
		//save csv content to a Session
		
		rewind($fp);
		Yii::app()->user->setState('export',stream_get_contents($fp));
		fclose($fp);
		
		$this->actionExportFile();
	}
	
	// Correct answers to multiple choice questions
	public function actionExportAnswers($AssignmentID)
	{
		$fp = fopen('php://temp', 'w');
	
		// Write column headers of csv file
	
		$headers = array(
				'Title',
				'Header',
				'CorrectAnswer',
		);
	
		fputcsv($fp,$headers);
	
		$model=new AssignmentInfo('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['AssignmentInfo'])) {
			$model->attributes=$_GET['AssignmentInfo'];
		}
	
		$dp = $model->correctAnswers($AssignmentID);
		$dp->setPagination(false);
	
		/*
		 * Get models, write to a file
		*/
	
		$models = $dp->getData();
		foreach($models as $model) {
			$row = array();
			foreach($headers as $head) {
				$row[] = CHtml::value($model,$head);
			}
			fputcsv($fp,$row);
		}
		//save csv content to a Session
	
		rewind($fp);
		Yii::app()->user->setState('export',stream_get_contents($fp));
		fclose($fp);
	
		$this->actionExportFile();
	}
	
	public function actionExportFile()
	{
		Yii::app()->request->sendFile('export.csv',Yii::app()->user->getState('export'));
		Yii::app()->user->clearState('export');
	}
	
	public function actionEnrollment($Semester, $Year)
	{
		$model = new Enrollment('search');
		$model->unsetAttributes();
		
		if (isset($_GET['Enrollment'])){
			$model->attributes = $_GET['Enrollment'];
		}
		
		$this->render('Enrollment', array(
				'model'=> $model,
				'Semester'=>$Semester,
				'Year'=>$Year,
				));
	}
	
	public function actionStudents($Semester, $Year)
	{
		$model = new AllStudents('search');
		$model->unsetAttributes();
		
		if (isset($_GET['AllStudents'])){
			$model->attributes = $_GET['AllStudents'];
		}
		
		if (isset($_POST['semester']))
		{
			$semester = $_POST['semester'];
			$position = strpos($semester, ":");
				
			$newSemester = substr($semester, 0, $position);
			$newYear = substr($semester, $position+1);
				
			$this->redirect('index-test.php?r=queries/students&Semester=' .
					$newSemester . "&Year=" . $newYear
			);
		}
	
		$this->render('Students', array(
				'model'=> $model,
				'Semester'=>$Semester,
				'Year'=>$Year
		));
	}
	
	public function actionQuestions($Semester, $Year)
	{
		$model = new AllExamAverages('search');
		$model->unsetAttributes();
		
		if (isset($_GET['AllExamAverages'])){
			$model->attributes = $_GET['AllExamAverages'];
		}
	
		if (isset($_POST['semester']))
		{
			$semester = $_POST['semester'];
			$position = strpos($semester, ":");
	
			$newSemester = substr($semester, 0, $position);
			$newYear = substr($semester, $position+1);
	
			$this->redirect('index-test.php?r=queries/questions&Semester=' .
					$newSemester . "&Year=" . $newYear
			);
		}
	
		$this->render('Questions', array(
				'model'=> $model,
				'Semester'=>$Semester,
				'Year'=>$Year
		));
	}
	// Uncomment the following methods and override them if needed
	/*
	public function filters()
	{
		// return the filter configuration for this controller, e.g.:
		return array(
			'inlineFilterName',
			array(
				'class'=>'path.to.FilterClass',
				'propertyName'=>'propertyValue',
			),
		);
	}

	public function actions()
	{
		// return external action classes, e.g.:
		return array(
			'action1'=>'path.to.ActionClass',
			'action2'=>array(
				'class'=>'path.to.AnotherActionClass',
				'propertyName'=>'propertyValue',
			),
		);
	}
	*/
}