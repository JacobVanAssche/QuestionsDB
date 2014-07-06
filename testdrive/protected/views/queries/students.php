<?php
$this->breadcrumbs=array(
		'Students',
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

echo "<h1>Students in " . $Semester . " " . $Year . "</h1>";

// Form to change semester
echo $this->renderPartial('semesters_Form');
echo "<br>";

echo CHtml::button('Create New Enrollment' , array('submit' => array('allTables/createEnrolledIn')));

$this->widget('zii.widgets.grid.CGridView', array(
		'id'=>'enrollment-grid',
		'dataProvider'=>$model->getSqlDataProvider($Semester, $Year),
		'filter'=>$model,
		'columns'=>array(
				array(
						'name'=>'StudentID',
						'type'=>'raw',
						'value' => 'CHtml::link($data["StudentID"], Yii::app()->createUrl("queries/studentInfo", array(
						"StudentID"=>$data["StudentID"], 
						"LastName"=>$data["LastName"],
						"FirstName"=>$data["FirstName"],
						"Semester"=>"' . $Semester . '",
						"Year"=>"' . $Year . '",
						)))',
				),
				// LastName
				array(
				'name'=>'LastName',
				'type'=>'raw',
				'value' => 'CHtml::link($data["LastName"], Yii::app()->createUrl("queries/studentInfo", array(
						"StudentID"=>$data["StudentID"],
						"LastName"=>$data["LastName"],
						"FirstName"=>$data["FirstName"],
						"Semester"=>"' . $Semester . '",
						"Year"=>"' . $Year . '",
						)))',
				),
				// FirstName
				array(
				'name'=>'FirstName',
				'type'=>'raw',
				'value' => 'CHtml::link($data["FirstName"], Yii::app()->createUrl("queries/studentInfo", array(
						"StudentID"=>$data["StudentID"],
						"LastName"=>$data["LastName"],
						"FirstName"=>$data["FirstName"],
						"Semester"=>"' . $Semester . '",
						"Year"=>"' . $Year . '",
						)))',
				),
				//'Username',
				// Email
				array(
						'name'=>'Email',
						'type'=>'raw',
						'value'=>'CHtml::link($data["Email"], "mailto:$data[Email]")'
				),
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
				//'CourseNumber',
				'Section',
				'Semester',
				'Year',
				'Status',
				'StatusChange',
			),
		
));

   
   
