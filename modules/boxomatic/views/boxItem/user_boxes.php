<?php
$baseUrl = $this->createFrontendUrl('/') . '/themes/boxomatic/admin';
$cs = Yii::app()->clientScript;
$cs->registerCssFile($baseUrl . '/css/ui-lightness/jquery-ui.css');

$cs->registerCoreScript('jquery.ui');
$cs->registerScriptFile($baseUrl . '/js/ui.touch-punch.min.js', CClientScript::POS_END);
$cs->registerScriptFile($baseUrl . '/js/ui.datepicker.min.js', CClientScript::POS_END);
$cs->registerScriptFile($baseUrl . '/js/boxitem/userBoxes.js', CClientScript::POS_END);

$this->breadcrumbs = array(
    'Box-O-Matic' => array('/snapcms/boxomatic/index'),
    'Customers' => array('user/customers'),
    'Orders',
);
//$this->page_heading = 'Orders';


if ($SelectedDeliveryDate):
//	$this->page_heading_subtext = 'for '.SnapFormat::date($SelectedDeliveryDate->date);
    $this->menu = array(
        array('icon' => 'glyphicon-cog', 'label' => 'Process Customers', 'url' => array('boxItem/processCustomers', 'date' => $SelectedDeliveryDate->id), 'linkOptions' => array('confirm' => 'Are you sure you want to process all customers?')),
        array('icon' => 'glyphicon-ok', 'label' => 'Pack Boxes', 'url' => array('boxItem/create', 'date' => $SelectedDeliveryDate->id)),
    );
else:
    $this->menu = array(
        array('icon' => 'glyphicon-refresh', 'label' => 'Process Customers', 'url' => 'javascript:void(0)', 'linkOptions' => array('class' => 'text-muted', 'confirm' => 'Are you sure you want to process all customers?')),
        array('icon' => 'glyphicon-ok', 'label' => 'Pack Boxes', 'url' => 'javascript:void(0)', 'linkOptions' => array('class' => 'text-muted')),
    );
endif;

Yii::app()->clientScript->registerScript('initPageSize', <<<EOD
	$('.change-pageSize').live('change', function() {
		$.fn.yiiGridView.update('customer-list-grid',{ data:{ pageSize: $(this).val() }})
	});
	$('.search-form form').submit(function(){
		$.fn.yiiGridView.update('customer-grid', {
			data: $(this).serialize()
		});
		return false;
	});
EOD
        , CClientScript::POS_READY);
/*
  ?>
  <div id="calendar-dropdown" class="page-header dropdown">
  <h1>Orders	<small class="dropdown-toggle" data-toggle="dropdown" data-target="#calendar-dropdown"><?php echo 'for ' . SnapFormat::date($SelectedDeliveryDate->date) ?> <b class="caret"></b></small></h1>
  <div class="dropdown-menu" aria-labelledby="dLabel" role="menu">
  <li>
  <div class="calendar">
  <script type="text/javascript">
  var curUrl = "<?php echo $this->createUrl('boxItem/userBoxes'); ?>";
  var selectedDate =<?php echo $SelectedDeliveryDate ? "'$SelectedDeliveryDate->date'" : 'null' ?>;
  var availableDates =<?php echo json_encode(SnapUtil::makeArray($DeliveryDates)) ?>;
  </script>
  <div class="delivery-date-picker"></div>
  <noscript>
  <?php foreach ($DeliveryDates as $DeliveryDate): ?>
  <?php echo CHtml::link($DeliveryDate->date, array('boxItem/userBoxes', 'date' => $DeliveryDate->id)) ?>,
  <?php endforeach; ?>
  </noscript>
  </div>
  </li>
  </div>
  </div>
 */
?>
<div class="page-header">
    <div class="row">
        <div class="col-sm-1"><h1>Orders</h1></div>
        <div class="col-sm-2">
            <input type="text" id="find-by-date" value="<?php echo date("d-m-Y") ?>" class="form-control" style="background-color:#f8f8f8;border:none;border-bottom: 1px solid"/>
        </div>
    </div>
    <script type="text/javascript">
        var curUrl = "<?php echo $this->createUrl('boxItem/userBoxes'); ?>";
        var selectedDate =<?php echo $SelectedDeliveryDate ? "'$SelectedDeliveryDate->date'" : 'null' ?>;
        var availableDates =<?php echo json_encode(SnapUtil::makeArray($DeliveryDates)) ?>;
    </script>
