<?php
/* @var $this TempTablesController */
/* @var $model Tmp1 */
/* @var $form CActiveForm */
?>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'Answers-form',
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($Semesters); ?>

	<div class="row">
		<?php echo $form->labelEx($Semesters,'Semester'); ?>
		<?php echo $form->textField($Semesters,'Semester',array('size'=>60,'maxlength'=>128)); ?>
		<?php echo $form->error($Semesters,'Semester'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($Semesters,'Year'); ?>
		<?php echo $form->textField($Semesters,'Year',array('size'=>60,'maxlength'=>128)); ?>
		<?php echo $form->error($Semesters,'Year'); ?>
	</div>
	
	<div class="row">
		<b>Start Date (YYYY-MM-DD)</b><br>
		<?php echo $form->textField($Semesters,'StartDate',array('size'=>60,'maxlength'=>128)); ?>
		<?php echo $form->error($Semesters,'StartDate'); ?> 
	</div>
	
	<div class="row">
		<b>End Date (YYYY-MM-DD)</b><br>
		<?php echo $form->textField($Semesters,'EndDate',array('size'=>60,'maxlength'=>128)); ?>
		<?php echo $form->error($Semesters,'EndDate'); ?> 
	</div>

	<div class="row buttons">
	<input type = "submit" name = "action" value = "Save" /> 
	<input type = "submit" name = "action" value = "Create Course" /> 
	<input type = "submit" name = "action" value = "Create Class" /> <br>
	<input type = "submit" name = "action" value = "View Semesters" />
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->