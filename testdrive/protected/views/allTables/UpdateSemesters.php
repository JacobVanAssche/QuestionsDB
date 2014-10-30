<?php
$this->breadcrumbs=array(
		'UpdateSemesters',
		$Semesters->Semester=>array('/Answers'),
);
?>
<h1>Update Semesters</h1>

<?php echo $this->renderPartial('Semesters_form', array('Semesters'=>$Semesters)); ?>