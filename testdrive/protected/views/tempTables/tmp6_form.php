<?php
/* @var $this TempTablesController */
/* @var $tmp6 Tmp6 */
/* @var $form CActiveForm */
?>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'tmp-form',
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($tmp6); ?>

	<div class="row">
		<?php echo $form->labelEx($tmp6,'Title'); ?>
		<?php echo $form->textField($tmp6,'Title',array('size'=>60,'maxlength'=>128)); ?>
		<?php echo $form->error($tmp6,'Title'); ?>
	</div>
	
	<div class="row">
		<?php echo $form->labelEx($tmp6,'Version'); ?>
		<?php echo $form->textField($tmp6,'Version',array('size'=>60,'maxlength'=>128)); ?>
		<?php echo $form->error($tmp6,'Version'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($tmp6,'CourseName'); ?>
		<?php echo $form->textField($tmp6,'CourseName',array('size'=>60,'maxlength'=>128)); ?>
		<?php echo $form->error($tmp6,'CourseName'); ?>
	</div>
	
	<div class="row">
		<?php echo $form->labelEx($tmp6,'Section'); ?>
		<?php echo $form->textField($tmp6,'Section',array('size'=>60,'maxlength'=>128)); ?>
		<?php echo $form->error($tmp6,'Section'); ?>
	</div>
	
	<div class="row">
		<?php echo $form->labelEx($tmp6,'Semester'); ?>
		<?php echo $form->textField($tmp6,'Semester',array('size'=>60,'maxlength'=>128)); ?>
		<?php echo $form->error($tmp6,'Semester'); ?>
	</div>
	
	<div class="row">
		<?php echo $form->labelEx($tmp6,'Year'); ?>
		<?php echo $form->textField($tmp6,'Year',array('size'=>60,'maxlength'=>128)); ?>
		<?php echo $form->error($tmp6,'Year'); ?>
	</div>
	
	<div class="row">
		<?php echo $form->labelEx($tmp6,'Date'); ?>
		<?php echo $form->textField($tmp6,'Date',array('size'=>60,'maxlength'=>128)); ?>
		<?php echo $form->error($tmp6,'Date'); ?>
	</div>
	

	<div class="row buttons">
	<input type = "submit" name = "action" value = "Save" />
	<input type = "submit" name = "action" value = "Next" /> <br>
	<input type = "submit" name = "action" value = "Review All" />
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->