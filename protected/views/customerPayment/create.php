<div class="row">
	<div class="large-8 columns">
		<h2>Add Credit - Paypal</h2>
		<p></p>
		<form name= "order" action="https://www.paypal.com/cgi-bin/webscr" method="post">
			<fieldset>
				<legend>Payment Form</legend>

				<label for="amount">Amount: <input type="text" name="amount" value="" />
				<input type="hidden" name="cmd" value="_ext-enter" />

				<input type="hidden" name="redirect_cmd" value="_xclick" />
				<input type="hidden" name="return" value = "<?php echo $this->createAbsoluteUrl('customerPayment/paypalSuccess') ?>" />
				<input type="hidden" name="cancel_return" value = "<?php echo $this->createAbsoluteUrl('customerPayment/paypalFailure') ?>" />
				<input type="hidden" name="business" value="Bellingengreengrocers@westnet.com.au" />
				<input type="hidden" name="item_name" value="Box-o-Matic 'Bellofoodbox' Credit" />
				<input type="hidden" name="quantity" value="1" />

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
				<input type="submit" name="submit" value="Add credit" class="button" />

			</fieldset>
		</form>
	</div>
	<div class="large-4 columns">
		<h2>Other Methods</h2>
		<p>Direct Deposit: BSB: 082469, ACC: 144056759 Account Name: Bellingen Green Grocers Pty Ltd.</p>
		<p>When depositing money via bank transfer, please use your Bellofoodbox ID as your reference.</p>
		<p>Your Bellofoodbox ID is: BFB2088.<br />Cash: In person on at Bellingen Greengrocers.</p>
	</div>
</div>
