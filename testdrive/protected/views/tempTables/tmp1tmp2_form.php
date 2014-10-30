<?php
/* @var $this TempTablesController */
/* @var $model Tmp1 */
/* @var $form CActiveForm */
?>
<?php 
$con = Yii::app()->db;

// Get Headers for drop down menu
$query = "SELECT Header FROM tmp_1";
$command = $con->createCommand($query);
$dataReader = $command->queryAll();

$headers = "";
foreach($dataReader as $row)
{
	$headers .= "<option value='{$row['Header']}'>{$row['Header']}</option>";
}


// Get Parts
$query = "SELECT Part FROM tmp_2
WHERE Header = \"" . $tmp1->getAttribute('Header') . "\"";
$command = $con->createCommand($query);
$dataReader = $command->queryAll();

$parts = "";
foreach($dataReader as $row)
{
	$parts[] = $row['Part'];
}

// Get OutOf
$query = "SELECT OutOf FROM tmp_2
WHERE Header = \"" . $tmp1->getAttribute('Header') . "\"";
$command = $con->createCommand($query);
$dataReader = $command->queryAll();

$outOf = "";
foreach($dataReader as $row)
{
	$outOf[] = $row['OutOf'];
}

// Get total number of parts
$query = "SELECT COUNT(Part) FROM tmp_2
WHERE Header = \"" . $tmp1->getAttribute('Header') . "\"";
$command = $con->createCommand($query);
$numParts = $command->queryScalar();
?>


<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'tmp-form',
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($tmp1); ?>
	<?php 
	if (isset($data->tmp2))
	{
		echo $form->errorSummary($tmp2); 
	}
	?>

	<div class="row">
		<?php echo $form->labelEx($tmp1,'Header'); ?>
		<?php echo $form->textField($tmp1,'Header',array('size'=>50,'maxlength'=>128)); ?>
		<?php echo $form->error($tmp1,'Header'); ?>
	</div>

	<?php echo $form->labelEx($tmp1,'QuestionText'); ?>
	<?php echo $form->textArea($tmp1, 'QuestionText', array('style' => 'width:500px; height:200px; resize:none; word-wrap:break-word;')); ?>
		
	<?php echo $form->error($tmp1,'QuestionText'); ?>
	
	
	<div class="row">
		<?php  ?> 
		<?php for ($i = 0; $i < $numParts; $i++)
		{
			echo $form->labelEx($tmp2,'Part' .  str_repeat('&emsp;',7). 'OutOf');
			echo "<input type = 'text' name = Parts[] value='" . $parts[$i] . "' style=width:100px>";
			//echo $form->labelEx($tmp2,'OutOf:');
			echo "<input type = 'text' name = OutOfs[] value='" . $outOf[$i] . "' style=width:100px>";
			//echo CHtml::button('Add Part' , array('submit' => array('tempTables/AddPart&Header=' . $tmp1->Header)));
			if ($numParts > 1)
			{
				echo CHtml::button('Delete Part' , array('submit' => array('tempTables/DeletePart&Header=' . $tmp2->Header . "&Part=" . $parts[$i])));
			}
			else
				echo CHtml::button('Delete Problem/Part' , array('submit' => array('tempTables/DeletePart&Header=' . $tmp2->Header . "&Part=" . $parts[$i])));
			
			echo "<br>";
		}?>
		<?php 
		echo $form->labelEx($tmp2,'Part' .  str_repeat('&emsp;',7). 'OutOf');
		
		echo "<input type = 'text' name = newPart style=width:100px>";
		echo "<input type = 'text' name = newOutOf style=width:100px>";
		
		echo "<input type = 'submit' name = 'action' value = 'Add New' /> <br>";
		?>
		
		<?php echo $form->error($tmp2,'Part'); ?> 
		<?php echo $form->error($tmp2,'OutOf'); ?>
	</div>

	<div class="row buttons">
	<input type = "submit" name = "action" value = "Previous" />
	<input type = "submit" name = "action" value = "Save" />
	<input type = "submit" name = "action" value = "Next" /> <br>
	<input type = "submit" name = "action" value = "Add Problem" /> <br>
	<b>Go to: </b> <select name = "GoTo">
		<?php echo $headers;?> </select>
	<input type = "submit" name = "action" value = "Go"/> <br>
	<input type = "submit" name = "action" value = "Edit Potential Answers"/> <br>
	<input type = "submit" name = "action" value = "Review All" />
	
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->
