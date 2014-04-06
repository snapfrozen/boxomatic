<?php
	$cs=Yii::app()->clientScript;
?>
<h1>Do not like list</h1>
<p>Select the products you do not wish to appear in your box</p>
<div class="row">
	<div class="large-2 columns leftCol">
		<h2>Categories</h2>
		<ul class="categories">
			<?php echo Category::model()->getCategoryTree(SnapUtil::config('boxomatic/supplier_product_root_id'), array('user/dontWant','id'=>Yii::app()->user->id), $curCat); ?>
			<li class="<?php echo ($curCat==Category::uncategorisedCategory?'selected':'') ?>"><?php echo CHtml::link('Uncategorised', array('user/dontWant','id'=>Yii::app()->user->id, 'cat'=>Category::uncategorisedCategory)) ?></li>
		</ul>
	</div>
	<div class="large-7 columns products">
		<h2>Products</h2>
		<div class="list-view">
			<div class="items row">
			<?php foreach($SupplierProducts as $Product): ?>
				
			<div class="view large-12 columns end">
				<div class="row">
					<div class="large-9 columns">
						<div class="image">
							<?php echo SnapHtml::image($Product, 'image', array('w'=>70,'h'=>70,'zc'=>1)) ?>
						</div>
						<div class="inner">
							<div class="row">
								<div class="large-9 columns">
									<h3><?php echo CHtml::encode($Product->name); ?></h3>
									<span class="description"><?php echo $Product->description; ?></span>
								</div>
							</div>
						</div>
					</div>
					<div class="large-3 columns">
						<?php if(isset($dontWantIds[$Product->id])): ?>
						<?php echo CHtml::link('Don\'t like','javascript:void(0)',array('class'=>'button small disabled')); ?>
						<?php else: ?>
						<?php echo CHtml::link('Don\'t like',array('user/dontWant','id'=>Yii::app()->user->id,'product'=>$Product->id,'cat'=>$curCat),array('class'=>'button small')); ?>
						<?php endif; ?>
					</div>
				</div>
			</div>
			<?php endforeach; ?>
			
			</div>
		</div>
	</div>
	<div class="large-3 columns">
		<h2>Do not like list</h2>
		<div class="list-view">
			<div class="items row">
			<?php foreach($model->DontWant as $Product): ?>
			<div class="view large-12 columns end">
				<div class="row">
					<div class="large-12 columns">
						<div class="image">
							<?php echo SnapHtml::image($Product, 'image', array('w'=>70,'h'=>70,'zc'=>1)) ?>
						</div>
						<div class="inner">
							<h3><?php echo CHtml::encode($Product->name); ?></h3>
							<?php echo CHtml::link('Like',array('user/dontWant','id'=>Yii::app()->user->id,'like'=>$Product->id,'cat'=>$curCat),array('class'=>'button small')); ?>
						</div>
					</div>
				</div>
			</div>
			<?php endforeach; ?>
			</div>
		</div>
	</div>
</div>

