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
 * PayPal PDT
 *
 * CREATED: 2010-10-25
 * UPDATED: 2010-10-28
 *
 * Receives PDT requests from PayPal, everything is logged using Yii::log().
 *
 * @author Stian Liknes <stianlik@gmail.com>
 */
class PPPdtAction extends CAction {

	/**
	 * Payment processing:
	 *
	 * 1. Confirm the payment status
	 * 2. Validate that the receiver_email is an email address registered in
	 *    your PayPal account, to prevent the payment from being sent to a
	 *	  fraudster’s account.
     * 3. Make sure that the txn_id is not a duplicate to prevent someone
	 *    from using reusing an old, completed transaction.
     * 4. Check other transaction details, such as the item number, price, and
	 *    currency to confirm that the price has not been changed.
	 * 5. Save transaction
	 *
	 * Listeners have to run onSucces or onFailure after processing a request.
	 *
	 * @param PPEvent $event
	 */
	public function onRequest($event) {
		$this->raiseEvent("onRequest", $event);
	}

	/**
	 * Action performed if payment processing succeeds.
	 *
	 * @param PPEvent $event Event
	 */
	public function onSuccess($event) {
		$this->raiseEvent("onSuccess", $event);
	}

	/**
	 * Action performed if payment processing fails.
	 *
	 * @param PPEvent $event
	 */
	public function onFailure($event) {
		$this->raiseEvent("onFailure", $event);
	}

	public function run() {
		$event = new PPEvent($this);

		/** If transaction id is missing, it's logged and a failure event is raised **/
		// 2010-10-28: TESTED OK
		if (!isset($_GET['tx'])) {
			$event->msg = "PDT received an HTTP GET request without a transaction ID.";
			Yii::log($event->msg, "error", "payPal.controllers.pdt.PPPdtAction");
			$this->onFailure($event);
			return;
		}

		/** Send PDT Request to PayPal **/
		// 2010-10-28: TESTED OK
		$event->requestAr = array(
			'cmd'=>'_notify-synch',
			'tx'=>$_GET['tx'],
			'at'=>Yii::app()->getModule('payPal')->account->identityToken,
		);
		$getVars = PPUtils::implode('&',PPUtils::urlencode($event->requestAr));
		$response = PPUtils::httpPost(PPUtils::getUrl(PPUtils::PDT), $getVars, false);

		/** If PDT request fails it is logged and a failure event is raised **/
		// 2010-10-28: TESTED OK
		if ($response["status"] == false) {
			$event->msg = "HTTP POST request to PayPal failed";
			Yii::log("{$event->msg}\nRequest:\n$getVars", "error", "payPal.controllers.pdt.PPPdtAction");
			$this->onFailure($event);
			return;
		}

		$responseAr = explode("\n", $response['httpResponse']);

		/** If PayPal is unable to verify a request it is logged and a failure event is raised **/
		// 2010-10-28: TESTED OK
		if (count($responseAr) < 1 || $responseAr[0] != 'SUCCESS') {
			$event->msg = "PDT request failed";
			Yii::log("{$event->msg}\nRequest:\n$getVars\nResponse:\n{$response["httpResponse"]}",
					"error", "payPal.controllers.pdt.PPPdtAction");
			$this->onFailure($event);
			return;
		}

		/** URL decode and write result into assoc array **/
		// 2010-10-28: TESTED OK
		for ($i=1;$i<count($responseAr);$i++) {
			$row = explode('=',$responseAr[$i]);
			if (count($row) > 1)
				$event->responseAr[urldecode($row[0])] = urldecode($row[1]);
		}

		/** Log successfull request and raise a onRequest event */
		// 2010-10-28: TESTED OK
		$event->details = $event->responseAr;
		$event->msg = "Successfull PDT request";
		Yii::log("{$event->msg}\nRequest:\n$getVars\nResponse:\n{$response["httpResponse"]}",
					"info", "payPal.controllers.pdt.PPPdtAction");
		$this->onRequest($event);
	}
}
?>
