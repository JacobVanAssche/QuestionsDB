<?php
$this->breadcrumbs=array(
		'Finalize',
);
?>

<h1>Finalize</h1>

<?php 

$con1 = Yii::App()->db;

$query = "SET SQL_MODE = 'NO_BACKSLASH_ESCAPES'";
$command = $con1->createCommand($query);
$command->execute();

// Get Title
$query = "SELECT Title FROM tmp_6";
$command = $con1->createCommand($query);
$title = $command->queryScalar();

// Get Version
$query = "SELECT Version FROM tmp_6";
$command = $con1->createCommand($query);
$version = $command->queryScalar();

// Get coursename
$query = "SELECT CourseName FROM tmp_6";
$command = $con1->createCommand($query);
$courseName = $command->queryScalar();

// Get section
$query = "SELECT Section FROM tmp_6";
$command = $con1->createCommand($query);
$section = $command->queryScalar();

// Get semester
$query = "SELECT Semester FROM tmp_6";
$command = $con1->createCommand($query);
$semester = $command->queryScalar();

// Get year
$query = "SELECT Year FROM tmp_6";
$command = $con1->createCommand($query);
$year = $command->queryScalar();

// Get TexFile
$query = "SELECT TexFile FROM tmp_6";
$command = $con1->createCommand($query);
$texFile = $command->queryScalar();

// Get Date
$query = "SELECT Date FROM tmp_6";
$command = $con1->createCommand($query);
$date = $command->queryScalar();

echo "Exam info:<br>";
echo "Title: ". $title . "<br>";
echo "Version: ". $version . "<br>";
echo "CourseName: ". $courseName . "<br>";
echo "Section: ". $section . "<br>";
echo "Semester: ". $semester . "<br>";
echo "Year: ". $year . "<br>";
echo "TexFile: ". $texFile . "<br>";
echo "Date: ". $date . "<br><br>";


// Get the ClassID
//------------------------------------
$query = "SELECT Aux_ClassID(\"" . $courseName . "\",\"" . $section . "\",\"" . $semester . "\",\"" . $year . "\")";
$command = $con1->createCommand($query);
$classID = $command->queryScalar();

