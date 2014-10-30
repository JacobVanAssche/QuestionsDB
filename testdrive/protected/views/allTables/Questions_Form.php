
<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'Answers-form',
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($Questions); ?>

	<div class="row">
		<?php echo $form->labelEx($Questions,'QuestionID'); ?>
		<?php echo $form->textField($Questions,'QuestionID',array('size'=>60,'maxlength'=>128)); ?>
		<?php echo $form->error($Questions,'QuestionID'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($Questions,'QuestionText'); ?>
		<?php echo $form->textField($Questions,'QuestionText',array('size'=>60,'maxlength'=>128)); ?>
		<?php echo $form->error($Questions,'QuestionText'); ?>
	</div>
	
	<div class="row">
		<?php echo $form->labelEx($Questions,'SolutionText'); ?>
		<?php echo $form->textField($Questions,'SolutionText',array('size'=>60,'maxlength'=>128)); ?>
		<?php echo $form->error($Questions,'SolutionText'); ?> 
	</div>
	
	<div class="row">
		<?php echo $form->labelEx($Questions,'Comments'); ?>
		<?php echo $form->textField($Questions,'Comments',array('size'=>60,'maxlength'=>128)); ?>
		<?php echo $form->error($Questions,'Comments'); ?> 
	</div>
	
	<div class="row">
		<?php echo $form->labelEx($Questions,'Timestamp'); ?>
		<?php echo $form->textField($Questions,'Timestamp',array('size'=>60,'maxlength'=>128)); ?>
		<?php echo $form->error($Questions,'Timestamp'); ?>
	</div>

	<div class="row buttons">
	<input type = "submit" name = "action" value = "Save" />
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->