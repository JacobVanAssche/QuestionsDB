<?php
$this->breadcrumbs=array(
		'ViewEnrolledIn',
		$EnrolledIn->Student=>array('/EnrolledIn'),
);
?>

<h1>EnrolledIn</h1>

<?php 

$this->widget('zii.widgets.grid.CGridView', array(
		'id'=>'answers-grid',
		'dataProvider'=>$EnrolledIn->search(),
		'filter'=>$EnrolledIn,
		'columns'=>array(
				'Student',
				'Class',
				'Status',
				'StatusChange',
				array(
						'class'=>'CButtonColumn',
						'template'=>'{update}{delete}',
						'buttons'=>array
						(
								'update' => array
								(
										'label'=>'Update',
										'url'=>'Yii::app()->createUrl("allTables/UpdateEnrolledIn", array(
												"Student"=>$data->Student,
												"Class"=>$data->Class,
												))',
								),
								'delete' => array
								(
										'label'=>'Delete',		
										'url'=>'Yii::app()->createUrl("allTables/DeleteEnrolledIn", array(
												"Student"=>$data->Student,
												"Class"=>$data->Class,
												))',
								)
						),
				),
		),

));

echo CHtml::button('Create New Enrollment' , array('submit' => array('allTables/CreateEnrolledIn'))); 


?>