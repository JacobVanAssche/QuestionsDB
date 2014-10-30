<?php
$this->breadcrumbs=array(
		'UpdateAnswers',
		$Answers->Student=>array('/Answers'),
);
?>
<h1>Update Answer</h1>

<?php echo $this->renderPartial('Answers_form', array('Answers'=>$Answers)); ?>