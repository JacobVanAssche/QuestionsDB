<?php
$this->breadcrumbs=array(
		'Temp Tables'=>array('/tempTables'),
		'AddPotentialAnswer',
		$tmp5->Header=>array('/Tmp5'),
);
?>

<h1>Add Potential Answer</h1>

<?php echo $this->renderPartial('addPotentialAnswer_Form', array('tmp5'=>$tmp5)); ?>
