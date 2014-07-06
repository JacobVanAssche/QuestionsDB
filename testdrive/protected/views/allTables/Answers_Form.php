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

	<?php echo $form->errorSummary($Answers); ?>

	<div class="row">
		<?php echo $form->labelEx($Answers,'Student'); ?>
		<?php echo $form->textField($Answers,'Student',array('size'=>60,'maxlength'=>128)); ?>
		<?php echo $form->error($Answers,'Student'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($Answers,'Assignment'); ?>
		<?php echo $form->textField($Answers,'Assignment',array('size'=>60,'maxlength'=>128)); ?>
		<?php echo $form->error($Answers,'Assignment'); ?>
	</div>
	
	<div class="row">
		<?php echo $form->labelEx($Answers,'Question'); ?>
		<?php echo $form->textField($Answers,'Question',array('size'=>60,'maxlength'=>128)); ?>
		<?php echo $form->error($Answers,'Question'); ?> 
	</div>
	
	<div class="row">
		<?php echo $form->labelEx($Answers,'Part'); ?>
		<?php echo $form->textField($Answers,'Part',array('size'=>60,'maxlength'=>128)); ?>
		<?php echo $form->error($Answers,'Part'); ?> 
	</div>
	
	<div class="row">
		<?php echo $form->labelEx($Answers,'Answer'); ?>
		<?php echo $form->textField($Answers,'Answer',array('size'=>60,'maxlength'=>128)); ?>
		<?php echo $form->error($Answers,'Answer'); ?>
	</div>

	<div class="row buttons">
	<input type = "submit" name = "action" value = "Save and view all Answers" />
	<input type = "submit" name = "action" value = "Save and go to current Student/Assignment" />
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->