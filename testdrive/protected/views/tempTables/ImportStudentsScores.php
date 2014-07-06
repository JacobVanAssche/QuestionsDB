<?php
/* @var $this SiteController */

$this->pageTitle=Yii::app()->name . ' - Import Student Scores';
$this->breadcrumbs=array(
	'Import Student Scores',
);
?>

<?php 

$con = Yii::App()->db;

// Put Semesters into array
$query = "SELECT Semester FROM Semesters order by Year desc";
$command = $con->createCommand($query);
$dataReader = $command->queryAll();

foreach($dataReader as $row)
{ $allSemesters[] = $row['Semester']; }

// Put Years into array
$query = "SELECT Year FROM Semesters order by Year desc";
$command = $con->createCommand($query);
$dataReader = $command->queryAll();

foreach($dataReader as $row)
{ $allYears[] = $row['Year']; }

$semesters = "";
for ($i = 0; $i < count($allSemesters); $i++)
{
	$semesters.= "<option value=\"" . $allSemesters[$i] . ":" . $allYears[$i] . "\">" . $allYears[$i]. " " . $allSemesters[$i]  . "</option>";
}

$host 	  = 	'localhost';
$username = $con->username;
$password = $con->password;
$dbname   = 	'QuestionsDB';


// Create connection
$con = mysqli_init();
if (!$con) {
	die('mysqli_init failed');
}

if (!mysqli_options($con,MYSQLI_OPT_LOCAL_INFILE,1)) {
	die('Setting MYSQLI_OPT_LOCAL_INFILE failed');
}


if (!mysqli_real_connect($con, $host, $username, $password,$dbname)) {
	die('Connect Error (' . mysqli_connect_errno() . ') '
			. mysqli_connect_error());
}

// Get Course Names for drop down menu
$query = "SELECT Name FROM Courses";
$result = mysqli_query($con, $query);
$courseNames = "";
while ($row = mysqli_fetch_assoc($result))
{
	$courseNames .= "<option value='{$row['Name']}'>{$row['Name']}</option>";
}

?>

<h1>Import Student Scores</h1>

<html>
	<body>
		<form method="post" enctype="multipart/form-data">
		<b>CSV File to import</b> <br> <input type="file" name="csv_file"> <br>
		<b>Select Course Name</b> <br> <select name = "courseName"><?php echo $courseNames;?></select> 
		<?php echo CHtml::button('Create New Course' , array('submit' => array('allTables/CreateCourses')));?><br>
		<b>Section</b> (leave blank if none) <br> <input type="text" name="section"> <br>
		<b>Semester</b> <br> <select name = "semester">
		<?php echo $semesters;?> </select> 
		<?php echo CHtml::button('Create New Semester' , array('submit' => array('allTables/CreateSemesters')));?><br>
		<b>Assignment Title</b> (e.g. "Exam 1") <br> <input type="text" name="assignmentTitle"> <br>
		<b>Version</b> (e.g. "A") <br> <input type="text" name="version"> <br><br>
		<input type="checkbox" name="updateAnswers" value="yes"><b>Update any existing student's answers</b> <br><br>
		<input type="submit" value="Import">
		</form>
	</body>	
</html>
<br>

<?php

