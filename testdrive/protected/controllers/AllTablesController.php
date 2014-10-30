<?php

class AllTablesController extends Controller
{
	public function actionImportEnrollment()
	{
		$this->render('ImportEnrollment',array(
		));
	}
	
	public function actionViewTextbooks()
	{
		$Textbooks = new Textbooks('search');
	
		$Textbooks->unsetAttributes();
	
		if(isset($_GET['Textbooks'])){
			$Textbooks->attributes=$_GET['Textbooks'];
		}
	
		$this->render('viewTextbooks',array(
				'Textbooks'=>$Textbooks,
		));
	}
	
	public function actionViewQuestions()
	{
		$Questions = new Questions('search');
	
		$Questions->unsetAttributes();
	
		if(isset($_GET['Questions'])){
			$Questions->attributes=$_GET['Questions'];
		}
	
		$this->render('viewQuestions',array(
				'Questions'=>$Questions,
		));
	}
	
	public function actionViewSemesters()
	{
		$Semesters = new Semesters('search');
	
		$Semesters->unsetAttributes();
	
		if(isset($_GET['Semesters'])){
			$Semesters->attributes=$_GET['Semesters'];
		}
	
		$this->render('viewSemesters',array(
				'Semesters'=>$Semesters,
		));
	}
	
	public function actionViewEnrolledIn()
	{
		$EnrolledIn = new EnrolledIn('search');
	
		$EnrolledIn->unsetAttributes();
	
		if(isset($_GET['EnrolledIn'])){
			$EnrolledIn->attributes=$_GET['EnrolledIn'];
		}
	
		$this->render('viewEnrolledIn',array(
				'EnrolledIn'=>$EnrolledIn,
		));
	}
	
	public function actionViewCourses()
	{
		$Courses = new Courses('search');
	
		$Courses->unsetAttributes();
	
		if(isset($_GET['Courses'])){
			$Courses->attributes=$_GET['Courses'];
		}
	
		$this->render('viewCourses',array(
				'Courses'=>$Courses,
		));
	}
	
	public function actionViewClasses()
	{
		$Classes = new Classes('search');
	
		$Classes->unsetAttributes();
	
		if(isset($_GET['Classes'])){
			$Classes->attributes=$_GET['Classes'];
		}
	
		$this->render('viewClasses',array(
				'Classes'=>$Classes,
		));
	}
	
	public function actionViewAnswers()
	{
		$Answers = new Answers('search');
		
		$Answers->unsetAttributes();
		
		if(isset($_GET['Answers'])){
			$Answers->attributes=$_GET['Answers'];
		}
		
		$this->render('viewAnswers',array(
				'Answers'=>$Answers,
		));
	}
	
	public function actionViewAssignments()
	{
		$Assignments = new Assignments('search');
	
		$Assignments->unsetAttributes();
	
		if(isset($_GET['Assignments'])){
			$Assignments->attributes=$_GET['Assignments'];
		}
	
		$this->render('viewAssignments',array(
				'Assignments'=>$Assignments,
		));
	}
	
	public function actionUpdateAnswers($Student, $Assignment, $Question, $Part)
	{
		$Answers=Answers::model()->findByAttributes(array(
				'Student' => $Student, 
				'Assignment' => $Assignment,
				'Question' => $Question,
				'Part' => $Part
		));
		
		if(isset($_POST['Answers']))
		{
			$Answers->attributes=$_POST['Answers'];
			
			if ($Answers->save())
			{
				if ($_POST['action'] == 'Save and view all Answers')
				{
					$this->redirect('index-test.php?r=allTables/viewAnswers');
				}
				else if ($_POST['action'] == 'Save and go to current Student/Assignment')
				{
					$this->redirect('index-test.php?r=queries/studentAnswers' . 
							'&Student=' . $Answers->getAttribute('Student') .
							'&Assignment=' . $Answers->getAttribute('Assignment'));
				}
			}
		}
		
		$this->render('UpdateAnswers',array(
				'Answers'=>$Answers,
		));
	}
	
