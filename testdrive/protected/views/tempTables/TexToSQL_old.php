<?php
/* @var $this TempTablesController */

$this->breadcrumbs=array(
	'TexToSQL',
);
?>

<h1>Import Assignment from Tex File</h1>

<html>
	<body>
		<form method="post" enctype="multipart/form-data">
		<b>Tex File to import</b> <br> <input type="file" name="tex_file"> <br><br>
		<input type="submit" name="enter" value="Import">
		</form>
	</body>
</html>


<?php 
if(isset($_POST['enter']))
{
	$con = Yii::App()->db;
	
	$path = $_FILES["tex_file"]["tmp_name"];
	
	echo "<br>OS Running: ";
	
	// Check for which operating system is running and adjust path accordingly
	if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN')
	{
		echo "Windows<br>";
		system("tex_sql " . $path);
	}
	else if (strtoupper(substr(PHP_OS, 0, 3)) === 'LIN')
	{
		echo "Linux<br>";
		system("./tex_sql " . $path);
	}
	
	echo "Importing SQL file into database...<br>";
	// Put the ExamImport.sql content into an array
	$lines = file('ExamImport.sql');
	
	// Go through each line of the file and insert all of the queries into database
	$query = "";
	for ($i = 0; $i < count($lines); $i++)
	{
		$query .= $lines[$i];
		echo $query.'<br>';
		// Get the last position of each line
		$lastCharPos = (strlen($lines[$i]))-2;
		// Get last character in the line
		$lastChar = substr($lines[$i], $lastCharPos, 1);
		// Get second to last character in the line
		$beforeLastChar = substr($lines[$i], $lastCharPos-1, 1);
		
		// Check if it is the end of the query
		if (($lastChar == ";" && $beforeLastChar == ")") || ($lastChar == ";" && $i < 7))
		{
			//echo $query . "<br>";
			// Execute query
			$command = $con->createCommand($query);
			$command->execute();
			$query = "";
		}
	}
	
	echo CHtml::button('Review' , array('submit' => array('tempTables/ReviewAll')));
}


?>
