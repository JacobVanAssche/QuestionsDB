<?php
$this->breadcrumbs=array(
		'Questions',
);
?>

<?php 

echo "<h1>" . $Title . "</h1>";
echo "<h2>" . $CourseName . "-";
echo 		  $Section . " ";
echo  "("	.	  $CourseNumber . ") </h2>";
echo "<h2>" . $Semester . " ";
echo 		  $Year . "</h2>";

echo "<h2>Questions</h2>";

$this->widget('zii.widgets.grid.CGridView', array(
		'id'=>'questionsInfo-grid',
		'dataProvider'=>$QuestionsInfo->questionAverages($AssignmentID),
		//'filter'=>$QuestionsInfo,
		'columns'=>array(
				'Header',
				'QuestionText',
				'QuestionAverage',
				'MaxPoints',
				'AveragePercentage',
		),

));

echo "<h2>Multiple Choice Questions</h2>";
$this->widget('zii.widgets.grid.CGridView', array(
		'id'=>'questionsInfo-grid',
		'dataProvider'=>$QuestionsInfo->multipleChoice($AssignmentID),
		//'filter'=>$QuestionsInfo,
		'columns'=>array(
				'Header',
				'PotentialAnswer',
				'CorrectAnswer',
				'Percentage',
				
		),

));

?>