<?php
$this->breadcrumbs=array(
		'EnrolledInClass',
);
// $CourseName
// $CourseNumber
// $Section
// $Semester
// $Year
?>


<h1>Enrollment Information for<br><?php echo $CourseName . "<br>Section: " . $Section; ?></h1>

<?php 
$con = Yii::app()->db;
$classIDquery = "SELECT 	DISTINCT c.ClassID
		FROM 		Courses
		JOIN 		Classes as c
		ON			\"" . $Semester . "\"= c.Semester
		AND			\"" . $Year . "\"= c.Year
		AND 		Course = Courses.CourseID
		AND 		\"" . $Section . "\"= c.Section
		WHERE		\"" . $CourseName ."\"= Name
		AND			\"" . $CourseNumber . "\"= Number";
$command = $con->createCommand($classIDquery);
$classID = $command->queryScalar();

echo "ClassID: " .  $classID . "<br>";
echo "Semester: " . $Semester . " " . $Year . "<br>";


$this->widget('zii.widgets.grid.CGridView', array(
		'id'=>'answers-grid',
		'dataProvider'=>$model->getSqlDataProvider($classID),
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
				'LastName',
				'FirstName',
				'Status',
				'StatusChange',
				'Username',
				// Email
				array(
						'name'=>'Email',
						'type'=>'raw',
						'value'=>'CHtml::link($data["Email"], "mailto:$data[Email]")'
				),
				'Notes',
		),
));

echo CHtml::button('Create New Enrollment' , array('submit' => array('allTables/createEnrolledInClass&classID=' . $classID)));

?>