if(isset($_POST['courseName']))
{ 
	echo 'Successfully set options...' . mysqli_get_host_info($con) . "<br>";
	// Set path name to uploaded file
	$path = $_FILES["csv_file"]["tmp_name"];
	
	// Add backslashes before characters that need to be quoted in database query (i.e. "\")
	$path = addslashes($path);
	
	function testQuery($con, $result, $query)
	{
		if (!$result)
		{
			$message = 'Invalid Query: ' . mysqli_error($con) . "<br>";
			$message .= 'Query: ' . $query;
			die ($message);
		}
		else
		{
			//echo "Query was successful!<br>";
		}
	}
	
	$combinedSemester = $_POST['semester'];
	$position = strpos($combinedSemester, ":");
		
	$semester = substr($combinedSemester, 0, $position);
	$year = (int)substr($combinedSemester, $position+1);
	
	$courseName		 = $_POST["courseName"];
	$section		 = $_POST["section"];
	$assignmentTitle = $_POST["assignmentTitle"];
	$version 		 = $_POST["version"];
	
	$query = "DELETE FROM imp_1";
	$result = mysqli_query($con, $query);
	testQuery($con, $result, $query);
	echo "Deleted from imp_1 <br>";
	
	$query = "LOAD DATA LOCAL INFILE
		'" . $path . "'
		INTO TABLE `QuestionsDB`.`imp_1` CHARACTER SET utf8 FIELDS TERMINATED BY ',' ENCLOSED BY '\"' lines terminated by '\\n'";
	$result = mysqli_query($con, $query);
	testQuery($con, $result, $query);
	echo "Loaded data into imp_1 from CSV file<br>";
	
	// Close connection
	mysqli_close($con);
	echo "Closed connection <br>";
	// Open connection
	$con = mysqli_init();
	if (!$con) {
		die('mysqli_init failed');
	}
	
	if (!mysqli_options($con,MYSQLI_OPT_LOCAL_INFILE,0)) {
		die('Setting MYSQLI_OPT_LOCAL_INFILE failed');
	}
	
	
	if (!mysqli_real_connect($con, $host, $username, $password,$dbname)) {
		die('Connect Error (' . mysqli_connect_errno() . ') '
				. mysqli_connect_error());
	}
	echo "Successfully reopened connection!<br>";
	
	// Delete all data from temporary tables
	$query = "DELETE FROM tmp_5";
	mysqli_query($con,$query);
	$query = "DELETE FROM tmp_1";
	mysqli_query($con,$query);
	$query = "DELETE FROM tmp_2";
	mysqli_query($con,$query);
	$query = "DELETE FROM tmp_3";
	mysqli_query($con,$query);
	$query = "DELETE FROM tmp_4";
	mysqli_query($con,$query);
	$query = "DELETE FROM tmp_6";
	$result = mysqli_query($con,$query);
	testQuery($con, $result, $query);
	echo "Deleted all tmp_tables <br>";
	
	
	// Find the total columns of the table
	/*****************************************/
	$query = "SELECT * FROM imp_1";
	$result = mysqli_query($con, $query);
	testQuery($con, $result, $query);
	
	
	$columns = mysqli_num_fields($result);
	echo "Total Number of columns found: " . $columns . "<br>";
	
	$count_columns = 0;
	$student_columns = 0;
	/*****************************************/
	
	// Determine which column is which
	/*****************************************/
	for ($i = 1; $i <= $columns; $i++)
	{
		$query = "SELECT f" . $i . " FROM imp_1" . " LIMIT 1";
		$result = mysqli_query($con, $query);
		testQuery($con, $result, $query);
		$row = mysqli_fetch_row($result);
		$column = $row[0];
	
		//echo "Column name: " . $column . "<br>";
	
		if  ($column == "Last Name")
		{
			$lastName = "f" . $i;
			$student_columns++;
		}
		else if ($column == "First Name")
		{
			$firstName = "f" . $i;
			$student_columns++;
		}
		else if ($column == "Username")
		{
			$username = "f" . $i;
			$student_columns++;
		}
		else if ($column == "Student ID")
		{
			$studentID_column = "f" . $i;
			$student_columns++;
		}
		else if ($column == "")
		{
			break;
		}
	
		$count_columns++;
	}
	/*****************************************/
	
	// Get the ClassID
	/*****************************************/
	$query = "SELECT Aux_ClassID(\"" . $courseName . "\",\"" . $section . "\",\"" . $semester . "\",\"" . $year . "\")";
	$result = mysqli_query($con, $query);
	testQuery($con, $result, $query);
	$row = mysqli_fetch_row($result);
	
	if ($row[0] != "")
	{
		echo "Class ID found!<br>";
		$classID = $row[0];
		echo "Class ID: " . $classID . "<br>";
		
	/*****************************************/
	
	// Check if Assignment exists
	/****************************************/
	$query = "SELECT COUNT(*) FROM `Assignments` WHERE Class = '" . $classID . "' AND Title = '" . 
			  $assignmentTitle . "' AND Version = '" . $version . "'";
	
	$result = mysqli_query($con, $query);
	testQuery($con, $result, $query);
	$row = mysqli_fetch_row($result);
	
	$assignmentID = $row[0];
	
	// Assignment already exists
	if ($assignmentID != 0)
	{
		$query = "SELECT `AssignmentID` FROM `Assignments` WHERE Class = '" . $classID . "' AND Title = '" . 
			  $assignmentTitle . "' AND Version = '" . $version . "'";
		$result = mysqli_query($con, $query);
		testQuery($con, $result, $query);
		$row = mysqli_fetch_row($result);
		$assignmentID = $row[0];
		echo "Assignment ID exists: " . $assignmentID . "<br>";
	}
	// Assignment does not exist
	else
	{
		echo "Assignment ID does not exist!<br>";
	}
	
	$lastHeader = "";
	for ($i = $student_columns+1; $i <= $count_columns; $i++)
	{
		// Check for questions already associated with the AssignmentID, insert any new ones to temp tables
		/*****************************************/
		$query1 = "SELECT `Header` FROM `QuestionsForAssignments` WHERE Assignment = \"" . $assignmentID . "\"";
		$result1 = mysqli_query($con,$query1);
		testQuery($con, $result, $query);
		
		$createNew = true;
		
		$query2 = "SELECT f" . $i . " FROM imp_1 LIMIT 1";
		$result2 = mysqli_query($con, $query2);
		$row = mysqli_fetch_row($result2);
		$header = $row[0];
		$header = trim($header);
		if (strpos($header, "Problem") === FALSE)
		{
			$header = "Problem " . $header;
		}
	
		while($row = mysqli_fetch_array($result1, MYSQL_NUM))
		{
			if (trim($row[0]) == $header)
			{
				$createNew = false;
			}

		}
		
		// Get part from header
		$strpos = strpos($header, "(");
	
		if ($strpos == FALSE)
		{
			$part = "";
		}
		else
		{
			$part = substr($header, $strpos);
			//$header = substr($header, 0, ($strpos-1));
		}
		
		// Insert any new headers to the temp tables that are not found in the assignment
		if ($createNew)
		{
			//echo $header . " should be inserted!<br>";
			// Add the header and part to arrays
			$missingHeaders[] = $header;
			$missingParts[] = $part;
		}
		else
		{
			$foundHeaders[] = $header;
			$foundParts[] = $part;
			//echo $header . " should NOT be inserted!<br>";
			//echo "Part for this header: " . $part . "<br>";
		}
	
	}
	
	
	/*****************************************/
	/* 		INSERT INTO TEMP TABLES			 */
	/*****************************************/
	$tmp_1 = "INSERT INTO tmp_1(Header, QuestionText)
				  VALUES ";
	$tmp_2 = "INSERT INTO tmp_2(Header, Part)
				      VALUES ";
	
	if (empty($missingHeaders))
	{
		$totalNew = 0;
		echo "No problems should be inserted! <br>";
	}
	else
	{
		$totalNew = count($missingHeaders);
	
		echo "Total problems to be inserted: " . $totalNew . "<br>";
		echo "Problems to be inserted:<br>";
		// Insert data into tmp_1, tmp_2
		foreach ($missingHeaders as $i => $h)
		{
			$p = $missingParts[$i];
			echo "<b>Header:</b> " . $h . "\t <b>Part</b>: " . $p . "<br>";
			
			if ($i != 0)
			{
				if ($h != $lastHeader)
				{
					$tmp_1 .= ",";
				}
				$tmp_2 .= ",";
			}
			
			if ($h != $lastHeader)
			{
				$tmp_1 .=  "(\"" . $h . "\",\"\")";
			}
			
			$tmp_2 .=  "(\"" . $h . "\",\"" . $p . "\")";
			
			$lastHeader = $h;
		}
		$result = mysqli_query($con, $tmp_1);
		testQuery($con, $result, $tmp_1);
		echo "Successfully inserted into tmp_1!<br>";
		
		$result = mysqli_query($con, $tmp_2);
		testQuery($con, $result, $tmp_2);
		echo "Successfully inserted into tmp_2!<br>";
	}
	
	// Index used for headers/parts array
	$index = 0;
	
	// Determine the number of students
	/*****************************************/
	$query = "SELECT COUNT(*) FROM imp_1 WHERE SUBSTRING(" . $studentID_column ." FROM 1 FOR 2) = \"T0\"";
	$result = mysqli_query($con, $query);
	testQuery($con, $result, $query);
	$row = mysqli_fetch_row($result);
	$numOfStudents = $row[0];
	
	// Get all of the student IDs
	$query = "SELECT " . $studentID_column . " FROM imp_1 WHERE SUBSTRING(" . $studentID_column . " FROM 1 FOR 2) = \"T0\"";
	$getStudents = mysqli_query($con, $query);
	testQuery($con, $getStudents, $query);
	
	while ($s = mysqli_fetch_row($getStudents))
	{
		$students[] = $s[0];
	}
	
	// Insert data into tmp_5 or insert scores
	for ($i = $student_columns+1; $i <= $count_columns; $i++)
	{
		$student_index = 0;
	
		$query = "SELECT f" . $i . " FROM imp_1 WHERE f" . $i . " NOT REGEXP '^Problem' AND f" . $i . " NOT REGEXP '^Total'";
		$answers = mysqli_query($con, $query);
		
		if ($totalNew == 0)
		{
			// Determine the QuestionID
			$query = "SELECT Question FROM QuestionsForAssignments WHERE
						  Assignment =\"" . $assignmentID . "\" AND Header = \"" . $foundHeaders[$index] .
								  "\" AND Part = \"" . $foundParts[$index] . "\"";
			$result = mysqli_query($con, $query);
			$row = mysqli_fetch_row($result);
			$questionID = $row[0];
						
			// Determine the OutOf
			$query = "SELECT OutOf FROM QuestionsForAssignments WHERE
						  Assignment =\"" . $assignmentID . "\" AND Header = \"" . $foundHeaders[$index] .
									  "\" AND Part = \"" . $foundParts[$index] . "\"";
			$result = mysqli_query($con, $query);
			$row = mysqli_fetch_row($result);
			$outOf = $row[0];
		}
		
		while ($row2 = mysqli_fetch_row($answers))
		{
			$answer = $row2[0];
			
			if ($totalNew == 0 && $student_index < $numOfStudents)
			{
				if ((double)$answer > $outOf)
				{
					echo "<br>Student's answer is greater than OutOf!<br>";
					echo "Student ID: " . $students[$student_index] . "<br>";
					echo "Answer: " . $answer . "<br>";
					echo "OutOf for question[".$questionID."]: " . $outOf . "<br>";
				}
				else if ((double)$answer < 0)
				{
					echo "<br>Student's answer can't be less than 0!<br>"; 
					echo "Student ID: " . $students[$student_index] . "<br>";
					echo "Answer: " . $answer . "<br>";
					echo "OutOf for question[".$questionID."]: " . $outOf . "<br>";
				}
				else
				{
					$dupRow = "SELECT COUNT(Student) FROM Answers WHERE Student = \"" . $students[$student_index] .
					"\" AND Assignment = \"" . $assignmentID .
					"\" AND Question = \"". $questionID . 
					"\" AND Part = \"". $foundParts[$index]  . "\"";
					
					$result = mysqli_query($con, $dupRow);
					testQuery($con, $result, $query);
					$row = mysqli_fetch_row($result);
					$dupRowCount = $row[0];
					
					if ($dupRowCount == 0)
					{
						$query = "INSERT INTO Answers(Student, Assignment, Question, Part, Answer)
				 				  VALUES (\"" . $students[$student_index] . "\",\"" . $assignmentID . "\",\"" . $questionID . "\",\"" . $foundParts[$index] . "\",\"" . $answer ."\")";
						$result = mysqli_query($con, $query);
						testQuery($con, $result, $query);
					}
					else
					{
						echo "<br><b>[Found duplicate row] </b>";
				
						if (isset($_POST["updateAnswers"]))
						{
							$query = "UPDATE Answers 
				 				  SET Answer     =\"" . $answer . "\" 
								  WHERE Student  =\"" . $students[$student_index] .
							  "\" AND Assignment =\"" . $assignmentID . 
							  "\" AND Question   =\"" . $questionID . 
							  "\" AND Part 		 =\"" . $foundParts[$index] . "\"";
							$result = mysqli_query($con, $query);
							testQuery($con, $result, $query);
							echo " Will Update <br>";
						}
						else
							echo " Will Not Update <br>";
						
						echo "\t\t\t <b>Student</b>: " . $students[$student_index] . "\t\t\t";
						echo "<b>Assignment</b>: " . $assignmentID . "\t\t\t";
						echo "<b>Question</b>: " . $questionID . "\t\t\t";
						echo "<b>Part</b>: \"" . $foundParts[$index] . "\"\t\t\t";
						echo "<b>Answer</b>: " . $answer . "<br>";
					}
					
				}
			}
			// If the answer is a letter, then put it as one of the potential answers.
			else if (!(is_numeric($answer)) && (ord($answer) > 64 && ord($answer) < 91))
			{
				// Insert all of the letters up to the current letter
				for ($a = 65; $a <= ord($answer); $a++)
				{
					$dupRow = "SELECT COUNT(*) FROM tmp_5 WHERE Header = \"" . $missingHeaders[$index] .
					"\" AND Part = \"" . $missingParts[$index] . "\" AND PotentialAnswer = \"". chr($a) . "\"";
	
					$result = mysqli_query($con, $dupRow);
					testQuery($con, $result, $query);
					$row = mysqli_fetch_row($result);
					$dupRowCount = $row[0];
	
					if ($dupRowCount == 0)
					{
						$query =  "INSERT INTO tmp_5(Header, Part, PotentialAnswer)
								   VALUES (\"" . $missingHeaders[$index] . "\",\"" . $missingParts[$index] . "\",\"" . chr($a) . "\")";
						$result = mysqli_query($con, $query);
						testQuery($con, $result, $query);
						mysqli_query($con, $query);
					}
				}
			}
			$student_index++;
		}	
		$index ++;
	}
	
	if ($totalNew != 0)
	{
		echo "Successfully inserted into tmp_5!<br>";

		// Insert data into tmp_6
		$query = "INSERT INTO tmp_6(Title, Version, CourseName, Section, Semester, Year)
			  VALUES ('" . $assignmentTitle . "','" . $version . "','" . $courseName . "','" . $section . "','" . $semester . "','" . $year . "')";
		$result = mysqli_query($con, $query);
		testQuery($con, $result, $query);
		
		echo "Successfully inserted into tmp_6!<br>";
		echo CHtml::button('Import Exam Questions' , array('submit' => array('tempTables/UpdateTmp6')));
	}
	else
		echo "Successfully inserted student scores!<br>";
	/*****************************************/
	
	
	/****************************************/
	
	}
	
	else
	{
		// Check if semester and year are in place
		
		// Put Semesters into array
		$query = "SELECT Semester FROM Semesters";
		$getSemesters = mysqli_query($con, $query);
		testQuery($con, $getSemesters, $query);
		
		while ($s = mysqli_fetch_row($getSemesters))
		{ $allSemesters[] = $s[0]; }
		
		// Put Years into array
		$query = "SELECT Year FROM Semesters";
		$getYears = mysqli_query($con, $query);
		testQuery($con, $getYears, $query);
		
		while ($y = mysqli_fetch_row($getYears))
		{ $allYears[] = $y[0]; }
		
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
			echo "Could not find Semester!<br>";
			echo CHtml::button('Add Semester' , array('submit' => array('allTables/CreateSemesters')));
		}
		else
		{
			// Check if course is missing
			$query = "SELECT CourseID FROM Courses WHERE Name = \"" . $courseName . "\"";
			$result = mysqli_query($con, $query);
			testQuery($con, $result, $query);
			$row = mysqli_fetch_row($result);
			
			if ($row[0] != "")
			{
				$courseID = $row[0];
				echo "Course ID: " . $courseID . "<br>";
				echo "Could not find Class!<br>";
				echo CHtml::button('Add Class' , array('submit' => array('allTables/CreateClasses')));
			}
			else
			{
				echo "Could not find course!<br>";
				echo CHtml::button('Add Course' , array('submit' => array('allTables/CreateCourses')));
			}	
		}
	}
	
}

?>
