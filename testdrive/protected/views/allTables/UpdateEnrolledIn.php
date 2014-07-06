<?php
$this->breadcrumbs=array(
		'UpdateEnrolledIn',
		$EnrolledIn->Student=>array('/EnrolledIn'),
);
?>
<h1>Update EnrolledIn</h1>

<?php echo $this->renderPartial('EnrolledIn_Form', array('EnrolledIn'=>$EnrolledIn)); ?>

<?php echo CHtml::button('View EnrolledIn' , array('submit' => array('allTables/ViewEnrolledIn'))); ?>
<br><br>
<?php echo CHtml::button('Import Enrollment from CSV file' , array('submit' => array('allTables/ImportEnrollment'))); ?>