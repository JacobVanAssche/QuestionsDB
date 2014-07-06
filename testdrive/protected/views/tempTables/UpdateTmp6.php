<?php
$this->breadcrumbs=array(
		'Temp Tables'=>array('/tempTables'),
		'UpdateTmp6',
		$tmp6->Title=>array('/Tmp6'),
		'UpdateTmp6',
);
?>

<h1>Update Exam Information</h1>

<?php echo $this->renderPartial('tmp6_form', array('tmp6'=>$tmp6)); ?>

<?php echo CHtml::button('Create New Assignment from Tex File' , array('submit' => array('tempTables/TexToSQL'))); ?>


