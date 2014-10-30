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

	<?php echo $form->errorSummary($EnrolledIn); ?>

	<div class="row">
		<?php echo $form->labelEx($EnrolledIn,'Student'); ?>
		<?php echo $form->textField($EnrolledIn,'Student',array('size'=>60,'maxlength'=>128)); ?>
		<?php echo $form->error($EnrolledIn,'Student'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($EnrolledIn,'Class'); ?>
		<?php echo $form->textField($EnrolledIn,'Class',array('size'=>60,'maxlength'=>128)); ?>
		<?php echo $form->error($EnrolledIn,'Class'); ?>
	</div>
	
	<div class="row">
		<?php echo $form->labelEx($EnrolledIn,'Status'); ?>
		<?php echo $form->textField($EnrolledIn,'Status',array('size'=>60,'maxlength'=>128)); ?>
		<?php echo $form->error($EnrolledIn,'Status'); ?> 
	</div>
	
	<div class="row">
		<?php echo $form->labelEx($EnrolledIn,'StatusChange'); ?>
		<?php echo $form->textField($EnrolledIn,'StatusChange',array('size'=>60,'maxlength'=>128)); ?>
		<?php echo $form->error($EnrolledIn,'StatusChange'); ?> 
	</div>

	<div class="row buttons">
	<input type = "submit" name = "action" value = "Save" />
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->