<div class="row">
	<div class="large-12 columns">
		<h2>Welcome <?php echo $model->full_name; ?></h2>
		<table>
			<tr>
				<td>Idenification Number</td>
				<td><?php echo $model->bfb_id; ?></td>
			</tr>
			<tr>
				<td>Email Address</td>
				<td><a href='mailto:<?php echo $model->email; ?>'><?php echo $model->email; ?></td>
			</tr>
			<tr>
				<td>Phone</td>
				<td><?php echo $model->user_phone; ?></td>
			</tr>
			<tr>
				<td>Mobile No.</td>
				<td><?php echo $model->user_mobile; ?></td>
			</tr>
			<tr>
				<td>Address</td>
				<td><?php echo $model->user_address; ?></td>
			</tr>
			<tr>
				<td>Suburb</td>
				<td><?php echo $model->user_suburb; ?></td>
			</tr>
			<tr>
				<td>State</td>
				<td><?php echo $model->user_state; ?></td>
			</tr>
			<tr>
				<td>Postcode</td>
				<td><?php echo $model->user_postcode; ?></td>
			</tr>
			<tr>
				<td>Last Login</td>
				<td><?php echo $model->last_login_time; ?></td>
			</tr>
		</table>
		<div class="right">
			<?php echo CHtml::link('Box don\'t like list',array('user/dontWant','id'=>$model->id), array('class' => 'button')) ?>
			<?php echo CHtml::link('Update',array('user/update','id'=>$model->id), array('class' => 'button')) ?>
		</div>
	</div>

	<div class="large-12 columns">

		<h2>Default delivery location</h2>

		<?php $this->widget('zii.widgets.CDetailView', array(
			'cssFile' => '', 
			'data'=>$model,
			'attributes'=>array(
				'Location.location_name',
				'Location.location_delivery_value',
				array(
					'name'=>'UserLocation.address',
					'visible'=>isset($model->UserLocation),
				),
				//'customer_notes',
			),
		)); ?>

	</div>
</div>
