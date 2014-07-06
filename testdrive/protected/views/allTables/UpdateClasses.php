<?php
$this->breadcrumbs=array(
		'UpdateClasses',
		$Classes->ClassID=>array('/Classes'),
);
?>
<h1>Update Classes</h1>

<?php echo $this->renderPartial('Classes_Form', array('Classes'=>$Classes)); ?>

<?php echo CHtml::button('View Courses' , array('submit' => array('allTables/ViewCourses'))); ?>

<?php echo CHtml::button('View Semesters' , array('submit' => array('allTables/ViewSemesters'))); ?>