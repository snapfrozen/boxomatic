<h2>Customer Boxes</h2>
<table id="customerList" cellpadding="0" cellspacing="0">
	<tbody>
		<?php foreach($CustBoxes as $CustBox): ?>
		<tr>
			<td><?php echo CHtml::image($CustBox->getQrCode(), 'QR Image', array('width'=>90,'height'=>90)); ?></td>
			<td><?php echo $CustBox->Box->BoxSize->box_size_name ?></td>
			<td><?php echo $CustBox->Customer->User->first_name ?></td>
			<td><?php echo $CustBox->Customer->User->last_name ?></td>
			<td><?php echo $CustBox->Customer->User->user_phone ?></td>
			<td><?php echo $CustBox->Customer->User->user_mobile ?></td>
			<td><?php echo $CustBox->delivery_location ?></td>
			<td><?php echo $CustBox->delivery_address ?></td>
		</tr>
		<?php endforeach; ?>
	</tbody>
</table>