<?php $this->pageTitle=Yii::app()->name; ?>
<div class="row">

	<div class="large-4 columns">
		<h1><?php echo $Content->title ?></h1>
	</div>

	<?php if(Yii::app()->user->isGuest): ?>
	
	<div class="large-4 columns">
		<img src="<?php echo Yii::app()->request->baseUrl; ?>/images/login.png" alt="">
		<div contenteditable="true" id="field_content_4" data-id="<?php echo $Content->id ?>"> 
			<?php echo $Content->content_4; ?>
		</div>
		<?php echo CHtml::link('Login',array('site/login'),array('class'=>'button')); ?>
	</div>

	<div class="large-4 columns">
		<img src="<?php echo Yii::app()->request->baseUrl; ?>/images/customer.png" alt="">
		<div contenteditable="true" id="field_content_5" data-id="<?php echo $Content->id ?>"> 
			<?php echo $Content->content_5; ?>
		</div>
		<?php echo CHtml::link('Register',array('site/register'),array('class'=>'button')); ?>
	</div>

	<?php else: ?>

	<!-- <div class="large-8 eight columns"> -->
	<div class="large-8 columns">
		<div class="large-4 columns">
			<img src="<?php echo Yii::app()->request->baseUrl; ?>/images/credits.png" alt="">
			<div contenteditable="true" id="field_content_1" data-id="<?php echo $Content->id ?>"> 
				<?php echo $Content->content_1; ?>
			</div>
			<?php echo CHtml::link('Learn More',array('customerPayment/create'),array('class'=>'button')); ?>
		</div>
		<div class="large-4 columns">
			<img src="<?php echo Yii::app()->request->baseUrl; ?>/images/order.png" alt="">
			<div contenteditable="true" id="field_content_2" data-id="<?php echo $Content->id ?>"> 
				<?php echo $Content->content_2; ?>
			</div>
			<?php echo CHtml::link('Learn More',array('customerBox/order'),array('class'=>'button')); ?>
		</div>
		<div class="large-4 columns">
			<img src="<?php echo Yii::app()->request->baseUrl; ?>/images/profile.png" alt="">
			<div contenteditable="true" id="field_content_3" data-id="<?php echo $Content->id ?>"> 
				<?php echo $Content->content_3; ?>
			</div>
			<?php echo CHtml::link('Learn More',array('user/update','id'=>Yii::app()->user->id),array('class'=>'button')); ?>
		</div>
	</div>
	<!-- </div> -->
	<?php endif; ?>
</div>