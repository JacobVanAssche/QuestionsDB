<?php
/* @var $this TempTablesController */
/* @var $model Tmp1 */
/* @var $form CActiveForm */
?>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'tmp-form',
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($tmp1); ?>

	<div class="row">
		<?php echo $form->labelEx($tmp1,'Header'); ?>
		<?php echo $form->textField($tmp1,'Header',array('size'=>60,'maxlength'=>128)); ?>
		<?php echo $form->error($tmp1,'Header'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($tmp1,'QuestionText'); ?>
		<?php echo $form->textArea($tmp1, 'QuestionText', array('style' => 'width:400px; height:100px; resize:none; word-wrap:break-word;')); ?>
		<?php echo $form->error($tmp1,'QuestionText'); ?>
	</div>
	
	<div class="row">
		<?php echo $form->labelEx($tmp2,'Part'); ?>
		<?php echo $form->textField($tmp2,'Part',array('size'=>60,'maxlength'=>128)); ?>
		<?php echo $form->error($tmp2,'Part'); ?> 
	</div>
	
	<div class="row">
		<?php echo $form->labelEx($tmp2,'OutOf'); ?>
		<?php echo $form->textField($tmp2,'OutOf',array('size'=>60,'maxlength'=>128)); ?>
		<?php echo $form->error($tmp2,'OutOf'); ?>
	</div>

	<div class="row buttons">
	<input type = "submit" name = "action" value = "Add" /><br>
	<input type = "submit" name = "action" value = "Edit Problems and Parts" /><br>
	<input type = "submit" name = "action" value = "Edit Potential Answers" /><br>
	<input type = "submit" name = "action" value = "Review All" />
	
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->