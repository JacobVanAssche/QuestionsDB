<?php
/* @var $this SiteController */

$this->pageTitle=Yii::app()->name . ' - About';
$this->breadcrumbs=array(
	'About',
);
?>
<h1>About</h1>

<p align = justify><?php echo "
		A way to manage classes, assignments, and student information was designed and implemented 
	using a database and the Yii Framework for the front-end. The front-end incorporates the database 
	and allows for an easy way to view, edit, and create new information about classes, students, or 
	assignments. When creating an assignment, you can either manually add questions to an assignment, 
	or you can you import an assignment from a TeX file. A lot of professors use TeX to create their 
	assignments, so this feature makes it very easy to import assignments. Once you have an assignment
	available, you can now import student's scores. The front-end also makes this process easy; 
	it will read a CSV file and grab the scores for each problem. You can view various statistics 
	about an assignment, such as average percentage of assignment/questions, participation, and 
	how many people picked the answer 'A'. The combination of the database and the front end allows 
	for practical use for professors to help manage their classes, assignments, and students." ?></p>
