<div class="panel">
	<h3><?php echo CHtml::link(SnapFormat::date($data->DeliveryDate->date),array('/shop/default/index','date'=>$data->delivery_date_id)) ?></h3>
	<ul class="no-bullet">
		<li>
			Order Total:
			<?php echo SnapFormat::currency($data->User->totalByDeliveryDate($data->delivery_date_id)) ?>
		</li>
		<li>
			<?php echo $data->getAttributeLabel('status'); ?>:
			<?php echo CHtml::encode($data->status_text); ?>
		</li>
	</ul>
</div>
