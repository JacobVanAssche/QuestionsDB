<?php
$this->breadcrumbs=array(
		'ViewSemesters',
		$Semesters->Semester=>array('/Semesters'),
);
?>

<h1>Semesters</h1>

<?php 

$this->widget('zii.widgets.grid.CGridView', array(
		'id'=>'semesters-grid',
		'dataProvider'=>$Semesters->search(),
		'filter'=>$Semesters,
		'columns'=>array(
				'Semester',
				'Year',
				'StartDate',
				'EndDate',
				array(
						'class'=>'CButtonColumn',
						'template'=>'{update}{delete}',
						'buttons'=>array
						(
								'update' => array
								(
										'label'=>'Update',
										'url'=>'Yii::app()->createUrl("allTables/UpdateSemesters", array(
												"Semester"=>$data->Semester,
												"Year"=>$data->Year,
										))',
								),
								'delete' => array
								(
										'label'=>'Delete',		
										'url'=>'Yii::app()->createUrl("allTables/DeleteSemesters", array(
												"Semester"=>$data->Semester,
												"Year"=>$data->Year,
										))',
								)
						),
				),
		),

));


echo CHtml::button('Create Semester' , array('submit' => array('allTables/CreateSemesters')));
echo "<br><br>";
echo CHtml::button('Create Class' , array('submit' => array('allTables/CreateClasses')));
echo CHtml::button('View Classes' , array('submit' => array('allTables/viewClasses'))); 
echo "<br><br>";
echo CHtml::button('Create Course' , array('submit' => array('allTables/CreateCourses')));
echo CHtml::button('View Courses' , array('submit' => array('allTables/viewCourses')));

?>