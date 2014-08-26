<?php 
	$this->pageTitle=Yii::app()->name; 
	$this->breadcrumbs=array(
		'Box-O-Matic',
	);
	$baseUrl = Yii::app()->baseUrl;
	//$this->page_heading = 'Box-O-Matic Admin';
?>
<?php echo CHtml::image($baseUrl.'/../themes/boxomatic/images/logo-lg.png','Box-o-Matic'); ?>