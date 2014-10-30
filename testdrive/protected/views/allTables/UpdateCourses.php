<?php
$this->breadcrumbs=array(
		'UpdateCourses',
		$Courses->CourseID=>array('/CourseID'),
);
?>
<h1>Update Courses</h1>

<?php echo $this->renderPartial('Courses_Form', array('Courses'=>$Courses)); ?>

<?php echo CHtml::button('View Courses' , array('submit' => array('allTables/ViewCourses'))); ?>