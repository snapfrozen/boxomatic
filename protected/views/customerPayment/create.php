<h1>Add Credit</h1>

<h2>With PayPal</h2>

<form name= "order" action="https://www.paypal.com/cgi-bin/webscr" method="post">
<input type="hidden" name="cmd" value="_ext-enter" />
<input type="hidden" name="redirect_cmd" value="_xclick" />
<input type="hidden" name="return" value = "http://app.foodbox.org.au/index.php?r=customerPayment/paypalSuccess" />
<input type="hidden" name="cancel_return" value = "http://app.foodbox.org.au/index.php?r=customerPayment/paypalFailure" />
<input type="hidden" name="business" value="secretary@northbankgarden.org.au" />
<input type="hidden" name="item_name" value="Box-o-Matic 'Bellofoodbox' Credit" />
<input type="hidden" name="quantity" value="1" />
<label for="amount">Amount: <input type="text" name="amount" value="" />

<input type="hidden" name="email" value="<?php echo $User->user_email ?>" />
<input type="hidden" name="first_name" value="<?php echo $User->first_name ?>" />
<input type="hidden" name="last_name" value="<?php echo $User->last_name ?>" />
<input type="hidden" name="address1" value ="<?php echo $User->user_address ?>" />
<input type="hidden" name="address2" value ="<?php echo $User->user_address2 ?>" />
<input type="hidden" name="city" value="" />
<input type="hidden" name="state" value="<?php echo $User->user_state ?>" />
<input type="hidden" name="zip" value="<?php echo $User->user_postcode ?>" />
<input type="hidden" name="custom" value="<?php echo $User->Customer->customer_id ?>" />
<input type="hidden" name="currency_code" value="AUD" />
<input type="submit" name="submit" value="Add credit" />
</form>
<p>&nbsp;</p>

<h2>Other Methods</h2>

<ul>
  <li><strong>Direct Deposit:</strong> BSB 704328, Acc 221552 (for BCU customers 221552 S20)<br />
  When depositing money via bank transfer, please use your Bellofoodbox ID as your reference.<br />
  Your Bellofoodbox ID is: <strong><?php echo $Customer->User->bfb_id; ?></strong>.<br /><br /></li>
  <li><strong>Cash:</strong> At the Kombu order box anytime, or in person on Wednesdays only during box pick up between 3.30pm - 6.30pm).</p>
</ul>

<p><strong>BCU Customers:</strong> If you are making a transfer from your BCU account to ours you will need to enter your ID number in the 'Your Reference' field, above the 'Amount' field.  This reference will be displayed on your statement AND our statement.</p>

<!-- <input type="hidden" name="shipping" value="5.00" />
<input type="hidden" name="shipping2" value="5.00" /> -->

<?php // echo $this->renderPartial('_form', array('model'=>$model));  ?>
