<?php
$this->breadcrumbs=array(
		'Temp Tables'=>array('/tempTables'),
		'AddProblem',
		$tmp1->Header=>array('/Tmp1'),
		$tmp2->Header=>array('/Tmp2'),
);
?>

<h1>Add Problem</h1>

<?php echo $this->renderPartial('AddProblem_Form', array('tmp1'=>$tmp1, 'tmp2'=>$tmp2)); ?>