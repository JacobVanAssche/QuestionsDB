<?php
$this->breadcrumbs=array(
		'ViewClasses',
		$Classes->ClassID=>array('/Classes'),
);
?>

<h1>Classes</h1>

<?php 

$this->widget('zii.widgets.grid.CGridView', array(
		'id'=>'answers-grid',
		'dataProvider'=>$Classes->search(),
		'filter'=>$Classes,
		'columns'=>array(
				'ClassID',
				'Course',
				'Semester',
				'Year',
				'Section',
				array(
						'class'=>'CButtonColumn',
						'template'=>'{update}{delete}',
						'buttons'=>array
						(
								'update' => array
								(
										'label'=>'Update',
										'url'=>'Yii::app()->createUrl("allTables/UpdateClasses", array(
												"ClassID"=>$data->ClassID,
												))',
								),
								'delete' => array
								(
										'label'=>'Delete',		
										'url'=>'Yii::app()->createUrl("allTables/DeleteClasses", array(
												"ClassID"=>$data->ClassID,
												))',
								)
						),
				),
		),

));

echo CHtml::button('Create Class' , array('submit' => array('allTables/CreateClasses')));
echo "<br><br>";
echo CHtml::button('Create Course' , array('submit' => array('allTables/CreateCourses')));
echo CHtml::button('View Courses' , array('submit' => array('allTables/viewCourses')));
echo "<br><br>";
echo CHtml::button('Create Semester' , array('submit' => array('allTables/CreateSemesters')));
echo CHtml::button('View Semesters' , array('submit' => array('allTables/viewSemesters')));
