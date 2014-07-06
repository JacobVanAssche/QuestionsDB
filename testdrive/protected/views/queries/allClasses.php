<?php
$this->breadcrumbs=array(
		'AllClassesCurrentSemester',
);
// $Semester
// $Year
?>

<h1>Classes</h1>

<?php echo $this->renderPartial('semesters_Form'); ?>

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

$query = "CALL ClassesInSemesterX(\"" . $Semester . "\",\"" . $Year . "\");";


$dataProvider = new CSqlDataProvider($query, array(
		'keyField' => 'Name',
));
$dataProvider->getData();
$total=$dataProvider->getItemCount();

$this->widget('zii.widgets.grid.CGridView', array(
		'id'=>'answers-grid',
		'dataProvider'=>$dataProvider,	
		'summaryText'=>'Total:'.$total,
		//'filter'=>$dataProvider,
		'columns'=>array(
				// CourseName
				array(
						'name'=>'Name',
						'type'=>'raw',
						'value' => 'CHtml::link($data["Name"], 
						Yii::app()->createUrl("queries/enrolledInClass", array(
						"CourseName"=>$data["Name"], 
						"CourseNumber"=>$data["Number"],
						"Semester"=>$data["Semester"],
						"Year"=>$data["Year"],
						"Section"=>$data["Section"]
						)))',
						'htmlOptions'=>array('width'=>'140px'),
				),
				// Number
				array(
						'name'=>'Number',
						'type'=>'raw',
						'htmlOptions'=>array('width'=>'100px'),
				),
				// Semester
				array(
						'name'=>'Semester',
						'type'=>'raw',
						'htmlOptions'=>array('width'=>'100px'),
				),
				// Year
				array(
						'name'=>'Year',
						'type'=>'raw',
						'htmlOptions'=>array('width'=>'50px'),
				),
				// Section
				array(
						'name'=>'Section',
						'type'=>'raw',
						'htmlOptions'=>array('width'=>'50px'),
				),
		),
));


echo CHtml::button('Create New Class' , array('submit' => array('allTables/CreateClasses')));
echo "<br><br>";
echo CHtml::button('Create New Course' , array('submit' => array('allTables/CreateCourses')));
echo "<br><br>";
echo CHtml::button('See All Students in Selected Semester' , array('submit' => array('queries/Enrollment', 'Semester'=>$Semester, 'Year'=>$Year)));
?>
