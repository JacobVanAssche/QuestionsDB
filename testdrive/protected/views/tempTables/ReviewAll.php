<?php
$this->breadcrumbs=array(
		'ReviewAll',
		$tmp1->Header=>array('/Tmp1'),
		$tmp2->Header=>array('/Tmp2'),
		$tmp5->Header=>array('/Tmp5'),
		$tmp6->Title=>array('/Tmp6'),
);
?>

<h1>Review All</h1>
<br><br>

<center><h2>Exam Information</h2></center>
<?php 

// GRID FOR TMP_6 TABLE
$this->widget('zii.widgets.grid.CGridView', array(
		'id'=>'tmp-grid6',
		'dataProvider'=>$tmp6->search(),
		'columns'=>array(
				'Title',
				'CourseName',
				'Section',
				'Semester',
				'Year',
				'Date',
				array(
						'class'=>'CButtonColumn',
						'template'=>'{update}',
						'buttons'=>array
						(
								'update' => array
								(
										'label'=>'Update',
										'url'=>'Yii::app()->createUrl("tempTables/updateTmp6", array("Title"=>$data->Title))',
								),
						),
				),
		),

));
?>

<center><h2>Problems</h2></center>

<?php
// GRID FOR TMP_1
$this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'tmp1-grid',
	'dataProvider'=>$tmp1->search(),
	'filter'=>$tmp1,
	'columns'=>array(
	
		'Header',
		'QuestionText',

		array(
			'class'=>'CButtonColumn',
			'template'=>'{update}{delete}',
			'buttons'=>array
			(
					'update' => array
					(
							'label'=>'Update',
							'url'=>'Yii::app()->createUrl("tempTables/updateTmp1Tmp2", array("Header"=>$data->Header))',
					),
					'delete' => array
					(
							'label'=>'Delete',
							'url'=>'Yii::app()->createUrl("tempTables/DeleteProblem", array("Header"=>$data->Header))',
					),
			),
		),
	),

)); 

echo CHtml::button('Add Problem' , array('submit' => array('tempTables/AddProblem')));
?>
<br><br>
<center><h2>Parts</h2></center>
<?php 
// GRID FOR TMP_2 TABLE
$this->widget('zii.widgets.grid.CGridView', array(
		'id'=>'tmp2-grid',
		'dataProvider'=>$tmp2->search(),
		'filter'=>$tmp2,
		'columns'=>array(

				'Header',
				'Part',
				'OutOf',

				array(
						'class'=>'CButtonColumn',
						'template'=>'{update}{delete}',
						'buttons'=>array
						(
								'update' => array
								(
										'label'=>'Update',
										'url'=>'Yii::app()->createUrl("tempTables/updateTmp1Tmp2", array("Header"=>$data->Header))',
								),
								'delete' => array
								(
										'label'=>'Delete',
										'url'=>'Yii::app()->createUrl("tempTables/DeletePart", array("Header"=>$data->Header, "Part"=>$data->Part))',
								),
						),
				),
		),

));

?>
<br><br>
<center><h2>Potential Answers</h2></center>
<?php 
// GRID FOR TMP_5 TABLE
$this->widget('zii.widgets.grid.CGridView', array(
		'id'=>'tmp-grid5',
		'dataProvider'=>$tmp5->search(),
		'filter'=>$tmp5,
		'columns'=>array(
				'Header',
				'Part',
				'PotentialAnswer',
				'PotentialAnswerText',
				'CorrectAnswer',
				array(
						'class'=>'CButtonColumn',
						'template'=>'{update}{delete}',
						'buttons'=>array
						(
								'update' => array
								(
										'label'=>'Update',
										//'imageUrl'=>Yii::app()->request->baseUrl.'/images/email.png',
										'url'=>'Yii::app()->createUrl("tempTables/updateTmp5", array("Header"=>$data->Header, "Part"=>$data->Part))',
								),
								'delete' => array
								(
										'label'=>'Delete',
										'url'=>'Yii::app()->createUrl("tempTables/DeletePotentialAnswer", array("Header"=>$data->Header, "Part"=>$data->Part, "PotentialAnswer"=>$data->PotentialAnswer))',
								),
						),
				),
		),

));

echo CHtml::button('Add Potential Answer' , array('submit' => array('tempTables/NewPotentialAnswer')));
?>
<br><br>
<?php 
echo CHtml::button('Finalize' , array('submit' => array('tempTables/Finalize')));

?>