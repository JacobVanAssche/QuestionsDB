<?php
$this->breadcrumbs=array(
		'ViewTextbooks',
);
?>

<h1>Textbooks</h1>

<?php 

$this->widget('zii.widgets.grid.CGridView', array(
		'id'=>'answers-grid',
		'dataProvider'=>$Textbooks->search(),
		'filter'=>$Textbooks,
		'columns'=>array(
				'ISBN13',
				'Title',
				'Author',
				'Edition',
				
				array(
						'class'=>'CButtonColumn',
						'template'=>'{update}{delete}',
						'buttons'=>array
						(
								'update' => array
								(
										'label'=>'Update',
										'url'=>'Yii::app()->createUrl("allTables/UpdateTextbooks", array(
												"ISBN13"=>$data->ISBN13,))',
								),
								'delete' => array
								(
										'label'=>'Delete',		
										'url'=>'Yii::app()->createUrl("allTables/DeleteTextbook", array(
												"ISBN13"=>$data->ISBN13,))',
								)
						),
				),
		),

));

echo CHtml::button('Create Textbook' , array('submit' => array('allTables/CreateTextbook')));
