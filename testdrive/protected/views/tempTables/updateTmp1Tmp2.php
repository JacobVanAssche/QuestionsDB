<?php
/* @var $this TempTablesController */

$this->breadcrumbs=array(
	'Temp Tables'=>array('/tempTables'),
	'updateTmp1Tmp2',
$tmp1->Header=>array('/Tmp1'),
$tmp2->Header=>array('/Tmp2'),
);

?>

<h1>Update <?php echo $tmp1->Header; ?></h1>

<?php echo $this->renderPartial('tmp1tmp2_form', array('tmp1'=>$tmp1, 'tmp2'=>$tmp2)); ?>





