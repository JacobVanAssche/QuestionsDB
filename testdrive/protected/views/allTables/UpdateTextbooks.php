<?php
$this->breadcrumbs=array(
		'UpdateTextbooks',
);
?>
<h1>Update Textbook</h1>

<?php echo $this->renderPartial('Textbooks_Form', array('Textbooks'=>$Textbooks)); ?>