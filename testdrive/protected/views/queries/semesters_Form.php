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

?>

<html>
	<body>
		<form method="post">
		<br> <select name = "semester">
		<?php echo $semesters;?> </select> 
		<input type="submit" value="Change Semester">
		</form>
	</body>
</html>