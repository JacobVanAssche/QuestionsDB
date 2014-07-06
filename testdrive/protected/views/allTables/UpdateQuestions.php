<?php
$this->breadcrumbs=array(
		'UpdateQuestions',
		$Questions->QuestionID=>array('/Questions'),
);
?>
<h1>Update Question</h1>

<?php echo $this->renderPartial('Questions_Form', array('Questions'=>$Questions)); ?>