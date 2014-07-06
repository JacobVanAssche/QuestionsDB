<?php
$this->breadcrumbs=array(
		'ViewAnswers',
		$Answers->Student=>array('/Answers'),
);
?>

<h1>Answers</h1>

<?php 

$this->widget('zii.widgets.grid.CGridView', array(
		'id'=>'answers-grid',
		'dataProvider'=>$Answers->search(),
		'filter'=>$Answers,
		'columns'=>array(
				'Student',
				'Assignment',
				'Question',
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
												"Student"=>$data->Student,
												"Assignment"=>$data->Assignment,
												"Question"=>$data->Question,
												"Part"=>$data->Part))',
								),
								'delete' => array
								(
										'label'=>'Delete',		
										'url'=>'Yii::app()->createUrl("allTables/DeleteAnswers", array(
												"Student"=>$data->Student,
												"Assignment"=>$data->Assignment,
												"Question"=>$data->Question,
												"Part"=>$data->Part))',
								)
						),
				),
		),

));