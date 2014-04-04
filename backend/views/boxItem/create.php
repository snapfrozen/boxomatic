<?php
$this->breadcrumbs=array(
	'Box-O-Matic'=>array('/snapcms/boxomatic/index'),
	'Boxes'=>array('boxes/index'),
	'Box Packing',
);
$this->menu=array(
	array('icon' => 'glyphicon glyphicon-thumbs-up', 'label'=>'Update Boxes', 'url'=>'#', 'linkOptions'=>array('id'=>'update-boxes')),
	array('icon' => 'glyphicon glyphicon-export', 'label'=>'Generate packing list Spreadsheet', 'url'=>array('deliveryDate/generatePackingList','date'=>$SelectedDeliveryDate->id), 'linkOptions'=>array('id'=>'update-boxes')),
	array('icon' => 'glyphicon glyphicon-export', 'label'=>'Generate customer list', 'url'=>array('deliveryDate/generateCustomerList','date'=>$SelectedDeliveryDate->id), 'linkOptions'=>array('id'=>'update-boxes')),
	array('icon' => 'glyphicon glyphicon-export', 'label'=>'Generate customer list PDF', 'url'=>array('deliveryDate/generateCustomerListPdf','date'=>$SelectedDeliveryDate->id), 'linkOptions'=>array('id'=>'update-boxes')),
	array('icon' => 'glyphicon glyphicon-export', 'label'=>'Generate order list', 'url'=>array('deliveryDate/generateOrderList','date'=>$SelectedDeliveryDate->id), 'linkOptions'=>array('id'=>'update-boxes')),
);

$dateLabel = $SelectedDeliveryDate ? SnapFormat::date($SelectedDeliveryDate->date) : 'None Selected';
$this->page_heading = 'Box Packing';
$this->page_heading_subtext = $dateLabel;
?>
<?php echo $this->renderPartial('_form', array(
		'model'=>$model,
		'SupplierProducts'=>$SupplierProducts,
		'DeliveryDates'=>$DeliveryDates,
		'DeliveryDateBoxes'=>$DeliveryDateBoxes,
		'SelectedDeliveryDate'=>$SelectedDeliveryDate,
		'selectedItemId'=>$selectedItemId,
	)); 
?>

