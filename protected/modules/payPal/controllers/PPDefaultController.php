<?php
/*
 * Copyright 2010 Stian Liknes <stianlik@gmail.com>. All rights reserved.
 *
 * Redistribution and use in source and binary forms, with or without modification, are
 * permitted provided that the following conditions are met:
 *
 * 1. Redistributions of source code must retain the above copyright notice, this list of
 * conditions and the following disclaimer.
 *
 * 2. Redistributions in binary form must reproduce the above copyright notice, this list
 * of conditions and the following disclaimer in the documentation and/or other materials
 * provided with the distribution.
 *
 * THIS SOFTWARE IS PROVIDED BY Stian Liknes <stianlik@gmail.com> ``AS IS'' AND ANY EXPRESS OR IMPLIED
 * WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND
 * FITNESS FOR A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL <COPYRIGHT HOLDER> OR
 * CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR
 * CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR
 * SERVICES; LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON
 * ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT (INCLUDING
 * NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF
 * ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
 *
 * The views and conclusions contained in the software and documentation are those of the
 * authors and should not be interpreted as representing official policies, either expressed
 * or implied, of Stian Liknes <stianlik@gmail.com>.
 */

/**
 * Example controller for IPN and PDT events.
 *
 * CREATED: 2010-10-23
 * UPDATED: 2010-11-02
 *
 * @author Stian Liknes <stianlik@gmail.com>
 */
class PPDefaultController extends Controller
{
	public function actionIndex()
	{
		$this->renderText("PayPal example controller");
	}

	/**
	 * Show payment details to customer when PDT is received.
	 */
	public function actionPdt() {
		$pdt = new PPPdtAction($this, "pdt");

		// Just invoking a success event, processing done by IPN listener
		$pdt->onRequest = function($event) {
			$event->sender->onSuccess($event);
		};

		// Notify user about successfull payment
		$pdt->onSuccess = function($event) {
			$str = "Success<br />";
			foreach ($event->details as $k => $v)
				$str.="$k => $v<br />";
			$event->sender->controller->renderText($str);
		};

		// Notify user about failed payment
		$pdt->onFailure = function($event) {
			$event->sender->controller->renderText("Failure");
		};

		$pdt->run();
	}

	/**
	 * Process payment and notify user if IPN is received.
	 */
	public function actionIpn() {
		$ipn = new PPIpnAction($this,"ipn");

		/*
		 * Process payment
		 *
		 * See PPPhpTransaction for validation details, important values:
		 * - PPPhpTransaction::currency = Valid currency (default: USD)
		 * - PPPhpTransaction::amount = Minumum payment amount (default: 5.00)
		 *
		 * I recommend using an active record for storage / validation.
		 */ 
		$ipn->onRequest = function($event) {
			// Check if this is a transaction
			if (!isset($event->details["txn_id"])) {
				$event->msg = "Missing txn_id";
				Yii::log($event->msg,"warning","payPal.controllers.DefaultController");
				$event->sender->onFailure($event);
				return;
			}

			// Put payment details into a transaction model
			$transaction = new PPPhpTransaction;
			$transaction->paymentStatus = $event->details["payment_status"];
			$transaction->mcCurrency = $event->details["mc_currency"];
			$transaction->mcGross = $event->details["mc_gross"];
			$transaction->receiverEmail = $event->details["receiver_email"];
			$transaction->txnId = $event->details["txn_id"];
			
			// Failed to process payment: Log and invoke failure event
			if (!$transaction->save()) {
				$event->msg = "Could not process payment";
				Yii::log("{$event->msg}\nTransaction ID: {$event->details["txn_id"]}", "error",
						"payPal.controllers.DefaultController");
				$event->sender->onFailure($event);
			}

			// Successfully processed payment: Log and invoke success event
			else if ($transaction->save()) {
				$event->msg = "Successfully processed payment";
				Yii::log("{$event->msg}\nTransaction ID: {$event->details["txn_id"]}", "info",
						"payPal.controllers.DefaultController");
				$event->sender->onSuccess($event);
			}
		};

		// Ignoring failures
		$ipn->onFailure = function($event) {
			// Could e.g. send a notification mail on certain events
		};

		// Send confirmation mail to customer
		$ipn->onSuccess = function($event) {
			$to = $event->details["payer_email"];
			$from = $event->details["receiver_email"];
			$subject = "Payment received";
			$body = "Your payment has been processed.\n" .
				"Receiver: $from\n" .
				"Amount: {$event->details["mc_gross"]} {$event->details["mc_amount"]}\n";
			$headers="From: $from\r\nReply-To: $from";
			mail($to,$subject,$body,$headers);
		};
		
		$ipn->run();
	}
}