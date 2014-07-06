<?php
$this->breadcrumbs=array(
		'AssignmentInfo',
);

?>


<?php 

echo "<h1>" . $Title . "</h1>";
echo "<h2>" . $CourseName . "-";
echo 		  $Section . " ";
echo  "("	.	  $CourseNumber . ") </h2>";
echo "<h2>" . $Semester . " ";
echo 		  $Year . "</h2>";


$con = Yii::App()->db;

// Find ClassID
$query = "SELECT Aux_ClassID('" . $CourseName . "','" . $Section . "','" . $Semester . "','" . $Year . "');";
$command = $con->createCommand($query);
$classID = $command->queryScalar();

echo "ClassID: " .  $classID . "<br>";
echo "AssignmentID: " .  $AssignmentID . "<br>";

echo "<br><h2>Assignment Participation</h2>";
$this->widget('zii.widgets.grid.CGridView', array(
		'id'=>'participation-grid',
		'dataProvider'=>$AssignmentInfo->participation($AssignmentID, $classID),
		'columns'=>array(
				array(
						'name'=>'Students who completed assignment',
						'type'=>'raw',
						'value'=>'$data["TookExam"]'
					 ),
				array(
						'name'=>'Students who did not complete assignment',
						'type'=>'raw',
						'value'=>'$data["MissedExam"]'
				),
				array(
						'name'=>'Assignment Participation',
						'type'=>'raw',
						'value'=>'$data["Participation"] . "%"'
				),
		),

));

echo "<h2>Student Scores for Assignment</h2>";
echo $this->renderPartial('export_scores_to_csv');
$this->widget('zii.widgets.grid.CGridView', array(
		'id'=>'studentScores-grid',
		'dataProvider'=>$AssignmentInfo->studentScores($AssignmentID, $classID, $Title),
		//'filter'=>$AssignmentInfo,
		'columns'=>array(
				array(
						'name'=>'StudentID',
						'type'=>'raw',
						'value' => 'CHtml::link($data["StudentID"], Yii::app()->createUrl("queries/studentInfo", array(
						"StudentID"=>$data["StudentID"], 
						"LastName"=>$data["LastName"],
						"FirstName"=>$data["FirstName"],
						"Semester"=>"' . $Semester . '",
						"Year"=>"' . $Year . '",
						)))',
				),
				// LastName
				array(
				'name'=>'LastName',
				'type'=>'raw',
				'value' => 'CHtml::link($data["LastName"], Yii::app()->createUrl("queries/studentInfo", array(
						"StudentID"=>$data["StudentID"],
						"LastName"=>$data["LastName"],
						"FirstName"=>$data["FirstName"],
						"Semester"=>"' . $Semester . '",
						"Year"=>"' . $Year . '",
						)))',
				),
				// FirstName
				array(
				'name'=>'FirstName',
				'type'=>'raw',
				'value' => 'CHtml::link($data["FirstName"], Yii::app()->createUrl("queries/studentInfo", array(
						"StudentID"=>$data["StudentID"],
						"LastName"=>$data["LastName"],
						"FirstName"=>$data["FirstName"],
						"Semester"=>"' . $Semester . '",
						"Year"=>"' . $Year . '",
						)))',
				),
				'Version',
				'Score',
				'Total',
				'Percent',
		),

));

echo "<h2>Assignment Average</h2>";
$this->widget('zii.widgets.grid.CGridView', array(
		'id'=>'assignmentAverage-grid',
		'dataProvider'=>$AssignmentInfo->assignmentAverage($AssignmentID, $classID),
		//'filter'=>$AssignmentInfo,
		'columns'=>array(
				array(
						'name'=>'Average Score',
						'type'=>'raw',
						'value'=>'$data["ExamAverage"]'
				),
				array(
						'name'=>'Maximum Score',
						'type'=>'raw',
						'value'=>'$data["MaximumPoints"]'
				),
				array(
						'name'=>'Average Percentage',
						'type'=>'raw',
						'value'=>'$data["AssignmentAveragePercentage"]'
				),
		),

));

echo "<h2>Students who did not take assignment</h2>";
$this->widget('zii.widgets.grid.CGridView', array(
		'id'=>'studentsMissing-grid',
		'dataProvider'=>$AssignmentInfo->missingAssignment($AssignmentID, $classID),
		//'filter'=>$AssignmentInfo,
		'columns'=>array(
				'StudentID',
				'LastName',
				'FirstName',
				'Username',
				array(
						'name'=>'Email',
						'type'=>'raw',
						'value'=>'CHtml::link($data["Email"], "mailto:$data[Email]")'
				),
		),

));

echo "<h2>Questions on Assignment</h2>";
$this->widget('zii.widgets.grid.CGridView', array(
		'id'=>'questions-grid',
		'dataProvider'=>$AssignmentInfo->questionsAveragePercentage($AssignmentID, $classID),
		//'filter'=>$AssignmentInfo,
		'columns'=>array(
				'Header', 
				'QuestionAverage',
				'MaximumPoints',
				'AveragePercentage',
				
		),

));


echo "<h2>Problems on Assignment</h2>";
$this->widget('zii.widgets.grid.CGridView', array(
		'id'=>'problems-grid',
		'dataProvider'=>$AssignmentInfo->problemsAveragePercentage($AssignmentID, $classID),
		//'filter'=>$AssignmentInfo,
		'columns'=>array(
				'Problem',
				'ProblemAverage',
				'MaximumPoints',
				'ProblemAveragePercentage',

		),

));

echo "<h2>Multiple Choice Questions</h2>";
echo $this->renderPartial('export_multiple_choice_csv');
$this->widget('zii.widgets.grid.CGridView', array(
		'id'=>'questionsInfo-grid',
		'dataProvider'=>$AssignmentInfo->multipleChoice($AssignmentID),
		//'filter'=>$AssignmentInfo,
		'columns'=>array(
				'Header',
				'PotentialAnswer',
				'CorrectAnswer',
				'Percentage',

		),

));

?>
