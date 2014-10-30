<?php
$this->breadcrumbs=array(
		'ViewCourses',
		$Courses->CourseID=>array('/Courses'),
);
?>

<h1>Courses</h1>

<?php 

$this->widget('zii.widgets.grid.CGridView', array(
		'id'=>'answers-grid',
		'dataProvider'=>$Courses->search(),
		'filter'=>$Courses,
		'columns'=>array(
				'CourseID',
				'Name',
				'Number',
				'University',
				'Credits',
				'Level',
				array(
						'class'=>'CButtonColumn',
						'template'=>'{update}{delete}',
						'buttons'=>array
						(
								'update' => array
								(
										'label'=>'Update',
										'url'=>'Yii::app()->createUrl("allTables/UpdateCourses", array(
												"CourseID"=>$data->CourseID,
												))',
								),
								'delete' => array
								(
										'label'=>'Delete',		
										'url'=>'Yii::app()->createUrl("allTables/DeleteCourses", array(
												"CourseID"=>$data->CourseID,
												))',
								)
						),
				),
		),

));


echo CHtml::button('Create Course' , array('submit' => array('allTables/CreateCourses')));
echo "<br><br>";
echo CHtml::button('Create Class' , array('submit' => array('allTables/CreateClasses')));
echo CHtml::button('View Classes' , array('submit' => array('allTables/viewClasses')));
echo "<br><br>";
echo CHtml::button('Create Semester' , array('submit' => array('allTables/CreateSemesters')));
echo CHtml::button('View Semesters' , array('submit' => array('allTables/viewSemesters')));

?>
