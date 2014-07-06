<?php
$this->breadcrumbs=array(
		'ViewAssignments',
		$Assignments->AssignmentID=>array('/Assignments'),
);
?>

<h1>Assignments</h1>

<?php 

$this->widget('zii.widgets.grid.CGridView', array(
		'id'=>'answers-grid',
		'dataProvider'=>$Assignments->search(),
		'filter'=>$Assignments,
		'columns'=>array(
				'AssignmentID',
				'Class',
				'Title',
				'Version',
				'TexFile',
				'Date',
				'Comments',
				array(
						'class'=>'CButtonColumn',
						'template'=>'{update}{delete}',
						'buttons'=>array
						(
								'update' => array
								(
										'label'=>'Update',
										'url'=>'Yii::app()->createUrl("allTables/UpdateAssignments", array(
												"AssignmentID"=>$data->AssignmentID,
												))',
								),
								'delete' => array
								(
										'label'=>'Delete',		
										'url'=>'Yii::app()->createUrl("allTables/DeleteAssignment", array(
												"AssignmentID"=>$data->AssignmentID,
												))',
								)
						),
				),
		),

));

echo CHtml::button('Create Assignment' , array('submit' => array('allTables/createAssignment')));
?>