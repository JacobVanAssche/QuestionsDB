<?php
$this->breadcrumbs=array(
		'ImportEnrollment',
);
?>
<h1>Import Enrollment</h1>

<html>
	<body>
		<form method="post" enctype="multipart/form-data">
		<b>CSV File to Import</b> <br> <input type="file" name="csv_file"> <br><br>
		<input type="submit" name="enter" value="Import">
		</form>
	</body>
</html>


<?php 
if(isset($_POST['enter']))
{
	$con = Yii::App()->db;
	
	$path = $_FILES["csv_file"]["tmp_name"];
	
	$path = addslashes($path);
	
	
	$rows = 0;
	if (($handle = fopen($path, "r")) !== FALSE) 
	{
	    while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) 
	    {
	    	$rows++;
	        $num = count($data);
	        
	        if ($rows == 1)
	        {
		        for ($i = 0; $i < $num; $i++) 
				{
					//echo $data[$i] . "<br>";
					
					// First row will determine which columns are which
					
						$columnName = strtoupper($data[$i]);
						
						if ((strpos($columnName, 'ID')) !== FALSE && (strpos($columnName, 'CLASS')) === FALSE)
							$UIDColumn = $i;
						
						else if ((strpos($columnName, 'LAST')) !== FALSE)
							$LastNameColumn = $i;
						
						else if ((strpos($columnName, 'FIRST')) !== FALSE)
							$FirstNameColumn = $i;
						
						else if ((strpos($columnName, 'NAME')) !== FALSE && (strpos($columnName, 'COURSE') !== FALSE))
							$CourseNameColumn = $i;
						
						else if ((strpos($columnName, 'NUMBER')) !== FALSE)
							$CourseNumberColumn = $i;
						
						else if ((strpos($columnName, 'SECTION')) !== FALSE)
							$SectionColumn = $i;
						
						else if ((strpos($columnName, 'SEMESTER')) !== FALSE)
							$SemesterColumn = $i;
						
						else if ((strpos($columnName, 'YEAR')) !== FALSE)
							$YearColumn = $i;
						
						else if ((strpos($columnName, 'STATUS')) !== FALSE)
							$StatusColumn = $i;
						
						else if ((strpos($columnName, 'USERNAME')) !== FALSE)
							$UsernameColumn = $i;
						
						else if ((strpos($columnName, 'EMAIL')) !== FALSE)
							$EmailColumn = $i;
					}
	        }
	        
	        else
	        {
		        // Put the information into corresponding arrays
		        $Section_Tmp = "";
		        $UID_Tmp = "";
		        $LastName_Tmp = "";
		        $FirstName_Tmp = "";
		        $CourseName_Tmp = "";
		        $CourseNumber_Tmp = "";
		        $Semester_Tmp = "";
		        $Year_Tmp = "";
		        $Status_Tmp = "Enrolled";
		        $Username_Tmp = null;
		        $Email_Tmp = null;
		        	
		        if (isset($SectionColumn))
		        	$Section_Tmp = $data[$SectionColumn];
		        	
		        if (isset($UIDColumn))
		        	$UID_Tmp = $data[$UIDColumn];
		        	
		        if (isset($LastNameColumn))
		        	$LastName_Tmp = $data[$LastNameColumn];
		        	
		        if (isset($FirstNameColumn))
		        	$FirstName_Tmp = $data[$FirstNameColumn];
		        	
		        if (isset($CourseNameColumn))
		        	$CourseName_Tmp = $data[$CourseNameColumn];
		        	
		        if (isset($CourseNumberColumn))
		        	$CourseNumber_Tmp = $data[$CourseNumberColumn];
		        	
		        if (isset($SemesterColumn))
		        	$Semester_Tmp = $data[$SemesterColumn];
		        	
		        if (isset($YearColumn))
		        	$Year_Tmp = $data[$YearColumn];
		        	
		        if (isset($StatusColumn))
		        	$Status_Tmp = $data[$StatusColumn];
		        
		        if (isset($UsernameColumn))
		        	$Username_Tmp = $data[$UsernameColumn];
		        
		        if (isset($EmailColumn))
		        	$Email_Tmp = $data[$EmailColumn];
		        	
		        $Section[] = $Section_Tmp;
		        $UID[] = $UID_Tmp;
		        $LastName[] = $LastName_Tmp;
		        $FirstName[] = $FirstName_Tmp;
		        $CourseName[] = $CourseName_Tmp;
		        $CourseNumber[] = $CourseNumber_Tmp;
		        $Semester[] = $Semester_Tmp;
		        $Year[] = $Year_Tmp;
		        $Status[] = $Status_Tmp;
		        $Username[] = $Username_Tmp;
		        $Email[] = $Email_Tmp;
	        }
	        //echo "<br>";
	    }
	    fclose($handle);
	}
	
	for ($i=0; $i < $rows-1; $i++) 
	{
		// Check if class exists
		$query = '
				SELECT ClassID
				FROM Classes
				
				JOIN Courses
				ON Classes.Course = Courses.CourseID
				
				WHERE Name = "'   . $CourseName[$i] . '"
				AND Semester = "' . $Semester[$i] . '"
				AND Year = "'     . $Year[$i] . '"
				AND Number = "'   . $CourseNumber[$i] . '"
				AND Section = "'  . $Section[$i] . '"
				';
		$command = $con->createCommand($query);
		$classID = $command->queryScalar();
		
		if ($classID == "")
		{
			// Check if Semester exists
			$query = '
				SELECT COUNT(*)
				FROM Semesters
				WHERE Semester = "'   . $Semester[$i] . '"
				AND Year = "'   . $Year[$i] . '"
				';
			$command = $con->createCommand($query);
			$countSemester = $command->queryScalar();
				
			if ($countSemester == 0)
			{
				// Create new Semester
				echo "Creating new semester: " . $Semester[$i] . " -- " . $Year[$i] . "<br>";
				$query = "INSERT INTO Semesters(Semester, Year, StartDate, EndDate)
						  VALUES('" . $Semester[$i] . "','" . $Year[$i] . "',0,0)";
				$command = $con->createCommand($query);
				$command->execute();
			
				$courseID = Yii::app()->db->getLastInsertID('Courses');
			}
			
			// Check if Course exists
			$query = '
				SELECT CourseID
				FROM Courses
				WHERE Name = "'   . $CourseName[$i] . '"
				AND Number = "'   . $CourseNumber[$i] . '"
				';
			$command = $con->createCommand($query);
			$courseID = $command->queryScalar();
			
			if ($courseID == "")
			{
				// Create new course
				echo "Creating new course: " . $CourseName[$i] . " -- " . $CourseNumber[$i] . "<br>";
				$query = "INSERT INTO Courses(Name, Number, University) 
						  VALUES('" . $CourseName[$i] . "','" . $CourseNumber[$i] . "','University of Detroit Mercy')";
				$command = $con->createCommand($query);
				$command->execute();
				
				$courseID = Yii::app()->db->getLastInsertID('Courses');
			}
			
			// Create new class
			echo "Creating new class: " . $CourseName[$i] . " -- " . $Semester[$i] . " " . $Year[$i] . " -- Section: " . $Section[$i] . "<br>";
			$query = "INSERT INTO Classes(Course, Semester, Year, Section) 
					  VALUES('" . $courseID . "','" . $Semester[$i] . "','" . $Year[$i] . "','" . $Section[$i] . "')";
			$command = $con->createCommand($query);
			$command->execute();
			$classID = Yii::app()->db->getLastInsertID('Classes');
		}
			
		// Check if student exists
		$query = "SELECT COUNT(*) FROM Students WHERE StudentID = '" . $UID[$i] . "'";
		$command = $con->createCommand($query);
		$countStudent = $command->queryScalar();
			
		if ($countStudent == 0 && $UID[$i] != "")
		{
			
			// Create student
			echo "Creating student: " . $UID[$i] . " " . $FirstName[$i] . " " . $LastName[$i] . "<br>";
			if ($Email[$i] != "")
			{
				$query = "INSERT INTO Students(StudentID, FirstName, LastName, Username, Email)
					      VALUES('" . $UID[$i] . "','" . $FirstName[$i] . "','" . $LastName[$i] . "','" . 
									  $Username[$i] . "','" . $Email[$i] . "')";
			}
			else
			{
				$query = "INSERT INTO Students(StudentID, FirstName, LastName, Username, Email)
					      VALUES('" . $UID[$i] . "','" . $FirstName[$i] . "','" . $LastName[$i] . "',
									  null , null)";
			}
				
			$command = $con->createCommand($query);
			$command->execute();
		}
		// Check if student is already enrolled
		$dupRow = "SELECT COUNT(*) FROM EnrolledIn
				   WHERE Student = '" . $UID[$i] . "'
				   AND 	 Class = '" . $classID . "'";
		$command = $con->createCommand($dupRow);
		$dupRowCount = $command->queryScalar();
			
		if ($UID[$i] != "")
		{
			if ($dupRowCount == 0)
			{
				// Found class ID, insert information into EnrolledIn
				echo "Creating new enrollment -- Student: " . $FirstName[$i] . " " . $LastName[$i] . " -- Class: " . $classID . " -- Status: " . $Status[$i] . "<br>";
				$query = "INSERT INTO EnrolledIn(Student, Class, Status)
						  VALUES('" . $UID[$i] . "','" . $classID . "','" . $Status[$i] . "')";
				$command = $con->createCommand($query);
				$command->execute();
			}
			else
			{
				echo "Student already has enrollment for this class!<br>";
				echo "<b>ID:</b> " . $UID[$i] . " <b>LastName:</b> " . $LastName[$i] . "    <b>FirstName:</b> " . $FirstName[$i] . " ";
				echo "<b> CourseName:</b> " . $CourseName[$i] . " <b>CourseNumber:</b> " . $CourseNumber[$i] . " <b>Section:</b> " . $Section[$i] . "<br>";
				echo "<b>Semester:</b> " . $Semester[$i] . " " . $Year[$i] . " ";
				echo "<b> Status:</b> " . $Status[$i] . "<br>";
			}
		}
		else
		{
			echo "UserID not found!<br>";
		}
	}
	
	
	echo "<br><br>Successfully imported enrollment<br>";
	echo CHtml::button('View Enrollment' , array('submit' => array('allTables/viewEnrolledIn')));
}
