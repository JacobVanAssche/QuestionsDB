<?php
$this->breadcrumbs=array(
		'Enrollment',
);
// $Semester
// $Year
?>


<h1>Enrollment</h1>

<?php 

echo CHtml::button('Create New Enrollment' , array('submit' => array('allTables/createEnrolledIn')));

$this->widget('zii.widgets.grid.CGridView', array(
		'id'=>'enrollment-grid',
		'dataProvider'=>$model->getSqlDataProvider($Semester, $Year),
		'filter'=>$model,
		'columns'=>array(
				array(
						'name'=>'StudentID',
						'type'=>'raw',
						'value' => 'CHtml::link($data["StudentID"], Yii::app()->createUrl("queries/studentInfo", array(
						"StudentID"=>$data["StudentID"], 
						"LastName"=>$data["LastName"],
						"FirstName"=>$data["FirstName"],
						)))',
				),
				'LastName',
				'FirstName',
				// CourseName
				array(
						'name'=>'CourseName',
						'type'=>'raw',
						'value' => 'CHtml::link($data["CourseName"], 
						Yii::app()->createUrl("queries/enrolledInClass", array(
						"CourseName"=>$data["CourseName"], 
						"CourseNumber"=>$data["CourseNumber"],
						"Semester"=>$data["Semester"],
						"Year"=>$data["Year"],
						"Section"=>$data["Section"]
						)))',
						'htmlOptions'=>array('width'=>'140px'),
				),
				'CourseNumber',
				'Section',
				'Semester',
				'Year',
				'Status',
			),
		
));
