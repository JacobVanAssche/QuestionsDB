<?php
$this->breadcrumbs=array(
		'ViewQuestions',
		$Questions->QuestionID=>array('/Questions'),
);
?>

<h1>Questions</h1>

<?php 

$this->widget('zii.widgets.grid.CGridView', array(
		'id'=>'Questions-grid',
		'dataProvider'=>$Questions->search(),
		'filter'=>$Questions,
		'columns'=>array(
				'QuestionID',
				'QuestionText',
				'SolutionText',
				'Comments',
				'Timestamp',
				array(
						'class'=>'CButtonColumn',
						'template'=>'{update}{delete}',
						'buttons'=>array
						(
								'update' => array
								(
										'label'=>'Update',
										'url'=>'Yii::app()->createUrl("allTables/UpdateQuestions", array(
												"QuestionID"=>$data->QuestionID,
												))',
								),
								'delete' => array
								(
										'label'=>'Delete',		
										'url'=>'Yii::app()->createUrl("allTables/DeleteQuestions", array(
												"QuestionID"=>$data->QuestionID,
												))',
								)
						),
				),
		),

));

echo CHtml::button('Create Question' , array('submit' => array('allTables/createQuestion')));
?>