<?php
$this->breadcrumbs=array(
		'Questions',
);
?>

<h1>Questions</h1>

<?php 
$con = Yii::App()->db;

if ($Semester == "" || $Year == "")
{
	$findSemester = "SELECT Aux_CurrentSemester();";
	$command = $con->createCommand($findSemester);
	$Semester = $command->queryScalar();
	
	$findYear = "SELECT Aux_CurrentYear();";
	$command = $con->createCommand($findYear);
	$Year = $command->queryScalar();
}

// Form to change semester
echo $this->renderPartial('semesters_Form');
echo "<br>";

echo "<h1>Assignments in " . $Semester . " " . $Year . "</h1>";

$this->widget('zii.widgets.grid.CGridView', array(
		'id'=>'assignments2-grid',
		'dataProvider'=>$model->getSqlDataProvider($Semester, $Year),
		'filter'=>$model,
		'columns'=>array(
				'CourseName',
				'CourseNumber',
				'Section',
				// Title
				array(
						'name'=>'Title',
						'type'=>'raw',
						'value' => 'CHtml::link($data["Title"], Yii::app()->createUrl("queries/questionsInfo", array(
						"AssignmentID"=>$data["AssignmentID"],
						"Title"=>$data["Title"],
						"CourseName"=>$data["CourseName"],
						"CourseNumber"=>$data["CourseNumber"],
						"Section"=>$data["Section"],
						"Semester"=>"' . $Semester . '",
						"Year"=>"' . $Year . '",
						)))'),
				//'Average',
				//'MaximumPoints',
				'AveragePercentage',
				'ParticipationPercentage',
		),

));


