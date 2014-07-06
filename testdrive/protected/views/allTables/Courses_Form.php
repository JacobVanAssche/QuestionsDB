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

	<?php echo $form->errorSummary($Courses); ?>

	<div class="row">
		<?php echo $form->labelEx($Courses,'Name'); ?>
		<?php echo $form->textField($Courses,'Name',array('size'=>60,'maxlength'=>128)); ?>
		<?php echo $form->error($Courses,'Name'); ?>
	</div>
	
	<div class="row">
		<?php echo $form->labelEx($Courses,'Number'); ?>
		<?php echo $form->textField($Courses,'Number',array('size'=>60,'maxlength'=>128)); ?>
		<?php echo $form->error($Courses,'Number'); ?> 
	</div>
	
	<div class="row">
		<?php echo $form->labelEx($Courses,'University'); ?>
		<?php echo $form->textField($Courses,'University',array('size'=>60,'maxlength'=>128)); ?>
		<?php echo $form->error($Courses,'University'); ?> 
	</div>
	
	<div class="row">
		<?php echo $form->labelEx($Courses,'Credits'); ?>
		<?php echo $form->textField($Courses,'Credits',array('size'=>60,'maxlength'=>128)); ?>
		<?php echo $form->error($Courses,'Credits'); ?>
	</div>
	
	<div class="row">
		<?php echo $form->labelEx($Courses,'Level'); ?>
		<?php echo $form->textField($Courses,'Level',array('size'=>60,'maxlength'=>128)); ?>
		<?php echo $form->error($Courses,'Level'); ?>
	</div>

	<div class="row buttons">
	<input type = "submit" name = "action" value = "Save" />
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->