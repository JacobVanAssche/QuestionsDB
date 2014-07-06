<?php
/* @var $this TempTablesController */
/* @var $model Tmp1 */
/* @var $form CActiveForm */
?>

<?php 

$con = Yii::app()->db;

// Get Headers for drop down menu
$query = "SELECT Header FROM tmp_1";
$command = $con->createCommand($query);
$dataReader = $command->queryAll();

$headers = "";
foreach($dataReader as $row)
{
	$headers .= "<option value='{$row['Header']}'>{$row['Header']}</option>";
}

?>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'tmp-form',
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($tmp5); ?>

	<div class="row">
		<b>Header</b> <br> <select name = 'Header'>
		<?php echo $headers;?> </select> <br>
	</div>

	<div class="row">
		<?php echo $form->labelEx($tmp5,'Part'); ?>
		<?php echo $form->textField($tmp5,'Part',array('size'=>60,'maxlength'=>128)); ?>
		<?php echo $form->error($tmp5,'Part'); ?>
	</div>
	
	<div class="row">
		<?php echo $form->labelEx($tmp5,'PotentialAnswer'); ?>
		<?php echo $form->textField($tmp5,'PotentialAnswer',array('size'=>60,'maxlength'=>128)); ?>
		<?php echo $form->error($tmp5,'PotentialAnswer'); ?> 
	</div>
	
	<div class="row">
		<?php echo $form->labelEx($tmp5,'PotentialAnswerText'); ?>
		<?php echo $form->textField($tmp5,'PotentialAnswerText',array('size'=>60,'maxlength'=>128)); ?>
		<?php echo $form->error($tmp5,'PotentialAnswerText'); ?>
	</div>
	
	<div class="row">
		<?php echo $form->labelEx($tmp5,'Correct Answer? (0= False, 1= True)'); ?>
		<?php echo $form->textField($tmp5,'CorrectAnswer',array('size'=>60,'maxlength'=>128)); ?>
		<?php echo $form->error($tmp5,'CorrectAnswer'); ?>
	</div>

	<div class="row buttons">
	<input type = "submit" name = "action" value = "Add" /><br>
	<input type = "submit" name = "action" value = "Edit Problems and Parts" /><br>
	<input type = "submit" name = "action" value = "Edit Potential Answers" /><br>
	<input type = "submit" name = "action" value = "Review All" />
	
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->