<?php

class TempTablesController extends Controller
{
	public function actionTexToSQL()
	{
		$this->render('TexToSQL',array(
		));
	}
	
	public function actionAllClasses()
	{
		$classes = new Classes('search');
		$courses = new Courses('search');
	
		$classes->unsetAttributes();
		$courses->unsetAttributes();
	
		$this->render('AllClasses',array(
				'Classes'=>$classes,
				'Courses'=>$courses,
		));
	}
	
	public function actionTmp6Error($text)
	{
		$this->render('tmp6Error', array('text'=>$text));
	}
	
	public function actionUpdateTmp6()
	{
		$con = Yii::app()->db;
		$query = "SELECT Title FROM tmp_6 LIMIT 1";
		$command = $con->createCommand($query);
		$value = $command->queryScalar();
		
		if ($value != "")
		{
			$tmp6 = Tmp6::model()->findByAttributes(array('Title' => $value));
		}
		else 
			$tmp6 = new Tmp6();

		if(isset($_POST['Tmp6']))
		{
			$query = "SELECT Header FROM tmp_1 ORDER BY CAST(Header AS DECIMAL(7,3))";
			$command = $con->createCommand($query);
			$value = $command->queryScalar();
				
			$tmp6->attributes=$_POST['Tmp6'];
				
			$date = $tmp6->getAttribute('Date');
			$courseName = $tmp6->getAttribute('CourseName');
			$section = $tmp6->getAttribute('Section');
			$semester = $tmp6->getAttribute('Semester');
			$year = $tmp6->getAttribute('Year');
				
			// Check if no date was entered -- default to null
			if ($date == "")
			{
				$tmp6->setAttribute('Date', null);
			}
			
			// Put Semesters into array
			$query = "SELECT Semester FROM Semesters";
			$command = $con->createCommand($query);
			$dataReader = $command->queryAll();
			
			foreach($dataReader as $row)
			{ $allSemesters[] = $row['Semester']; }
			
			// Put Years into array
			$query = "SELECT Year FROM Semesters";
			$command = $con->createCommand($query);
			$dataReader = $command->queryAll();
			
			foreach($dataReader as $row)
			{ $allYears[] = $row['Year']; }
			
			$foundSemester = FALSE;
			
			for ($i = 0; $i < count($allSemesters); $i++)
			{
				if ($semester == $allSemesters[$i] && $year == $allYears[$i])
				{
					$foundSemester = TRUE;
				}
			}
			
			if ($foundSemester == FALSE)
			{
				$text = "semester";
				$this->redirect(array('tempTables/tmp6Error&text=' . $text));
			}
			else 
			{	
				// Check if course is missing
				$query = "SELECT CourseID FROM Courses WHERE Name = '" . $courseName . "'";
				$command = $con->createCommand($query);
				$course = $command->queryScalar();
				
				if ($course != "")
				{	
					// Check if class is missing
					$query = "SELECT ClassID FROM Classes WHERE Course = '" . $course . "' 
							  AND Semester ='" . $semester . "' AND Year ='" . $year . "'";
					$command = $con->createCommand($query);
					$class = $command->queryScalar();
					
					if ($class == "")
					{
						$text = "class";
						$this->redirect(array('tempTables/tmp6Error&text=' . $text));
					}
				}
			
				else
				{
					$text =  "course";
					$this->redirect(array('tempTables/tmp6Error&text=' . $text));
				}
			}
				
			if ($_POST['action'] == 'Next')
			{
				$path = 'tempTables/updateTmp1Tmp2&Header=' . $value;
			}
				
			else if ($_POST['action'] == 'Review All')
			{
				$path = 'tempTables/ReviewAll';
			}
			else if ($_POST['action'] == 'Save')
			{
				$path = 'tempTables/updateTmp6';
			}

			
			// Update the information to the database
			if($tmp6->save())
			{
				$this->redirect(array($path));
			}

		}

		$this->render('updateTmp6',array(
				'tmp6'=>$tmp6,
		));
	}

