<?php
$this->breadcrumbs=array(
		'UpdateAssignments',
		$Assignments->AssignmentID=>array('/Assignments'),
);
?>
<h1>Update Assignment</h1>

<?php echo $this->renderPartial('Assignments_Form', array('Assignments'=>$Assignments)); ?>