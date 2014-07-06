<?php
$this->breadcrumbs=array(
		'Assignments',
);
?>

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


echo "<h1>Assignments in " . $Semester . " " . $Year . "</h1>";
echo $this->renderPartial('semesters_Form');
echo "<br>";
echo CHtml::button('Import Students Scores' , array('submit' => array('tempTables/ImportStudentsScores')));
echo "<br><br>";
echo "<b>Create new Assignment: </b>";
echo CHtml::button('Manually' , array('submit' => array('tempTables/UpdateTmp6')));
echo CHtml::button('From Tex File' , array('submit' => array('tempTables/TexToSQL'))); 
echo "<br>";

$this->widget('zii.widgets.grid.CGridView', array(
		'id'=>'assignments1-grid',
		'dataProvider'=>$allExamAverages->getSqlDataProvider($Semester, $Year),
		'filter'=>$allExamAverages,
		'columns'=>array(
				'CourseName',
				'CourseNumber',
				'Section',
				// Title
				array(
						'name'=>'Title',
						'type'=>'raw',
						'value' => 'CHtml::link($data["Title"], Yii::app()->createUrl("queries/assignmentInfo", array(
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


