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

	<?php echo $form->errorSummary($Classes); ?>

	<div class="row">
		<?php echo $form->labelEx($Classes,'Course'); ?>
		<?php echo $form->textField($Classes,'Course',array('size'=>60,'maxlength'=>128)); ?>
		<?php echo $form->error($Classes,'Course'); ?>
	</div>
	
	<div class="row">
		<?php echo $form->labelEx($Classes,'Semester'); ?>
		<?php echo $form->textField($Classes,'Semester',array('size'=>60,'maxlength'=>128)); ?>
		<?php echo $form->error($Classes,'Semester'); ?> 
	</div>
	
	<div class="row">
		<?php echo $form->labelEx($Classes,'Year'); ?>
		<?php echo $form->textField($Classes,'Year',array('size'=>60,'maxlength'=>128)); ?>
		<?php echo $form->error($Classes,'Year'); ?> 
	</div>
	
	<div class="row">
		<?php echo $form->labelEx($Classes,'Section'); ?>
		<?php echo $form->textField($Classes,'Section',array('size'=>60,'maxlength'=>128)); ?>
		<?php echo $form->error($Classes,'Section'); ?>
	</div>

	<div class="row buttons">
	<input type = "submit" name = "action" value = "Save" />
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->