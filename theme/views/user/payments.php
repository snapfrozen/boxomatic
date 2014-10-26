<?php $Payment = new UserPayment; //Needed for attribute labels ?>
<div class="page-header">
    <h1>Payments</h1>
</div>
<div class="row">
    <div class="col-sm-2">
        <strong><?php echo CHtml::encode($Payment->getAttributeLabel('payment_date')); ?></strong>
    </div>
    <div class="col-sm-6">
        <strong><?php echo CHtml::encode($Payment->getAttributeLabel('payment_note')); ?></strong>
    </div>
    <div class="col-sm-1">
        <strong><?php echo CHtml::encode($Payment->getAttributeLabel('payment_type')); ?></strong>
    </div>
    <div class="col-sm-1 text-right">
        <strong><?php echo CHtml::encode($Payment->getAttributeLabel('credit')); ?></strong>
    </div>
    <div class="col-sm-1 text-right">
        <strong><?php echo CHtml::encode($Payment->getAttributeLabel('debit')); ?></strong>
    </div>
    <div class="col-sm-1 text-right">
        <strong><?php echo CHtml::encode($Payment->getAttributeLabel('balance')); ?></strong>
    </div>
</div>
<?php $this->widget('bootstrap.widgets.BsListView', array(
    'dataProvider'=>$dataProvider,
    'itemView'=>'_payment_view',
    'template' => "{items}\n{summary}\n{pager}"
)); ?>
