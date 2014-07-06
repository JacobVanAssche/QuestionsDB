<?php
/* @var $this TempTablesController */
/* @var $model Tmp1 */
/* @var $form CActiveForm */
?>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'Assignments-form',
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($Assignments); ?>

	<div class="row">
		<?php echo $form->labelEx($Assignments,'AssignmentID'); ?>
		<?php echo $form->textField($Assignments,'AssignmentID',array('size'=>60,'maxlength'=>128)); ?>
		<?php echo $form->error($Assignments,'AssignmentID'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($Assignments,'Class'); ?>
		<?php echo $form->textField($Assignments,'Class',array('size'=>60,'maxlength'=>128)); ?>
		<?php echo $form->error($Assignments,'Class'); ?>
	</div>
	
	<div class="row">
		<?php echo $form->labelEx($Assignments,'Title'); ?>
		<?php echo $form->textField($Assignments,'Title',array('size'=>60,'maxlength'=>128)); ?>
		<?php echo $form->error($Assignments,'Title'); ?> 
	</div>
	
	<div class="row">
		<?php echo $form->labelEx($Assignments,'Version'); ?>
		<?php echo $form->textField($Assignments,'Version',array('size'=>60,'maxlength'=>128)); ?>
		<?php echo $form->error($Assignments,'Version'); ?> 
	</div>
	
	<div class="row">
		<?php echo $form->labelEx($Assignments,'TexFile'); ?>
		<?php echo $form->textField($Assignments,'TexFile',array('size'=>60,'maxlength'=>128)); ?>
		<?php echo $form->error($Assignments,'TexFile'); ?>
	</div>
	
	<div class="row">
		<?php echo $form->labelEx($Assignments,'Date'); ?>
		<?php echo $form->textField($Assignments,'Date',array('size'=>60,'maxlength'=>128)); ?>
		<?php echo $form->error($Assignments,'Date'); ?>
	</div>
	
	<div class="row">
		<?php echo $form->labelEx($Assignments,'Comments'); ?>
		<?php echo $form->textField($Assignments,'Comments',array('size'=>60,'maxlength'=>128)); ?>
		<?php echo $form->error($Assignments,'Comments'); ?>
	</div>

	<div class="row buttons">
	<input type = "submit" name = "action" value = "Save" />
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->