	public function actionUpdateQuestions($QuestionID)
	{
		$Questions=Questions::model()->findByAttributes(array(
				'QuestionID' => $QuestionID,
		));
	
		if(isset($_POST['Questions']))
		{
			$Questions->attributes=$_POST['Questions'];
				
			if ($Questions->save())
			{
				$this->redirect('index-test.php?r=allTables/viewQuestions');
			}
		}
	
		$this->render('UpdateQuestions',array(
				'Questions'=>$Questions,
		));
	}
	
	public function actionUpdateEnrolledIn($Student, $Class)
	{
		$EnrolledIn=EnrolledIn::model()->findByAttributes(array(
				'Student' => $Student,
				'Class' => $Class,
		));
	
		if(isset($_POST['EnrolledIn']))
		{
			$EnrolledIn->attributes=$_POST['EnrolledIn'];
			
			$date = $EnrolledIn->getAttribute('StatusChange');
			
			// Check if no date was entered -- default to null
			if ($date == "")
			{
				$EnrolledIn->setAttribute('StatusChange', null);
			}
				
			if ($EnrolledIn->save())
			{
				$this->redirect('index-test.php?r=allTables/viewEnrolledIn');
			}
		}
	
		$this->render('UpdateEnrolledIn',array(
				'EnrolledIn'=>$EnrolledIn,
		));
	}
	
	public function actionUpdateSemesters($Semester, $Year)
	{
		$Semesters=Semesters::model()->findByAttributes(array(
				'Semester' => $Semester,
				'Year' => $Year,
		));
	
		if(isset($_POST['Semesters']))
		{
			$path = 'index-test.php?r=allTables/viewSemesters';
			$Semesters->attributes=$_POST['Semesters'];
				
			$startDate = $Semesters->getAttribute('StartDate');
				
			// Check if no date was entered -- default to null
			if ($startDate == "")
			{
				$Semesters->setAttribute('StartDate', null);
			}
			
			$endDate = $Semesters->getAttribute('EndDate');
			
			// Check if no date was entered -- default to null
			if ($endDate == "")
			{
				$Semesters->setAttribute('EndDate', null);
			}
			
			
			if ($_POST['action'] == 'View Semesters')
			{
				$path = 'index-test.php?r=allTables/viewSemesters';
				$this->redirect($path);
			}
			
			else if ($_POST['action'] == 'Create Course')
			{
				$path = 'index-test.php?r=allTables/createCourses';
			}
			
			else if ($_POST['action'] == 'Create Class')
			{
				$path = 'index-test.php?r=allTables/createClasses';
			}
	
			if ($Semesters->save())
			{
				$this->redirect($path);
			}
		}
	
		$this->render('UpdateSemesters',array(
				'Semesters'=>$Semesters,
		));
	}
	
	public function actionUpdateCourses($CourseID)
	{
		$Courses=Courses::model()->findByAttributes(array(
				'CourseID' => $CourseID,
		));
	
		if(isset($_POST['Courses']))
		{
			$Courses->attributes=$_POST['Courses'];
				
			if ($Courses->save())
			{
				$this->redirect('index-test.php?r=allTables/viewCourses');
			}
		}
	
		$this->render('UpdateCourses',array(
				'Courses'=>$Courses,
		));
	}
	
	public function actionUpdateTextbooks($ISBN13)
	{
		$Textbooks=Textbooks::model()->findByAttributes(array(
				'ISBN13' => $ISBN13,
		));
	
		if(isset($_POST['Textbooks']))
		{
			$Textbooks->attributes=$_POST['Textbooks'];
	
			if ($Courses->save())
			{
				$this->redirect('index-test.php?r=allTables/viewTextbooks');
			}
		}
	
		$this->render('UpdateTextbooks',array(
				'Textbooks'=>$Textbooks,
		));
	}
	
	public function actionUpdateClasses($ClassID)
	{
		$Classes=Classes::model()->findByAttributes(array(
				'ClassID' => $ClassID,
				));
	
		if(isset($_POST['Classes']))
		{
			$Classes->attributes=$_POST['Classes'];
				
			if ($Classes->save())
			{
				$this->redirect('index-test.php?r=allTables/viewClasses');
			}
		}
	
		$this->render('UpdateClasses',array(
				'Classes'=>$Classes,
		));
	}

