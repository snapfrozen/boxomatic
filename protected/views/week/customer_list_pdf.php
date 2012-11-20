<table id="customerList" cellpadding="2" cellspacing="0" border="0">
	<tbody>
		<?php foreach($CustBoxes as $CustBox): ?>
		<tr>
			<td rowspan="2" class="bottom alignCentre">
				<?php echo CHtml::image($CustBox->getQrCode(), 'QR Image', array('width'=>90,'height'=>90)); ?>
			</td>
			<td class="name"><strong><?php echo $CustBox->Box->BoxSize->box_size_name ?>:</strong> <?php echo $CustBox->Customer->User->first_name ?> <?php echo $CustBox->Customer->User->last_name ?></td>
			<td class="phone">
				<?php if(!empty($CustBox->Customer->User->user_phone)): ?><strong>T:</strong><?php echo $CustBox->Customer->User->user_phone ?><?php endif; ?>
				<?php if(!empty($CustBox->Customer->User->user_mobile)): ?><strong>M:</strong><?php echo $CustBox->Customer->User->user_mobile ?><?php endif; ?>
			</td>
		</tr>
		<tr>
			<td class="bottom" colspan="2">
				<?php echo $CustBox->delivery_location ?><br />
				<?php echo $CustBox->delivery_address ?>
			</td>
		</tr>
		<?php endforeach; ?>
	</tbody>
</table>