<?php
/* @var $this TempTablesController */
/* @var $model Tmp5 */
/* @var $form CActiveForm */
?>

<?php 

$con = Yii::app()->db;

// Get Headers for drop down menu
$query = "SELECT DISTINCT Header FROM tmp_5";
$command = $con->createCommand($query);
$dataReader = $command->queryAll();

$headers = "";
foreach($dataReader as $row)
{
	$headers .= "<option value='{$row['Header']}'>{$row['Header']}</option>";
}

$numAnswers = 0;

// Get PotentialAnswers
$query = "SELECT PotentialAnswer FROM tmp_5
WHERE Header = \"" . $tmp5->getAttribute('Header') . "\"
AND Part = \"" . $tmp5->getAttribute('Part') . "\"";
$command = $con->createCommand($query);
$dataReader = $command->queryAll();

$PotentialAnswers = "";
foreach($dataReader as $row)
{
	$PotentialAnswers[] = $row['PotentialAnswer'];
	$numAnswers++;
}

// Get PotentialAnswerText
$query = "SELECT PotentialAnswerText FROM tmp_5
WHERE Header = \"" . $tmp5->getAttribute('Header') . "\"
AND Part = \"" . $tmp5->getAttribute('Part') . "\"";
$command = $con->createCommand($query);
$dataReader = $command->queryAll();

$PotentialAnswerText = "";
foreach($dataReader as $row)
{
	$PotentialAnswerText[] = $row['PotentialAnswerText'];
}

// Get CorrectAnswers
$query = "SELECT CorrectAnswer FROM tmp_5
WHERE Header = \"" . $tmp5->getAttribute('Header') . "\"
AND Part = \"" . $tmp5->getAttribute('Part') . "\"";
$command = $con->createCommand($query);
$dataReader = $command->queryAll();

$CorrectAnswers = "";
foreach($dataReader as $row)
{
	$CorrectAnswers[] = $row['CorrectAnswer'];
}


?>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'tmp-form',
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($tmp5); ?>

	<div class="row">
		<?php echo $form->labelEx($tmp5,'Header'); ?>
		<?php echo $form->textField($tmp5,'Header',array('size'=>60,'maxlength'=>128)); ?>
		<?php echo $form->error($tmp5,'Header'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($tmp5,'Part'); ?>
		<?php echo $form->textField($tmp5,'Part',array('size'=>60,'maxlength'=>128)); ?>
		<?php echo $form->error($tmp5,'Part'); ?>
	</div>
	
	<div class="row">
		<?php for ($i = 0; $i < $numAnswers; $i++)
		{
			echo $form->labelEx($tmp5,'PotentialAnswer&emsp;&emsp;&emsp;&emsp;  Text'.str_repeat('&emsp;',15).'Correct Answer? (0=False, 1=True) ');
			echo "<input type = 'text' name = PotentialAnswers[] value='" . $PotentialAnswers[$i] . "' style=width:140px>";
			echo "<input type = 'text' name = PotentialAnswerText[] value='" . $PotentialAnswerText[$i] . "' style=width:200px>";
			echo "<input type = 'text' name = CorrectAnswer[] value='" . $CorrectAnswers[$i] . "' style=width:220px>";
			echo CHtml::button('Delete' , array('submit' => array('tempTables/DeletePotentialAnswer&Header=' . $tmp5->Header . "&Part=" . $tmp5->Part . '&PotentialAnswer=' . $PotentialAnswers[$i])));
			echo "<br>";
		}
		
		echo $form->labelEx($tmp5,'PotentialAnswer&emsp;&emsp;&emsp;&emsp;  Text'.str_repeat('&emsp;',15).'Correct Answer? (0=False, 1=True) ');
	
		echo "<input type = 'text' name = newPA style=width:140px>";
		echo "<input type = 'text' name = newPAT style=width:200px>";
		echo "<input type = 'text' name = newCA style=width:220px>";
		
		echo "<input type = 'submit' name = 'action' value = 'Add New' /> <br>";
		?>
	</div>
	

	<div class="row buttons">
	<input type = "submit" name = "action" value = "Previous" />
	<input type = "submit" name = "action" value = "Save" />
	<input type = "submit" name = "action" value = "Next" /> <br>
	<input type = "submit" name = "action" value = "Add Potential Answer" /><br>
	<b>Go to: </b> <select name = "Header">
		<?php echo $headers;?> </select> 
	Part:         <input type = "text"   name = "Part" />
	<input type = "submit" name = "action" value = "Go"/> <br>
	<input type = "submit" name = "action" value = "Edit Problems/Parts"/> <br>
	<input type = "submit" name = "action" value = "Review All" />
	
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->
