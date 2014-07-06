<?php
$this->breadcrumbs=array(
		'StudentAnswers',
);
?>

<?php 
$con = Yii::App()->db;

$query = "SELECT FirstName FROM Students WHERE StudentID ='" . $Student . "';";
$command = $con->createCommand($query);
$FirstName = $command->queryScalar();

$query = "SELECT LastName FROM Students WHERE StudentID ='" . $Student . "';";
$command = $con->createCommand($query);
$LastName = $command->queryScalar();

$query = "SELECT Title FROM Assignments WHERE AssignmentID ='" . $Assignment . "'";
$command = $con->createCommand($query);
$Title = $command->queryScalar();


?>

<h1>Student: <?php echo $FirstName . " " . $LastName; ?> </h1>
<h2>ID: <?php echo $Student; ?></h2>
<h2>Assignment: <?php echo $Title; ?></h2>

<?php 
$this->widget('zii.widgets.grid.CGridView', array(
		'id'=>'answers-grid1',
		'dataProvider'=>$StudentAnswers->answers($Student, $Assignment),
		//'filter'=>$Answers,
		'columns'=>array(
				'Header',
				'Part',
				'Answer',
				array(
						'class'=>'CButtonColumn',
						'template'=>'{update}{delete}',
						'buttons'=>array
						(
								'update' => array
								(
										'label'=>'Update',
										'url'=>'Yii::app()->createUrl("allTables/UpdateAnswers", array(
												"Student"=>"' . $Student . '",
												"Assignment"=>"' . $Assignment . '",
												"Question"=>$data["Question"],
												"Part"=>$data["Part"]))',
								),
								'delete' => array
								(
										'label'=>'Delete',		
										'url'=>'Yii::app()->createUrl("allTables/DeleteAnswers", array(
												"Student"=>"' . $Student . '",
												"Assignment"=>"' . $Assignment . '",
												"Question"=>$data["Question"],
												"Part"=>$data["Part"]))',
								)
						),
				),
		),

));

?>