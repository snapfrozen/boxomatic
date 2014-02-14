<p><?php echo CHtml::link('Update all', array('contenttype/updateSchema'), array('class' => 'btn btn-primary')); ?></p>
<div class="items">
<?php foreach ($data as $ct): $ct->checkForErrors(); ?>
	<div class="item <?php echo $ct->hasSchemaErrors() ? 'error' : 'ok' ?>">
		<h4><?php echo CHtml::value($ct,'name'); ?></h4>
		<?php if($ct->hasSchemaErrors()): ?>
			<?php foreach($ct->schemaErrors as $error): ?>
			<div class="alert alert-error">
				<?php echo $error; ?>
			</div>
			<?php endforeach; ?>
		<?php endif; ?>
		<?php if(!$ct->tableExists()) :
			echo CHtml::link('Create Table', 
				array('contentType/createTable', 'id'=>$ct->id), 
				array('class'=>'btn btn-success')); 
		endif;?>
		<?php if($ct->tableExists() && $ct->fieldsExist() !== true) :
			echo CHtml::link('Create Missing DB Fields', 
				array('contentType/createFields', 'id'=>$ct->id), 
				array('class'=>'btn btn-success')); 
		endif;?>
	</div>
<?php endforeach; ?>
</div>