if ($classID != "")
{
	echo "Class ID found!<br>";
	echo "Class ID: " . $classID . "<br>";

	//--------------------------------------
	// Check if Assignment exists
	//--------------------------------------
	
	// Case I: Version is not empty
	if ($version !="") 
	{
	$query = "SELECT COUNT(*) FROM `Assignments` WHERE Class = \"" . $classID . "\" AND Title = \"" . $title . "\" AND Version = \"" . 		$version ."\"";
	}
	else
	// Case II: Version is empty
	{
	$query = "SELECT COUNT(*) FROM `Assignments` WHERE Class = \"" . $classID . "\" AND Title = \"" . $title . "\"";
	}

	$command = $con1->createCommand($query);
	$assignmentID = $command->queryScalar();	

	// Assignment already exists
	if ($assignmentID != 0)
	{
		// Case I: Version is not empty
		if ($version !="")
		{
		$query = "SELECT `AssignmentID` FROM `Assignments` WHERE Class = \"" . $classID . "\" AND Title = \"" . $title . "\" AND 			Version = \"" . $version ."\"";
		}
		else
		// Case II: Version is empty
		{
		$query = "SELECT `AssignmentID` FROM `Assignments` WHERE Class = \"" . $classID . "\" AND Title = \"" . $title ."\"";
		}

		$command = $con1->createCommand($query);
		$assignmentID = $command->queryScalar();
		
		echo "Assignment ID exists: " . $assignmentID . "<br><br>";
		
		// Update the information of the Assignment
		$query = "UPDATE Assignments SET TexFile = \"" . $texFile . "\", Date = \"" . $date . "\" WHERE AssignmentID = " . 			$assignmentID;
		$command = $con1->createCommand($query);
		$command->execute();
		
	}
	// Assignment does not exist
	else
	{
		echo "Assignment ID does not exist!<br>";
		
		$query = "INSERT INTO `Assignments`(Class, Title, Version, TexFile, Date) 
				  VALUES (\"" . $classID . "\",\"" . $title . "\",\"". $version . "\",\"" . $texFile . "\",\"" . $date . "\")";
		$command = $con1->createCommand($query);
		$command->execute();
		
		//Case I: Version is not empty
		if ($version !="")
		{
			$query = "SELECT `AssignmentID` FROM `Assignments` WHERE Class = \"" . $classID . "\" AND Title = \"" . $title . "\" AND 			Version = \"" .	$version ."\"";
		}
		else
		//Case II: Version is empty
		{
			$query = "SELECT `AssignmentID` FROM `Assignments` WHERE Class = \"" . $classID . "\" AND Title = \"" . $title ."\"";
		}
		$command = $con1->createCommand($query);
		$assignmentID = $command->queryScalar();

		echo "New AssignmentID inserted: " . $assignmentID . "<br><br>";
	}
	
	// Put Headers into array
	$query = "SELECT Header FROM tmp_2";
	$command = $con1->createCommand($query);
	$dataReader = $command->queryAll();

	foreach($dataReader as $row)
	{ $headers[] = $row['Header']; }
	
	// Put Parts into array
	$query = "SELECT Part FROM tmp_2";
	$command = $con1->createCommand($query);
	$dataReader = $command->queryAll();
	
	foreach($dataReader as $row)
	{ $parts[] = $row['Part']; }
	
	$lastQuestionText = null;
	// Check for questions associated with assignment
	for ($i = 0; $i < count($headers); $i++)
	{
		$combinedHeader = $headers[$i] . " " . $parts[$i];
		
		if (strpos($combinedHeader, "Problem") === FALSE)
		{
			$combinedHeader = "Problem " . $headers[$i] . " " . $parts[$i];
		}
		
		// Get question text
		$query = "SELECT QuestionText FROM tmp_1
				  WHERE Header =\"" . $headers[$i] . "\"";
		$command = $con1->createCommand($query);
		$questionText = $command->queryScalar();
		
		if ($questionText !== $lastQuestionText)
		{
			$query = "SELECT Question FROM QuestionsForAssignments 
				  WHERE Assignment = " . $assignmentID . " AND Header = \"" . $combinedHeader . "\"
				  AND Part = \"" . $parts[$i] . "\"";
			$command = $con1->createCommand($query);
			$questionID = $command->queryScalar();
		}
		
		// If there exists a question with this header, then update the QuestionText field
		if ($questionID != "" && $questionText !== $lastQuestionText)
		{
			$query = "UPDATE Questions SET QuestionText = \"" . $questionText . "\" WHERE QuestionID = " . $questionID;
			$command = $con1->createCommand($query);
			$command->execute();
			
			echo "Question FOUND: " . $combinedHeader . " [" . $questionID . "]<br>";
		}
		// Question does not exist -- insert new question with information
		else
		{
			//Check if the question contains parts
			if ($parts[$i] != "")
			{
				// Check if the question text has already been inserted
				if ($questionText !== $lastQuestionText)
				{
					// Create new question into Questions table
					$query = "INSERT INTO Questions(QuestionText) VALUES (\"" . $questionText . "\")";
					$command = $con1->createCommand($query);
					$command->execute();
					$questionID = Yii::app()->db->getLastInsertID();
				}
				
				// Insert into QuestionParts
				$query = "INSERT INTO QuestionParts(Question, Part)
					      VALUES (\"" . $questionID . "\", \"" . $parts[$i] . "\")";
				$command = $con1->createCommand($query);
				$command->execute();
			
			}
			else
			{
				// Create new question into Questions table
				$query = "INSERT INTO Questions(QuestionText) VALUES (\"" . $questionText . "\")";
				$command = $con1->createCommand($query);
				$command->execute();
				$questionID = Yii::app()->db->getLastInsertID();
			}
			
			$lastQuestionText = $questionText;

			// Get OutOf from tmp_2
			$query = "SELECT OutOf FROM tmp_2
				  WHERE Header =\"" . $headers[$i] . "\"
				  AND Part = \"" . $parts[$i] . "\"";
			$command = $con1->createCommand($query);
			$outOf = $command->queryScalar();
			
			echo "Question INSERTED: " . $combinedHeader . " [" . $questionID . "]<br>";
			
			//Insert the new question into QuestionsForAssignments table
			 $query =  "INSERT INTO QuestionsForAssignments(Assignment, Header, Question, Part, OutOf)
			VALUES (\"" . $assignmentID . "\",\"" . $combinedHeader . "\",\"" . $questionID . "\",\"" . $parts[$i] . "\",\"" . $outOf . "\")";
			$command = $con1->createCommand($query);
			$command->execute();
		}
		
		// Store links for the images
		$query = "SELECT Image FROM tmp_3
				  WHERE Header =\"" . $headers[$i] . "\"";
		$command = $con1->createCommand($query);
		$image = $command->queryScalar();
		
		if ($image != "")
		{
			$URI = "\\" . $year . " " . $semester . "\\" . $courseName . "\\" . $title . "\\" . $image;
			$dupRow = "SELECT COUNT(*) FROM Links WHERE Question = \"" . $questionID . "\"";
			$command = $con1->createCommand($dupRow);
			$dupRowCount = $command->queryScalar();
				
			if ($dupRowCount == 0)
			{
				echo "Image to insert: " . $image . "<br>";
				$query =  "INSERT INTO Links(URI, Question)
				VALUES (\"" . $URI . "\",\"" . $questionID . "\")"; 
				$command = $con1->createCommand($query);
				$command->execute();
			}
			
		}
		
		// Store tex commands
		$query = "SELECT Command FROM tmp_4
				  WHERE Header =\"" . $headers[$i] . "\"";
		$command = $con1->createCommand($query);
		$texCommand = $command->queryScalar();
		
		if ($texCommand != "")
		{

			$dupRow = "SELECT COUNT(*) FROM TexCommands WHERE Question = \"" . $questionID . "\"";
			$command = $con1->createCommand($dupRow);
			$dupRowCount = $command->queryScalar();
			
			if ($dupRowCount == 0)
			{
				echo "Command to insert: " . $texCommand . "<br>";
				$query =  "INSERT INTO TexCommands(Command, Question)
				VALUES (\"" . $texCommand . "\",\"" . $questionID . "\")";
				$command = $con1->createCommand($query);
				$command->execute();
			}
		}
		
		$query = "SELECT COUNT(Header) FROM tmp_5 WHERE Header = \"" . $headers[$i] . "\" AND Part = \"" . $parts[$i] . "\"";
		//$result = mysqli_query($con, $query);
		$command = $con1->createCommand($query);
		$numAnswers = $command->queryScalar();
		
		// Populate PotentialAnswers table
		$query = "SELECT PotentialAnswer FROM tmp_5
					  WHERE Header=\"" . $headers[$i] . "\" AND Part = \"" . $parts[$i] . "\"";
		$command = $con1->createCommand($query);
		$dataReader = $command->queryAll();
		
		foreach($dataReader as $row)
		{ $potentialAnswers[] = $row['PotentialAnswer']; }
		
		if ($numAnswers > 0)
		{
			for ($j = 0; $j < $numAnswers; $j++)
			{
				if (isset($potentialAnswers[$j]))
				{
				$query = "SELECT PotentialAnswerText FROM tmp_5
						     WHERE Header =\"" .$headers[$i] . "\" AND Part = \"" . $parts[$i] . "\"
						     AND PotentialAnswer =\"" . $potentialAnswers[$j] . "\"";
				$command = $con1->createCommand($query);
				$answerText = $command->queryScalar();
				
				$query = "SELECT CorrectAnswer FROM tmp_5
						     WHERE Header =\"" .$headers[$i] . "\" AND Part = \"" . $parts[$i] . "\"
						     AND PotentialAnswer =\"" . $potentialAnswers[$j] . "\"";
				$command = $con1->createCommand($query);
				$correctAnswer = $command->queryScalar();
				
				$dupRow = "SELECT COUNT(*) FROM PotentialAnswers WHERE Question = \"" . $questionID . 
				"\" AND Part = \"" . $parts[$i] . "\" AND PotentialAnswer = \"". $potentialAnswers[$j] . "\"";
				$command = $con1->createCommand($dupRow);
				$dupRowCount = $command->queryScalar();
				
				if ($dupRowCount == 0)
				{
					$query = "INSERT INTO PotentialAnswers(Question, Part, PotentialAnswer, PotentialAnswerText, CorrectAnswer)
							      VALUES (\"" . $questionID . "\",\"" . $parts[$i] . "\",\"" . $potentialAnswers[$j] . "\",\"" . $answerText . "\",\"" . $correctAnswer . "\")";
					$command = $con1->createCommand($query);
					$command->execute();

				}
				}
			}
			$dupRow = "SELECT COUNT(*) FROM PotentialAnswers WHERE Question = \"" . $questionID .
			"\" AND Part = \"" . $parts[$i] . "\" AND PotentialAnswer = \"NA\"";
			$command = $con1->createCommand($dupRow);
			$dupRowCount = $command->queryScalar();
			
			if ($dupRowCount == 0)
			{
				$query = "INSERT INTO PotentialAnswers(Question, Part, PotentialAnswer, PotentialAnswerText, CorrectAnswer)
							      VALUES (\"" . $questionID . "\",\"" . $parts[$i] . "\",\"NA\",\"\",\"0\")";
				$command = $con1->createCommand($query);
				$command->execute();
			}
		}
		echo "<br>";
	}
	echo CHtml::button('Import Student Scores' , array('submit' => array('tempTables/ImportStudentsScores')));
	
	
}
else
{	
	
	// Check if course is missing
	$query = "SELECT CourseID FROM Courses WHERE Name = \"" . $courseName . "\"";
	$command = $con1->createCommand($query);
	$courseID = $command->queryScalar();
	
	if ($courseID != "")
	{
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

?>
