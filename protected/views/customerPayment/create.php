<?php
$this->menu=array(
	array('label'=>'List CustomerPayment', 'url'=>array('index')),
	array('label'=>'Manage CustomerPayment', 'url'=>array('admin')),
);
?>

<h1>Add Credit</h1>
<form name= "order" action="https://www.paypal.com/cgi-bin/webscr" method="post">
<input type="hidden" name="cmd" value="_ext-enter" />
<input type="hidden" name="redirect_cmd" value="_xclick" />
<input type="hidden" name="return" value = "http://app.foodbox.org.au/index.php?r=customerPayment/paypalSuccess" />
<input type="hidden" name="cancel_return" value = "http://app.foodbox.org.au/index.php?r=customerPayment/paypalFailure" />
<input type="hidden" name="business" value="donovan@snapfrozen.com.au" />
<input type="hidden" name="item_name" value="Box-o-Matic 'Bellofoodbox' Credit" />
<input type="hidden" name="quantity" value="1" />
<label for="amount">Amount: <input type="text" name="amount" value="" />

<!-- <input type="hidden" name="shipping" value="5.00">
<input type="hidden" name="shipping2" value="5.00"> -->

<input type="hidden" name="email" value="">
<input type="hidden" name="first_name" value="">
<input type="hidden" name="last_name" value="">
<input type="hidden" name="address1" value ="">
<input type="hidden" name="address2" value ="">
<input type="hidden" name="city" value="">
<input type="hidden" name="state" value="">
<input type="hidden" name="zip" value="">

<input type="submit" name="submit" value="Add credit" />

</form>

<?php // echo $this->renderPartial('_form', array('model'=>$model)); 
	
?>