</div>
<div class="row">
    <div id="customerList" class="col-md-9">
        <?php
        $this->beginWidget('bootstrap.widgets.BsPanel', array(
            'title' => 'Boxes',
            'titleTag' => 'h3',
        ));
        ?>
        <?php $dataProvider = $SelectedDeliveryDate ? $UserBoxes->boxSearch($SelectedDeliveryDate->id) : $UserBoxes->boxSearch(-1); ?>
        <?php $pageSize = Yii::app()->user->getState('pageSize', 10); ?>
        <?php
        $this->widget('bootstrap.widgets.BsGridView', array(
            'id' => 'customer-list-grid',
            'cssFile' => '',
            'dataProvider' => $dataProvider,
            'filter' => $UserBoxes,
            'summaryText' => 'Displaying {start}-{end} of {count} result(s). ' .
            CHtml::dropDownList(
                    'pageSize', $pageSize, array(5 => 5, 10 => 10, 20 => 20, 50 => 50, 100 => 100), array('class' => 'change-pageSize')) .
            ' rows per page',
            'columns' => array(
                'user_box_id',
                array(
                    'name' => 'customer_full_name',
                    'type' => 'raw',
                    'value' => 'CHtml::link($data->User->full_name,array("user/view","id"=>$data->user_id))'
                ),
                array(
                    'name' => 'User.balance',
                    'value' => 'SnapFormat::currency($data->User->balance)',
                ),
                array(
                    'name' => 'customer_box_price',
                    'value' => 'SnapFormat::currency($data->Box->box_price + $data->delivery_cost)',
                    'filter' => CHtml::listData(BoxSize::model()->findAll(), 'box_size_price', 'box_size_name')
                ),
                array(
                    'name' => 'status',
                    'value' => '$data->status_text',
                    'filter' => UserBox::model()->statusOptions,
                ),
                array(
                    'class' => 'bootstrap.widgets.BsButtonColumn',
                    'header' => 'Actions',
                    'template' => '{login}{process}{cancel}{set_approved}{set_delivered}',
                    'buttons' => array(
                        'login' => array
                            (
                            'label' => '<i class="glyphicon glyphicon-user"></i>',
                            'url' => 'array("user/loginAs","id"=>$data->user_id)',
                            'options' => array('title' => 'Login As'),
                        ),
                        'process' => array
                            (
                            'label' => '<i class="glyphicon glyphicon-cog"></i>',
                            'url' => 'array("boxItem/processCustBox","custBox"=>$data->user_box_id)',
                            'visible' => '$data->status==UserBox::STATUS_DECLINED',
                            'options' => array('title' => 'Process'),
                        ),
                        'cancel' => array
                            (
                            'url' => 'array("boxItem/refund","custBox"=>$data->user_box_id)',
                            'visible' => '$data->status==UserBox::STATUS_APPROVED',
                            'label' => '<i class="glyphicon glyphicon-remove"></i>',
                            'options' => array('confirm' => 'Are you sure you want to refund this box?', 'title' => 'Cancel & Refund'),
                        ),
                        'set_approved' => array
                            (
                            'url' => 'array("boxItem/setApproved","custBox"=>$data->user_box_id)',
                            'visible' => '$data->status==UserBox::STATUS_DELIVERED',
                            'label' => '<i class="glyphicon glyphicon-check"></i>',
                            'options' => array('confirm' => 'Are you sure you want to set this box to Approved?', 'title' => 'Set Approved'),
                        ),
                        'set_delivered' => array
                            (
                            'url' => 'array("boxItem/setDelivered","custBox"=>$data->user_box_id)',
                            'visible' => '$data->status==UserBox::STATUS_APPROVED',
                            'label' => '<i class="glyphicon glyphicon-thumbs-up"></i>',
                            'options' => array('confirm' => 'Are you sure you want to set this box to Collected/Delivered?', 'title' => 'Set Delivered'),
                        )
                    ),
                ),
            ),
        ));
        ?>
        <?php $this->endWidget(); ?>

        <?php
        $this->beginWidget('bootstrap.widgets.BsPanel', array(
            'title' => 'Order Items',
        ));
        ?>
        <?php
        $this->widget('bootstrap.widgets.BsGridView', array(
            'id' => 'customer-extras-grid',
            'cssFile' => '',
            'dataProvider' => $CDDsWithExtras,
            'filter' => $CDD,
            'summaryText' => 'Displaying {start}-{end} of {count} result(s). ' .
            CHtml::dropDownList(
                    'pageSize', $pageSize, array(5 => 5, 10 => 10, 20 => 20, 50 => 50, 100 => 100), array('class' => 'change-pageSize')) .
            ' rows per page',
            'columns' => array(
                array(
                    'name' => 'customer_user_id',
                    'value' => '$data->User->id'
                ),
                array(
                    'name' => 'search_full_name',
                    'type' => 'raw',
                    'value' => 'CHtml::link(CHtml::value($data,"User.full_name"),array("user/view","id"=>$data->User->id))',
                ),
                array(
                    'name' => 'User.balance',
                    'value' => 'SnapFormat::currency($data->User->balance)',
                ),
                array(
                    'name' => 'extras_total',
                    'value' => 'SnapFormat::currency($data->extras_total)',
                    'filter' => false,
                ),
                array(
                    'name' => 'extras_item_names',
                    'value' => 'substr($data->extras_item_names,0,20)."..."'
                ),
                array(
                    'name' => 'status',
                    'value' => '$data->status_text',
                    'filter' => Order::model()->statusOptions,
                ),
                array(
                    'class' => 'bootstrap.widgets.BsButtonColumn',
                    'header' => 'Actions',
                    'template' => '{login}{process}{cancel}{set_approved}{set_delivered}',
                    'buttons' => array(
                        'login' => array
                            (
                            'label' => '<i class="glyphicon glyphicon-user"></i>',
                            'url' => 'array("user/loginAs","id"=>$data->User->id)',
                            'options' => array('title' => 'Login As'),
                        ),
                        'process' => array
                            (
                            'label' => '<i class="glyphicon glyphicon-cog"></i>',
                            'url' => 'array("boxItem/processCustExtras","cdd"=>$data->id)',
                            'visible' => '$data->status==Order::STATUS_DECLINED',
                            'options' => array('title' => 'Process'),
                        ),
                        'cancel' => array
                            (
                            'url' => 'array("customer/refundExtras","cdd"=>$data->id)',
                            'visible' => '$data->status==Order::STATUS_APPROVED',
                            'label' => '<i class="glyphicon glyphicon-cancel"></i>',
                            'options' => array('confirm' => 'Are you sure you want to refund this order?', 'title' => 'Cancel & Refund'),
                        ),
                        'set_approved' => array
                            (
                            'url' => 'array("customer/setExtrasApproved","cdd"=>$data->id)',
                            'visible' => '$data->status==Order::STATUS_DELIVERED',
                            'label' => '<i class="glyphicon glyphicon-check"></i>',
                            'options' => array('confirm' => 'Are you sure you want to set this box to Approved?', 'title' => 'Set Approved'),
                        ),
                        'set_delivered' => array
                            (
                            'url' => 'array("customer/setExtrasApproved","cdd"=>$data->id)',
                            'visible' => '$data->status==Order::STATUS_APPROVED',
                            'label' => '<i class="glyphicon glyphicon-thumbs-up"></i>',
                            'options' => array('confirm' => 'Are you sure you want to set this box to Collected/Delivered?', 'title' => 'Set Delivered'),
                        )
                    ),
                ),
            ),
        ));
        ?>
        <?php $this->endWidget(); ?>
    </div>

    <div class="col-md-3">
        <div class="sticky">
            <?php
            $this->beginWidget('bootstrap.widgets.BsPanel', array(
                'title' => 'Menu',
                'contentCssClass' => '',
                'htmlOptions' => array(
                    'class' => 'panel',
                ),
                'type' => BsHtml::PANEL_TYPE_PRIMARY,
            ));
            $this->widget('application.widgets.SnapMenu', array(
                'items' => $this->menu,
                'htmlOptions' => array('class' => 'nav nav-stacked'),
            ));
            $this->endWidget();
            ?>
        </div>
    </div>
</div>

