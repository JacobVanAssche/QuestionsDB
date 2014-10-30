
<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'Textbooks-form',
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($Textbooks); ?>

	<div class="row">
		<?php echo $form->labelEx($Textbooks,'ISBN13'); ?>
		<?php echo $form->textField($Textbooks,'ISBN13',array('size'=>60,'maxlength'=>128)); ?>
		<?php echo $form->error($Textbooks,'ISBN13'); ?>
	</div>
	
	<div class="row">
		<?php echo $form->labelEx($Textbooks,'Title'); ?>
		<?php echo $form->textField($Textbooks,'Title',array('size'=>60,'maxlength'=>128)); ?>
		<?php echo $form->error($Textbooks,'Title'); ?> 
	</div>
	
	<div class="row">
		<?php echo $form->labelEx($Textbooks,'Author'); ?>
		<?php echo $form->textField($Textbooks,'Author',array('size'=>60,'maxlength'=>128)); ?>
		<?php echo $form->error($Textbooks,'Author'); ?> 
	</div>
	
	<div class="row">
		<?php echo $form->labelEx($Textbooks,'Edition'); ?>
		<?php echo $form->textField($Textbooks,'Edition',array('size'=>60,'maxlength'=>128)); ?>
		<?php echo $form->error($Textbooks,'Edition'); ?>
	</div>
	

	<div class="row buttons">
	<input type = "submit" name = "action" value = "Save" />
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->