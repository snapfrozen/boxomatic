<?php /* @var $this Controller */ 

?>
<?php $this->beginContent('/layouts/main'); ?>
<div class="row">
	<div id="content" class="col-md-9 clearfix">
		<?php echo $content; ?>
	</div><!-- content -->
	<div id="sidebar" class="col-md-3">
		<?php
		if(isset($this->operations)):
			$this->beginWidget('zii.widgets.CPortlet', array(
				'title'=>'Operations',
				'titleCssClass' => 'panel-title',
				'decorationCssClass' => 'panel-heading',
				'htmlOptions'=>array('class'=>'panel panel-success')
			));
			$this->widget('zii.widgets.CMenu', array(
				'items'=>$this->operations,
				'htmlOptions'=>array('class'=>'nav'),
			));
			$this->endWidget();
		endif;
		?>
		<?php
			$this->beginWidget('zii.widgets.CPortlet', array(
				'title'=>'Menu',
				'titleCssClass' => 'panel-title',
				'decorationCssClass' => 'panel-heading',
				'htmlOptions'=>array('class'=>'panel panel-info')
			));
			$this->widget('zii.widgets.CMenu', array(
				'items'=>$this->getMenuArray(),
				'htmlOptions'=>array('class'=>'nav'),
			));
			$this->endWidget();
		?>
	</div><!-- sidebar -->
</div>
<?php $this->endContent(); ?>