	public function actionUpdateAssignments($AssignmentID)
	{
		$Assignments=Assignments::model()->findByAttributes(array(
				'AssignmentID' => $AssignmentID,
		));
	
		if(isset($_POST['Assignments']))
		{
			$Assignments->attributes=$_POST['Assignments'];
			
		
			$date = $Assignments->getAttribute('Date');
				
			// Check if no date was entered -- default to null
			if ($date == "")
			{
				$Assignments->setAttribute('Date', null);
			}
				
			if ($Assignments->save())
			{
				$this->redirect('index-test.php?r=allTables/ViewAssignments');
			}
		}
	
		$this->render('UpdateAssignments',array(
				'Assignments'=>$Assignments,
		));
	}
	
	public function actionCreateAssignment()
	{
		$Assignments=new Assignments();
	
		if(isset($_POST['Assignments']))
		{
			$Assignments->attributes=$_POST['Assignments'];
				
	
			$date = $Assignments->getAttribute('Date');
	
			// Check if no date was entered -- default to null
			if ($date == "")
			{
				$Assignments->setAttribute('Date', null);
			}
	
			if ($Assignments->save())
			{
				$this->redirect('index-test.php?r=allTables/ViewAssignments');
			}
		}
	
		$this->render('UpdateAssignments',array(
				'Assignments'=>$Assignments,
		));
	}
	
	public function actionCreateQuestion()
	{
		$Questions=new Questions();
	
		if(isset($_POST['Questions']))
		{
			$Questions->attributes=$_POST['Questions'];
					
			if ($Questions->save())
			{
				$this->redirect('index-test.php?r=allTables/ViewQuestions');
			}
		}
	
		$this->render('UpdateQuestions',array(
				'Questions'=>$Questions,
		));
	}
	
	public function actionCreateTextbook()
	{
		$Textbooks=new Textbooks();
	
		if(isset($_POST['Textbooks']))
		{
			$Textbooks->attributes=$_POST['Textbooks'];
				
			if ($Textbooks->save())
			{
				$this->redirect('index-test.php?r=allTables/viewTextbooks');
			}
		}
	
		$this->render('UpdateTextbooks',array(
				'Textbooks'=>$Textbooks,
		));
	}
	
	public function actionDeleteSemesters($Semester, $Year)
	{
		$Semesters=Semesters::model()->findByAttributes(array(
				'Semester' => $Semester,
				'Year' => $Year,
		));
	
		if ($Semesters->delete())
		{
			$this->redirect('index-test.php?r=allTables/viewAnswers');
		}
	
	}
	
	public function actionDeleteAnswers($Student, $Assignment, $Question, $Part)
	{
		$Answers=Answers::model()->findByAttributes(array(
				'Student' => $Student,
				'Assignment' => $Assignment,
				'Question' => $Question,
				'Part' => $Part
		));
		
		if ($Answers->delete())
		{
			$this->redirect('index-test.php?r=allTables/viewAnswers');
		}
		
	}
	
	public function actionDeleteQuestions($QuestionID)
	{
		$Questions=Questions::model()->findByAttributes(array(
				'QuestionID' => $QuestionID,
		));
	
		if ($Questions->delete())
		{
			$this->redirect('index-test.php?r=allTables/viewQuestions');
		}
	
	}
	
	public function actionDeleteEnrolledIn($Student, $Class)
	{
		$EnrolledIn=EnrolledIn::model()->findByAttributes(array(
				'Student' => $Student,
				'Class' => $Class,
		));
	
		if ($EnrolledIn->delete())
		{
			$this->redirect('index-test.php?r=allTables/viewEnrolledIn');
		}
	
	}
	
	public function actionDeleteCourses($CourseID)
	{
		$CourseID=Courses::model()->findByAttributes(array(
				'CourseID' => $CourseID,
		));
	
		if ($CourseID->delete())
		{
			$this->redirect('index-test.php?r=allTables/viewCourses');
		}
	
	}
	
