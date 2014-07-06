<?php
$this->breadcrumbs=array(
		'Students',
);
?>

<h1>Student Information:</h1>
 <h2><?php echo $FirstName . " " . $LastName . "<br>"
   ."ID: " . $StudentID . "<br>";?> </h2>

<?php 

$con = Yii::App()->db;

if ($Semester == "" && $Year == "")
{
	$findSemester = "SELECT Aux_CurrentSemester();";
	$command = $con->createCommand($findSemester);
	$Semester = $command->queryScalar();
	
	$findYear = "SELECT Aux_CurrentYear();";
	$command = $con->createCommand($findYear);
	$Year = $command->queryScalar();
}

echo "<center><h2>" . $Semester . " " . $Year . " Classes</h2></center>";

$this->widget('zii.widgets.grid.CGridView', array(
		'id'=>'enrollment-grid',
		'dataProvider'=>$studentEnrollment->getSqlDataProvider($Semester, $Year, $StudentID),
		'filter'=>$studentEnrollment,
		'columns'=>array(
				// CourseName
				array(
						'name'=>'CourseName',
						'type'=>'raw',
						'value' => 'CHtml::link($data["CourseName"],
						Yii::app()->createUrl("queries/enrolledInClass", array(
						"CourseName"=>$data["CourseName"],
						"CourseNumber"=>$data["CourseNumber"],
						"Semester"=>$data["Semester"],
						"Year"=>$data["Year"],
						"Section"=>$data["Section"]
						)))',
						'htmlOptions'=>array('width'=>'140px'),
				),
				'CourseNumber',
				'Section',
				'Status',
				'StatusChange'
		),

));

echo "<center><h2>" . $Semester . " " . $Year . " Assignments</h2></center>";

$this->widget('zii.widgets.grid.CGridView', array(
		'id'=>'studentExamPercentage-grid',
		'dataProvider'=>$studentExamPercentages->getSqlDataProvider($Semester, $Year, $StudentID),
		'filter'=>$studentExamPercentages,
		'columns'=>array(
				// CourseName
				array(
						'name'=>'CourseName',
						'type'=>'raw',
						'value' => 'CHtml::link($data["CourseName"],
						Yii::app()->createUrl("queries/enrolledInClass", array(
						"CourseName"=>$data["CourseName"],
						"CourseNumber"=>$data["CourseNumber"],
						"Semester"=>$data["Semester"],
						"Year"=>$data["Year"],
						"Section"=>$data["Section"]
						)))',
						'htmlOptions'=>array('width'=>'140px'),
				),
				'Section',
				array(
						'name'=>'Title',
						'type'=>'raw',
						'value' => 'CHtml::link($data["Title"],
						Yii::app()->createUrl("queries/studentAnswers", array(
						"Student"=>"' . $StudentID . '",
						//"FirstName"=>"' . $FirstName . '",
						//"LastName"=>"' . $LastName . '",
						"Assignment"=>$data["Assignment"],
						//"Title"=>$data["Title"],
						)))',
						'htmlOptions'=>array('width'=>'140px'),
				),
				'Score',
				'MaximumPoints',
				'Percentage',
		),

));

$Semester .= " previous";

echo "<center><h2>Different Semester Classes</h2></center>";

$this->widget('zii.widgets.grid.CGridView', array(
		'id'=>'differentSemesters-grid',
		'dataProvider'=>$studentEnrollment->getSqlDataProvider($Semester, $Year, $StudentID),
		'filter'=>$studentEnrollment,
		'columns'=>array(
				// CourseName
				array(
						'name'=>'CourseName',
						'type'=>'raw',
						'value' => 'CHtml::link($data["CourseName"],
						Yii::app()->createUrl("queries/enrolledInClass", array(
						"CourseName"=>$data["CourseName"],
						"CourseNumber"=>$data["CourseNumber"],
						"Semester"=>$data["Semester"],
						"Year"=>$data["Year"],
						"Section"=>$data["Section"]
						)))',
						'htmlOptions'=>array('width'=>'140px'),
				),
				'CourseNumber',
				'Section',
				array(
						'name'=>'Semester',
						'type'=>'raw',
						'value' => 'CHtml::link("$data[Semester] $data[Year]",
						Yii::app()->createUrl("queries/studentInfo", array(
						"StudentID"=>$data["StudentID"], 
						"LastName"=>$data["LastName"],
						"FirstName"=>$data["FirstName"],
						"Semester"=>$data["Semester"],
						"Year"=>$data["Year"],
						)))',
						
				),
				'Status',
				'StatusChange'
		),

));


?>
   
   