	public function actionUpdateTmp5($Header, $Part)
	{
		$con = Yii::app()->db;
		
		$query = "SELECT Header FROM tmp_5
					WHERE Header = '" . $Header . "'
					AND Part ='" . $Part . "'";
		$command = $con->createCommand($query);
		$header = $command->queryScalar();
		
		if ($header != "")
		{
			$tmp5=Tmp5::model()->findByAttributes(array(
				'Header' => $Header,
				'Part' => $Part
				));
		}
		else
		{
			$this->redirect("index-test.php?r=tempTables/newPotentialAnswer");
		}
		
		$query = "SELECT PotentialAnswer FROM tmp_5
						WHERE Header ='" . $Header . "'
						  AND 	Part ='" . $Part . "'";
		$command = $con->createCommand($query);
		$dataReader = $command->queryAll();
		
		foreach($dataReader as $row)
		{
			$oldPA[] = $row['PotentialAnswer'];
		}

		if(isset($_POST['Tmp5']))
		{
			$tmp5->attributes=$_POST['Tmp5'];
				
			// Get total number of PotentialAnswers
			$query = "SELECT COUNT(PotentialAnswer) FROM tmp_5
					WHERE Header = '" . $Header . "'
							AND Part = '" . $Part . "'";
			$command = $con->createCommand($query);
			$numPotential = $command->queryScalar();
				
			$path = 'tempTables/updateTmp5&Header=';
				
			if ($_POST['action'] == 'Previous')
			{
				$query = "SELECT Part FROM tmp_5
						WHERE Header ='" . $Header . "'
						  AND 	Part < '" . $Part . "'
						  		ORDER BY Part DESC
						  		LIMIT 1";
				$command = $con->createCommand($query);
				$part = $command->queryScalar();

				if ($part == "")
				{
					$query = "SELECT Header FROM tmp_5
							WHERE CAST(Header AS DECIMAL(7,3)) < CAST('" . $Header . "' AS DECIMAL(7,3))
									ORDER BY CAST(Header AS DECIMAL(7,3)) DESC
									LIMIT 1";
					$command = $con->createCommand($query);
					$header = $command->queryScalar();
						
					if ($header == "")
					{
						$query = "SELECT Header
								FROM tmp_5
								WHERE CAST(SUBSTR(Header FROM 9) AS UNSIGNED INT) < CAST(SUBSTR('" .$Header ."'FROM 9) AS UNSIGNED INT)
										ORDER BY CAST(SUBSTR(Header FROM 9) AS UNSIGNED INT) DESC
										LIMIT 1";
						$command = $con->createCommand($query);
						$header = $command->queryScalar();
					}
						
					if ($header == "")
					{
						$query = "SELECT Header FROM tmp_1 ORDER BY CAST(Header AS DECIMAL(7,3)) DESC LIMIT 1";
						$command = $con->createCommand($query);
						$header = $command->queryScalar();
						if ($header == "")
						{
							$query = "SELECT Header
							  FROM tmp_1
							  ORDER BY CAST(SUBSTR(Header FROM 9) AS UNSIGNED INT) DESC
							  LIMIT 1";
							$command = $con->createCommand($query);
							$value = $command->queryScalar();
						}

						$path = "tempTables/updateTmp1Tmp2&Header=" . $header;
					}
					else
					{
						$query = "SELECT Part FROM tmp_5
								WHERE Header ='" . $header . "'
										ORDER BY Part DESC
										LIMIT 1";
						$command = $con->createCommand($query);
						$part = $command->queryScalar();
						$path .= $header . '&Part=' . $part;
					}
				}
				else
					$path .= $Header . '&Part=' . $part;
			}
			if ($_POST['action'] == 'Next')
			{
				$query = "SELECT Part FROM tmp_5
						WHERE Header ='" . $Header . "'
						  AND 	Part > '" . $Part . "'
						  		ORDER BY Part ASC
						  		LIMIT 1";
				$command = $con->createCommand($query);
				$part = $command->queryScalar();

				if ($part == "")
				{
					$query = "SELECT Header FROM tmp_5
							WHERE CAST(Header AS DECIMAL(7,3)) > CAST('" . $Header ."' AS DECIMAL(7,3))
									ORDER BY CAST(Header AS DECIMAL(7,3)) ASC
									LIMIT 1";
					$command = $con->createCommand($query);
					$header = $command->queryScalar();
						
					if ($header == "")
					{
						$query = "SELECT Header
								FROM tmp_5
								WHERE CAST(SUBSTR(Header FROM 9) AS UNSIGNED INT) > CAST(SUBSTR('" .$Header ."'FROM 9) AS UNSIGNED INT)
										ORDER BY CAST(SUBSTR(Header FROM 9) AS UNSIGNED INT) ASC
										LIMIT 1";
						$command = $con->createCommand($query);
						$header = $command->queryScalar();
					}
						
					if ($header != "")
					{
							
						$query = "SELECT Part FROM tmp_5
								WHERE Header ='" . $header . "'
										ORDER BY Part ASC
										LIMIT 1";
						$command = $con->createCommand($query);
						$part = $command->queryScalar();
						$path .= $header . '&Part=' . $part;
					}
					else
					{
						$path = 'tempTables/ReviewAll';
					}
						
				}
				else
					$path .= $Header . '&Part=' . $part;
			}
			
			else if ($_POST['action'] == 'Add Potential Answer')
			{
				$path = 'tempTables/newPotentialAnswer';
			}
			else if ($_POST['action'] == 'Review All')
			{
				$path = 'tempTables/ReviewAll';
			}
			else if ($_POST['action'] == 'Save' || $_POST['action'] == 'Add New')
			{
				// Stay on same page
				$path .= $Header . '&Part=' . $Part;
			}
			else if ($_POST['action'] == 'Edit Problems/Parts')
			{
				$query = "SELECT Header FROM tmp_1 ORDER BY CAST(Header AS DECIMAL(7,3))";
				$command = $con->createCommand($query);
				$value = $command->queryScalar();
				
				$path = 'tempTables/updateTmp1Tmp2&Header=' . $value;
			}
			else if ($_POST['action'] == 'Go')
			{
				$h = $_POST['Header'];
				$p = $_POST['Part'];
				
				$path .= $h . '&Part=' . $p;
			}
				
			if (isset($_POST['PotentialAnswers'][0]))
			{
				if (($_POST['newPA']) != "" && $_POST['newPAT'] != "" && $_POST['newCA'] != "")
				{
					$query = "INSERT INTO tmp_5(Header, Part, PotentialAnswer, PotentialAnswerText, CorrectAnswer)
								  VALUES ('" . $Header . "','" .
												  $Part . "','" .
												  $_POST['newPA'] . "','" .
												  addslashes($_POST['newPAT']) . "','" .
												  $_POST['newCA'] . "')";
					$command = $con->createCommand($query);
					$command->execute();
				}
				
				for ($i = 0; $i < $numPotential; $i++)
				{
					$newPA = addslashes($_POST['PotentialAnswers'][$i]);
					
					if ($oldPA[$i] != $newPA)
					{
						$query = "DELETE FROM tmp_5 WHERE 
								Header ='" . $Header . "' AND 
							    Part ='" . $Part . "' AND
							    PotentialAnswer ='" . $oldPA[$i] . "'";
						$command = $con->createCommand($query);
						$command->execute();
						
						$query = "INSERT INTO tmp_5(Header, Part, PotentialAnswer, PotentialAnswerText, CorrectAnswer)
								  VALUES ('" . $Header . "','" .
								  				 $Part . "','" . 
								  				 $newPA . "','" .
								  				 $_POST['PotentialAnswerText'][$i] . "','" .
								  				 $_POST['CorrectAnswer'][$i] . "')";
						$command = $con->createCommand($query);
						$command->execute();
					}
					else
					{
						$query = "UPDATE tmp_5
								SET PotentialAnswerText = '" . addslashes($_POST['PotentialAnswerText'][$i]) ."',
								CorrectAnswer = '" . $_POST['CorrectAnswer'][$i] . "'
								WHERE Header ='" . $Header . "'
								AND Part ='" . $Part . "'
								AND PotentialAnswer = '" . $_POST['PotentialAnswers'][$i] . "'";
						$command = $con->createCommand($query);
						$command->execute();
					}
				}

				$this->redirect(array($path));
			}
				
		}

		$this->render('updateTmp5',array(
				'tmp5'=>$tmp5,
		));
	}

	public function actionUpdateTmp1Tmp2($Header)
	{
		$con = Yii::app()->db;
		
		$query = "SELECT Header FROM tmp_1
				  WHERE Header = '" . $Header . "'";
		$command = $con->createCommand($query);
		$header = $command->queryScalar();
		
		$query = "SELECT Part FROM tmp_2
				  WHERE Header ='" . $Header . "'";
		$command = $con->createCommand($query);
		$dataReader = $command->queryAll();
		
		foreach($dataReader as $row)
		{
			$oldPart[] = $row['Part'];
		}
		
		if ($header != "")
		{
			// Load tmp1 model with the current header
			$tmp1=Tmp1::model()->findByAttributes(array('Header' => $Header));
			// Load tmp2 model with the current header
			$tmp2=Tmp2::model()->findByAttributes(array('Header' => $Header));
		}
		else
		{
			$this->redirect("index-test.php?r=tempTables/AddProblem");
		}
		
		// If "Next", "Save", or "Previous" button is pressed
		if(isset($_POST['Tmp1']))
		{				
			// Get total number of parts
			$query = "SELECT COUNT(Part) FROM tmp_2
					WHERE Header = '" . $Header . "'";
			$command = $con->createCommand($query);
			$numParts = $command->queryScalar();
				
			$tmp1->attributes=$_POST['Tmp1'];
			if (isset($_POST['Tmp2']))
			{
				$tmp2->attributes=$_POST['Tmp2'];
			}
				
			$path = 'tempTables/updateTmp1Tmp2&Header=';

			// Previous button is pressed
			if ($_POST['action'] == 'Previous')
			{
				// Get the previous header
				$query = "SELECT Header
						  FROM tmp_1
						  WHERE Header < '" . $Header . "'
						  ORDER BY Header DESC
						  LIMIT 1";
				$command = $con->createCommand($query);
				$value = $command->queryScalar();
			
				// There are problems before this
				if ($value != "")
				{
					$path .= $value;
				}
				// No problems before this, bring user back to exam info page
				else
					$path = 'tempTables/updateTmp6';
			}
			// Next button is pressed
			else if ($_POST['action'] == 'Next')
			{
				$query = "SELECT Header
						  FROM tmp_1
						  WHERE Header > '" . $Header . "' 
						  ORDER BY Header
						  LIMIT 1";
				$command = $con->createCommand($query);
				$value = $command->queryScalar();
				
				// There exists another headers
				if ($value != "")
				{
					$path .= $value;
				}
				
				// No more headers -- Go to potential answers
				else
				{
					$query = "SELECT Header FROM tmp_5 ORDER BY Header ASC LIMIT 1";
					$command = $con->createCommand($query);
					$value = $command->queryScalar();
						
					if ($value != "")
					{
						$query = "SELECT Part FROM tmp_5
								  WHERE Header ='" . $value . "'
								  ORDER BY Part ASC
								  LIMIT 1";
						$command = $con->createCommand($query);
						$Part = $command->queryScalar();

						$path = 'tempTables/updateTmp5&Header='.$value.'&Part='.$Part;
					}
					else
					{
						$path = 'tempTables/ReviewAll';
					}
				}
			}
				
			else if ($_POST['action'] == 'Add Problem')
			{
				$path = 'tempTables/AddProblem';
			}
				
			else if ($_POST['action'] == 'Review All')
			{
				$path = 'tempTables/ReviewAll';
			}
			else if ($_POST['action'] == 'Save' || $_POST['action'] == 'Add New')
			{
				// Stay on same page
				$path .= $tmp1->getAttribute('Header');
			}
			else if ($_POST['action'] == 'Go')
			{
				$goto = $_POST['GoTo'];
				$path .= $goto;
			}
			else if ($_POST['action'] == 'Edit Potential Answers')
			{
				$query = "SELECT Header FROM tmp_5 ORDER BY Header ASC LIMIT 1";
				$command = $con->createCommand($query);
				$value = $command->queryScalar();
				
				if ($value != "")
				{
						
					$query = "SELECT Part FROM tmp_5
							  WHERE Header ='" . $value . "'
							  ORDER BY Part ASC
							  LIMIT 1";
					$command = $con->createCommand($query);
					$Part = $command->queryScalar();
				
					$path = 'tempTables/updateTmp5&Header='.$value.'&Part='.$Part;
				}
				else
				{
					$path = 'tempTables/ReviewAll';
				}
			}
			
			
			
			// Update the information to the database
			if ($tmp1->save())
			{
				$newHeader = $tmp1->getAttribute('Header');
				if (($_POST['newPart']) != "" && $_POST['newOutOf'] != "")
				{
					$query = "INSERT INTO tmp_2(Header, Part, OutOf)
									  VALUES ('" . $newHeader . "','" .
													  $_POST['newPart'] . "','" .
													  $_POST['newOutOf'] . "')";
					$command = $con->createCommand($query);
					$command->execute();
				}
				
				if (isset($_POST['OutOfs'][0]))
				{	
					for ($i = 0; $i < $numParts; $i++)
					{
						$newPart = $_POST['Parts'][$i];
						
						if ($oldPart[$i] != $newPart)
						{
							$query = "INSERT INTO tmp_2(Header, Part, OutOf)
								  VALUES ('" . $newHeader . "','" .
															  $newPart . "','" .
															  $_POST['OutOfs'][$i] . "')";
								
							$command = $con->createCommand($query);
							$command->execute();
							
							$query = "UPDATE tmp_5 SET Part ='" . $newPart . "' WHERE Header ='" . $newHeader . "' AND Part ='" . $oldPart[$i] . "'";
							$command = $con->createCommand($query);
							$command->execute();
							
							$query = "DELETE FROM tmp_2 WHERE
									  Header ='" . $newHeader . "' AND
							          Part ='" . $oldPart[$i] . "'";
							$command = $con->createCommand($query);
							$command->execute();
								
							
						}
						else 
						{
							$query = "UPDATE tmp_2
									SET OutOf = " . $_POST['OutOfs'][$i] . "
											WHERE Header ='" . $newHeader . "'
							  		  AND PART ='" . $_POST['Parts'][$i] . "'";
							$command = $con->createCommand($query);
							$command->execute();
						}
					}

					$this->redirect(array($path));
				}

				else
				{
					// Redirect user accordingly
					$this->redirect(array($path));
				}
			}
		}

		$this->render('updateTmp1Tmp2',array(
				'tmp1'=>$tmp1,
				'tmp2'=>$tmp2,
		));
	}

	public function actionReviewAll()
	{
		$tmp1 = new Tmp1('search');
		$tmp1->dbCriteria->order='IF(SUBSTR(Header FROM 9) != "",(SUBSTR(Header FROM 9)+1 ), (Header+1))';
		
		$tmp2 = new Tmp2('search');
		$tmp2->dbCriteria->order='IF(SUBSTR(Header FROM 9) != "",(SUBSTR(Header FROM 9)+1 ), (Header+1))';
		
		$tmp5 = new Tmp5('search');
		$tmp5->dbCriteria->order='IF(SUBSTR(Header FROM 9) != "",(SUBSTR(Header FROM 9)+1 ), (Header+1))';
		
		$tmp6 = new Tmp6('search');

		$tmp1->unsetAttributes();
		$tmp2->unsetAttributes();
		$tmp5->unsetAttributes();
		$tmp6->unsetAttributes();

		if(isset($_GET['Tmp1'])){
			$tmp1->attributes=$_GET['Tmp1'];
		}
		
		if(isset($_GET['Tmp2'])){
			$tmp1->attributes=$_GET['Tmp2'];
		}

		if(isset($_GET['Tmp5'])){
			$tmp5->attributes=$_GET['Tmp5'];
		}
		
		if(isset($_GET['Tmp6'])){
			$tmp5->attributes=$_GET['Tmp6'];
		}

		$this->render('ReviewAll',array(
				'tmp1'=>$tmp1,
				'tmp2'=>$tmp2,
				'tmp5'=>$tmp5,
				'tmp6'=>$tmp6,
		));
	}

	public function actionAddProblem()
	{
		$con = Yii::app()->db;
		
		$tmp1=new Tmp1();
		$tmp2=new Tmp2();

		if(isset($_POST['Tmp1'], $_POST['Tmp2']))
		{
			$tmp1->attributes=$_POST['Tmp1'];
			$tmp2->attributes=$_POST['Tmp2'];

			$tmp2->setAttribute('Header', $tmp1->getAttribute('Header'));

			if ($_POST['action'] == 'Review All')
			{
				$path = 'index-test.php?r=tempTables/ReviewAll';
				$this->redirect($path);
			}
			else if ($_POST['action'] == 'Edit Problems and Parts')
			{
				$query = "SELECT Header FROM tmp_1 ORDER BY CAST(Header AS DECIMAL(7,3)) ASC LIMIT 1";
				$command = $con->createCommand($query);
				$header = $command->queryScalar();
				if ($header == "")
				{
					$query = "SELECT Header
							  FROM tmp_1
							  ORDER BY CAST(SUBSTR(Header FROM 9) AS UNSIGNED INT) ASC
							  LIMIT 1";
					$command = $con->createCommand($query);
					$value = $command->queryScalar();
				}
				
				$path = "index-test.php?r=tempTables/updateTmp1Tmp2&Header=" . $header;
				$this->redirect($path);
			}
			else if ($_POST['action'] == 'Edit Potential Answers')
			{
				$query = "SELECT Header FROM tmp_5 ORDER BY Header ASC LIMIT 1";
				$command = $con->createCommand($query);
				$value = $command->queryScalar();
			
				if ($value != "")
				{
			
					$query = "SELECT Part FROM tmp_5
								WHERE Header ='" . $value . "'
										ORDER BY Part ASC
										LIMIT 1";
					$command = $con->createCommand($query);
					$Part = $command->queryScalar();
			
					$path = 'index.php?r=tempTables/updateTmp5&Header='.$value.'&Part='.$Part;
				}
				else
				{
					$path = 'index-test.php?r=tempTables/ReviewAll';
				}
				$this->redirect($path);
			}
			
			else if ($tmp1->save() && $tmp2->save())
			{
				$path = 'index-test.php?r=tempTables/UpdateTmp1Tmp2&Header=' . $tmp1->getAttribute('Header');
				$this->redirect($path);
			}
				
		}
			
		$this->render('AddProblem',array(
				'tmp1'=>$tmp1,
				'tmp2'=>$tmp2,
		));

	}

	public function actionAddPart($Header)
	{
		$tmp1=Tmp1::model()->findByAttributes(array('Header' => $Header));
		$tmp2=new Tmp2();

		if(isset($_POST['Tmp1'], $_POST['Tmp2']))
		{
			$tmp1->attributes=$_POST['Tmp1'];
			$tmp2->attributes=$_POST['Tmp2'];

			$tmp2->setAttribute('Header', $tmp1->getAttribute('Header'));

			if ($tmp1->save() && $tmp2->save())
			{
				$path = 'index-test.php?r=tempTables/UpdateTmp1Tmp2&Header=' . $Header;
				$this->redirect($path);
			}

		}
			
		$this->render('AddPart',array(
				'tmp1'=>$tmp1,
				'tmp2'=>$tmp2,
		));

	}


	public function actionDeletePart($Header, $Part)
	{
		$con = Yii::app()->db;

		// Load tmp1 model with current header
		$tmp1=Tmp1::model()->findByAttributes(array('Header' => $Header));

		// Load tmp2 model with current header and part
		$tmp2=Tmp2::model()->findByAttributes(array('Header' => $Header, 'Part' => $Part));

		// Load tmp5 model with the current header and part
		$tmp5=Tmp5::model()->findByAttributes(array('Header' => $Header, 'Part' => $Part));

		// Get total number of parts
		$query = "SELECT COUNT(Part) FROM tmp_2
				WHERE Header = '" . $Header . "'";
		$command = $con->createCommand($query);
		$numParts = $command->queryScalar();
		
		$path = 'index-test.php?r=tempTables/updateTmp1Tmp2&Header=';


		if (isset($tmp5))
		{
			if ($tmp5->deleteAllByAttributes(array('Header'=>$Header, 'Part'=>$Part)))
			{
				if ($numParts == 1)
				{
					if ($tmp1->delete())
					{
						$query = "SELECT Header
								FROM tmp_1
								WHERE CAST(SUBSTR(Header FROM 9) AS UNSIGNED INT) < CAST(SUBSTR('" .$Header ."'FROM 9) AS UNSIGNED INT)
							    ORDER BY CAST(SUBSTR(Header FROM 9) AS UNSIGNED INT) DESC
							    LIMIT 1";
						$command = $con->createCommand($query);
						$value = $command->queryScalar();
					
						if ($value == "")
						{
							$query = "SELECT Header
								FROM tmp_1
								WHERE CAST(Header AS DECIMAL(7,3)) < CAST('".$Header."' AS DECIMAL(7,3))
								ORDER BY CAST(Header AS DECIMAL(7,3)) DESC
								LIMIT 1";
							$command = $con->createCommand($query);
							$value = $command->queryScalar();
						}
							
						// There are problems before this
						if ($value != "")
						{
							$path .= $value;
						}
						// No problems before this, bring user back to exam info page
						else
							$path = 'index-test.php?r=tempTables/updateTmp6';
						
						$this->redirect($path);
					}
				}

				else if ($tmp2->deleteAllByAttributes(array('Header'=>$Header, 'Part'=>$Part)))
				{
					$this->redirect('index-test.php?r=tempTables/updateTmp1Tmp2&Header=' . $Header);
				}
			}
		}

		if ($numParts == 1)
		{
			if ($tmp1->delete())
			{
				$query = "SELECT Header
				FROM tmp_1
				WHERE CAST(SUBSTR(Header FROM 9) AS UNSIGNED INT) < CAST(SUBSTR('" .$Header ."'FROM 9) AS UNSIGNED INT)
				  ORDER BY CAST(SUBSTR(Header FROM 9) AS UNSIGNED INT) DESC
				  LIMIT 1";
				$command = $con->createCommand($query);
				$value = $command->queryScalar();
			
				if ($value == "")
				{
					$query = "SELECT Header
						FROM tmp_1
						WHERE CAST(Header AS DECIMAL(7,3)) < CAST('".$Header."' AS DECIMAL(7,3))
								ORDER BY CAST(Header AS DECIMAL(7,3)) DESC
								LIMIT 1";
					$command = $con->createCommand($query);
					$value = $command->queryScalar();
				}
					
				// There are problems before this
				if ($value != "")
				{
					$path .= $value;
				}
				// No problems before this, bring user back to exam info page
				else
					$path = 'index-test.php?r=tempTables/updateTmp6';
				
				$this->redirect($path);
			}
		}

		if ($tmp2->deleteAllByAttributes(array('Header'=>$Header, 'Part'=>$Part)))
		{
			$this->redirect('index-test.php?r=tempTables/updateTmp1Tmp2&Header=' . $Header);
		}

	}


	public function actionDeleteProblem($Header)
	{
		// Load tmp1 model with the current header
		$tmp1=Tmp1::model()->findByAttributes(array('Header' => $Header));

		// Load tmp5 model with the current header
		$tmp5=Tmp5::model()->findByAttributes(array('Header' => $Header));

		if (isset($tmp5))
		{
			if ($tmp5->deleteAll("Header = '" . $tmp1->getAttribute('Header'). "'"))
			{
				if ($tmp1->delete())
				{
					$this->redirect('index-test.php?r=tempTables/ReviewAll');
				}
			}
		}

		else if ($tmp1->delete())
		{
			$this->redirect('index-test.php?r=tempTables/ReviewAll');
		}
	}

	public function actionAddPotentialAnswer($Header, $Part)
	{		
		$tmp5=new Tmp5();
		
		$tmp5->setAttribute('Header', $Header);
		$tmp5->setAttribute('Part', $Part);

		if(isset($_POST['Tmp5']))
		{
			$tmp5->attributes=$_POST['Tmp5'];
			
			/*if ($_POST['action'] == 'Review All')
			{
				$path = 'index-test.php?r=tempTables/ReviewAll';
				$this->redirect($path);
			}*/

			 if ($tmp5->save())
			{
				$path = 'index.php?r=tempTables/updateTmp5&Header='.$tmp5->getAttribute('Header').'&Part='.$tmp5->getAttribute('Part');
				$this->redirect($path);
			}

		}
			
		$this->render('addPotentialAnswer',array(
				'tmp5'=>$tmp5,
		));

	}

	public function actionDeletePotentialAnswer($Header, $Part, $PotentialAnswer)
	{
		$con = Yii::App()->db;

		// Load tmp5 model with the current header and part
		$tmp5=Tmp5::model()->findByAttributes(array(
				'Header' => $Header,
				'Part' => $Part,
				'PotentialAnswer' => $PotentialAnswer));


		if ($tmp5->delete())
		{
			$query = "SELECT Header FROM tmp_5
					WHERE Header = '" . $Header . "'
							AND Part = '" . $Part . "'";
			$command = $con->createCommand($query);
			$header = $command->queryScalar();

			if ($header != "")
			{
				$this->redirect('index.php?r=tempTables/updateTmp5&Header='.$Header.
						'&Part='.$Part);
			}
			else
				$this->redirect('index-test.php?r=tempTables/ReviewAll');
				
		}
	}

	public function actionNewPotentialAnswer()
	{
		$con = Yii::app()->db;
		$tmp5=new Tmp5();

		if(isset($_POST['Tmp5']))
		{
			$tmp5->attributes=$_POST['Tmp5'];
			
			$header = $_POST['Header'];
			$tmp5->setAttribute('Header', $header);
			
			if ($_POST['action'] == 'Review All')
			{
				$path = 'index-test.php?r=tempTables/ReviewAll';
				$this->redirect($path);
			}
			else if ($_POST['action'] == 'Edit Problems and Parts')
			{
				$query = "SELECT Header FROM tmp_1 ORDER BY CAST(Header AS DECIMAL(7,3)) ASC LIMIT 1";
				$command = $con->createCommand($query);
				$header = $command->queryScalar();
				if ($header == "")
				{
					$query = "SELECT Header
							  FROM tmp_1
							  ORDER BY CAST(SUBSTR(Header FROM 9) AS UNSIGNED INT) ASC
							  LIMIT 1";
					$command = $con->createCommand($query);
					$value = $command->queryScalar();
				}
			
				$path = "index-test.php?r=tempTables/updateTmp1Tmp2&Header=" . $header;
				$this->redirect($path);
			}
			else if ($_POST['action'] == 'Edit Potential Answers')
			{
				$query = "SELECT Header FROM tmp_5 ORDER BY Header ASC LIMIT 1";
				$command = $con->createCommand($query);
				$value = $command->queryScalar();
					
				if ($value != "")
				{
						
					$query = "SELECT Part FROM tmp_5
							  WHERE Header ='" . $value . "'
							  ORDER BY Part ASC
							  LIMIT 1";
					$command = $con->createCommand($query);
					$Part = $command->queryScalar();
						
					$path = 'index.php?r=tempTables/updateTmp5&Header='.$value.'&Part='.$Part;
				}
				else
				{
					$path = 'index.php?r=tempTables/ReviewAll';
				}
				$this->redirect($path);
			}
			else if ($tmp5->save())
			{
				$path = 'index.php?r=tempTables/updateTmp5&Header=' . $tmp5->getAttribute('Header') . '&Part=' . $tmp5->getAttribute('Part');
				$this->redirect($path);
			}

		}
			
		$this->render('newPotentialAnswer',array(
				'tmp5'=>$tmp5,
		));
	}

	public function actionFinalize()
	{
		$this->render('Finalize');
	}

	public function actionImportStudentsScores()
	{
		$this->render('ImportStudentsScores');
	}
}
