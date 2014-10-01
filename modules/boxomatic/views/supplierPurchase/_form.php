<?php
$baseUrl = $this->createFrontendUrl('/').'/themes/boxomatic/admin';
$cs=Yii::app()->clientScript;
$cs->registerCssFile($baseUrl . '/css/ui-lightness/jquery-ui.css');
$cs->registerScriptFile($baseUrl . '/js/supplierpurchase/_form.js',CClientScript::POS_END);

if(isset($_POST['new_product']) && !$SupplierProduct->validate()):
    $cs->registerScript('supplier_product_error','$("#sp-form").modal("show")',CClientScript::POS_END); //   
endif;

?>
<div class="form">
<?php $form=$this->beginWidget('application.widgets.SnapActiveForm', array(
	'id'=>'supplier-purchase-form',
	'enableAjaxValidation'=>false,
	'layout' => BsHtml::FORM_LAYOUT_HORIZONTAL,
	'htmlOptions' => array('class'=>'row')
)); ?>
    
	<div class="col-lg-9 clearfix">
		<?php //echo CHtml::hiddenField('update_url',$this->createUrl('supplier/getListItems')); ?>

        
        <div class="form-group">
            <div class="col-md-12">
                <div class="row">
                    <div class="col-md-2">Product</div>
                    <div class="col-md-2">Quantity</div>
                    <div class="col-md-2">Price</div>
                    <div class="col-md-2">Available From</div>
                    <div class="col-md-2">Available To</div>
                    <div class="col-md-2">In shop</div>
                </div>
            </div>
        </div>

        <div id="products-to-add">
            <?php foreach($model->Products as $SPP): ?>
                <?php echo $this->renderPartial('_spp_form',array(
                    'SPP'=>$SPP,
                    'supplier_id'=>$model->supplier_id)); ?>
            <?php endforeach; ?>
        </div>
        
        <div id="add-product-form">
            <div class="form-group">
                <div class="col-md-2">
                    <?php echo BsHtml::dropDownList('supplier_product_id', null, SupplierProduct::getDropdownListItems($model->supplier_id), array(
                        'class'=>'chosen',
                        'prompt'=>'- Select -',
                    )); ?>
                </div>
                <div class="col-md-6">
                    <?php echo BsHtml::submitButton('Add Product',array('class'=>'add-product','name'=>'add_product')); ?>
                    <!-- Button trigger modal -->
                    <button class="btn btn-default" data-toggle="modal" data-target="#sp-form">
                        Create New Product
                    </button>
                </div>
            </div>
        </div>

        <hr />
        
        <?php echo $form->dateFieldControlGroup($model,'delivery_date'); ?>
        <?php echo $form->textFieldControlGroup($model, 'other_costs'); ?>
        <div class="form-group">
            <div class="col-md-10 col-md-offset-2">
                <?php echo SnapFormat::currency($model->total); ?>
            </div>
        </div>
        <?php echo $form->textAreaControlGroup($model, 'order_notes'); ?>
        
		
	</div>
	<?php echo $this->renderPartial('//layouts/_form_sidebar'); ?>
	
<?php $this->endWidget(); ?>
</div>

 <?php
$form = $this->beginWidget('application.widgets.SnapActiveForm', array(
    'id' => 'add-supplier-prouct-form',
    'enableAjaxValidation' => false,
    'htmlOptions' => array('enctype' => 'multipart/form-data', 'class' => 'row')
));
?>
<!-- Modal -->
<div class="modal fade" id="sp-form" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
        <h4 class="modal-title" id="myModalLabel">New Product</h4>
      </div>
      <?php echo $this->renderPartial('../supplierProduct/_inner_form', array('model'=>$SupplierProduct, 'hideSupplier'=>true, 'form'=>$form)); ?>            </div>
  </div>
</div>
<?php $this->endWidget(); ?>