	public function actionCreateSemesters()
	{
		$Semesters=new Semesters();
	
		if(isset($_POST['Semesters']))
		{
			$path = 'index-test.php?r=allTables/viewSemesters';
			
			$Semesters->attributes=$_POST['Semesters'];
				
			$startDate = $Semesters->getAttribute('StartDate');
				
			// Check if no date was entered -- default to null
			if ($startDate == "")
			{
				$Semesters->setAttribute('StartDate', null);
			}
			
			$endDate = $Semesters->getAttribute('EndDate');
			
			// Check if no date was entered -- default to null
			if ($endDate == "")
			{
				$Semesters->setAttribute('EndDate', null);
			}
			
			
			if ($_POST['action'] == 'View Semesters')
			{
				$path = 'index-test.php?r=allTables/viewSemesters';
				$this->redirect($path);
			}
			
			else if ($_POST['action'] == 'Create Course')
			{
				$path = 'index-test.php?r=allTables/createCourses';
			}
			
			else if ($_POST['action'] == 'Create Class')
			{
				$path = 'index-test.php?r=allTables/createClasses';
			}
	
			if ($Semesters->save())
			{
				$this->redirect($path);
			}
	
		}
			
		$this->render('UpdateSemesters',array(
				'Semesters'=>$Semesters,
		));
	
	
	}
	
	public function actionCreateCourses()
	{
		$Courses=new Courses();
	
		if(isset($_POST['Courses']))
		{
			$Courses->attributes=$_POST['Courses'];
			
			if ($Courses->save())
			{
				$this->redirect('index-test.php?r=allTables/viewCourses');
			}
				
		}
			
		$this->render('UpdateCourses',array(
				'Courses'=>$Courses,
		));
		
	
	}
	
	public function actionCreateClasses()
	{
		$Classes=new Classes();
	
		if(isset($_POST['Classes']))
		{
			$Classes->attributes=$_POST['Classes'];
				
			if ($Classes->save())
			{
				$this->redirect('index-test.php?r=allTables/viewClasses');
			}
	
		}
			
		$this->render('UpdateClasses',array(
				'Classes'=>$Classes,
		));
	}
	
	public function actionCreateEnrolledIn()
	{
		$EnrolledIn=new EnrolledIn();
	
		if(isset($_POST['EnrolledIn']))
		{
			$EnrolledIn->attributes=$_POST['EnrolledIn'];
	
			if ($EnrolledIn->save())
			{
				$this->redirect('index-test.php?r=allTables/viewEnrolledIn');
			}
		}
			
		$this->render('UpdateEnrolledIn',array(
				'EnrolledIn'=>$EnrolledIn,
		));
	}
	
	public function actionCreateEnrolledInClass($classID)
	{
		$EnrolledIn=new EnrolledIn();
		$EnrolledIn->setAttribute('Class', $classID);
	
		if(isset($_POST['EnrolledIn']))
		{
			$EnrolledIn->attributes=$_POST['EnrolledIn'];
	
			if ($EnrolledIn->save())
			{
				$this->redirect('index-test.php?r=allTables/viewEnrolledIn');
			}
		}
			
		$this->render('UpdateEnrolledIn',array(
				'EnrolledIn'=>$EnrolledIn,
		));
	}
	
	public function actionDeleteClasses($ClassID)
	{
		$Classes=Classes::model()->findByAttributes(array(
				'ClassID' => $ClassID,
		));
	
		if ($Classes->delete())
		{
			$this->redirect('index-test.php?r=allTables/viewAnswers');
		}
	
	}
	
	public function actionDeleteAssignment($AssignmentID)
	{
		$Assignments=Assignments::model()->findByAttributes(array(
				'AssignmentID' => $AssignmentID,
		));
	
		if ($Assignments->delete())
		{
			$this->redirect('index-test.php?r=allTables/viewAssignments');
		}
	
	}
	
	public function actionDeleteTextbook($ISBN13)
	{
		$Textbooks=Textbooks::model()->findByAttributes(array(
				'ISBN13' => $ISBN13,
		));
	
		if ($Textbooks->delete())
		{
			$this->redirect('index-test.php?r=allTables/viewTextbooks');
		}
	
	}

	public function actionIndex()
	{
		$this->render('index');
	}

	public function actionUpdate()
	{
		$this->render('update');
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