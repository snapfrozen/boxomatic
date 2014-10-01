
<div class="form-group">
    <div class="col-md-12">
        <div class="row">
            <div class="col-md-2">
                <?php echo BsHtml::activeDropDownList($SPP, 'supplier_product_id', SupplierProduct::getDropdownListItems($supplier_id), array(
                    'class'=>'chosen',
                    'prompt'=>'- Select -',
                    'name' => 'SupplierPurchaseProducts['.$SPP->id.'][supplier_product_id]',
                )); ?>
            </div>
            <div class="col-md-2">
                <?php echo BsHtml::activeTextField($SPP,'quantity',array('name' => 'SupplierPurchaseProducts['.$SPP->id.'][quantity]')) ?>
            </div>
            <div class="col-md-2">
                <?php echo BsHtml::activeTextField($SPP,'price',array('name' => 'SupplierPurchaseProducts['.$SPP->id.'][price]')) ?>
            </div>
            <?php if($SPP->Product): ?>
            <div class="col-md-2">
                <?php echo BsHtml::activeDateField($SPP->Product,'customer_available_from',array('name' => 'SupplierProducts['.$SPP->supplier_product_id.'][customer_available_from]')) ?>
            </div>
            <div class="col-md-2">
                <?php echo BsHtml::activeDateField($SPP->Product,'customer_available_to', array('name' => 'SupplierProducts['.$SPP->supplier_product_id.'][customer_available_to]')) ?>
            </div>
            <div class="col-md-2">
                <?php echo BsHtml::activeCheckBox($SPP->Product, 'available_in_shop', array('name' => 'SupplierProducts['.$SPP->supplier_product_id.'][available_in_shop]')) ?>
                <button name="delete" type="submit" class="close text-danger" value="<?php echo $SPP->id ?>"><span class="glyphicon glyphicon-trash"></span></button>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>