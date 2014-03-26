<?php echo $this->renderPartial('_form', array(
		'model'=>$model,
		'SupplierProducts'=>$SupplierProducts,
		'DeliveryDates'=>$DeliveryDates,
		'DeliveryDateBoxes'=>$DeliveryDateBoxes,
		'SelectedDeliveryDate'=>$SelectedDeliveryDate,
		'selectedItemId'=>$selectedItemId,
	)); 
?>

