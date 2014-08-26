<table id="customerList" cellpadding="2" cellspacing="0" border="0">
	<tbody>
               <tr height="143">
                <?php $i = 0; ?>
		<?php foreach($CustBoxes as $CustBox): ?>
                <?php if($i % 2 == 0 && $i > 0) : ?>
                </tr>
                <tr height="143">
                <?php endif; ?>
                 
                <td height="143">
                
                <div style="height: 200px; display:block;">
                <table>
		<tr>
			<td valign="top" rowspan="2">
 
				<br /><?php echo CHtml::image($CustBox->getQrCode(), 'QR Image', array('width'=>90,'height'=>90)); ?>
			</td>
			<td class="name"><br /><strong><?php echo $CustBox->Box->BoxSize->box_size_name ?>:</strong> <?php echo $CustBox->User->first_name ?> <?php echo $CustBox->User->last_name ?></td>
			<!-- <td class="phone">
				<?php if(!empty($CustBox->User->user_phone)): ?><strong>T:</strong><?php echo $CustBox->User->user_phone ?><?php endif; ?>
				<?php if(!empty($CustBox->User->user_mobile)): ?><strong>M:</strong><?php echo $CustBox->User->user_mobile ?><?php endif; ?>
			</td> -->
		</tr>
		<tr>
			<td class="bottom" colspan="2">
				<?php echo $CustBox->delivery_location ?><br /><br />
				<?php echo $CustBox->delivery_address ?>
			</td>
		</tr>
                </table>
                </div>
                </td>
               
                <?php $i++; ?>
		<?php endforeach; ?>
	</tbody>
</table>
