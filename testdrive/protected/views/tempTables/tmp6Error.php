<?php
$this->breadcrumbs=array(
		'Tmp6Error',
);
?>

<h1>Error!</h1>

<?php 

if ($text == 'semester')
{
	echo "Could not find Semester!<br>";
	echo CHtml::button('Add Semester' , array('submit' => array('allTables/CreateSemesters')));
}

else if ($text == 'course')
{
	echo "Could not find course!<br>";
	echo CHtml::button('Add Course' , array('submit' => array('allTables/CreateCourses')));
}

else if ($text == 'class')
{
	echo "Could not find class!<br>";
	echo CHtml::button('Add Class' , array('submit' => array('allTables/CreateClasses')));
}


?>