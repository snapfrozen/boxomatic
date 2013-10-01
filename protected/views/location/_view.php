<tr>
	<td><?php echo CHtml::link(CHtml::encode($data->location_id), array('view', 'id'=>$data->location_id)); ?></td>
	<td><?php echo CHtml::encode($data->location_name); ?></td>
	<td><?php echo CHtml::encode($data->location_delivery_value); ?></td>
</tr>