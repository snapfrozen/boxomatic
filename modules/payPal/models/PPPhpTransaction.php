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
 * Example model for PayPal transactions using PHP file for storage.
 *
 * CREATED: 2010-10-26
 * UPDATED: 2010-10-28
 *
 * DO NOT USE THIS CODE IN A PRODUCTION ENVIRONMENT, THE SAVING/LOADING
 * MECHANISM INCLUDED ARE NO GOOD (USE A DATABASE INSTEAD). THE ONLY REASON I'M
 * STORING TRANSACIONS THIS WAY IS TO MAKE THE EXAMPLE RUNNABLE WITHOUT A
 * DATABASE SCHEMA.
 *
 * @author Stian Liknes
 */
class PPPhpTransaction extends CModel {
	// Settings
	static public $currency = "USD";
	static public $amount = "5.00";

	// Details from PayPal (PPEvent::details)
	public $paymentStatus;
	public $txnId;
	public $receiverEmail;
	public $mcCurrency;
	public $mcGross;

	// Used for PHP file storage
	static private $transactions = null;

	/**
	 * Used for PHP file storage.
	 * @param string $txnId
	 */
	public function  __construct($txnId = null) {
		$this->txnId = $txnId;
	}

	/**
	 * @return array Validation rules for the transaction
	 */
	public function rules() {
		return array(
			// Only process completed payments
			array('paymentStatus', 'compare', 'compareValue' => 'Completed'),

			// Do not process duplicates
			array('txnId','uniqueValidator'),

			// Only process payments sent to configured account
			array('receiverEmail','compare', 'compareValue' => Yii::app()->getModule('payPal')->account->email),

			// Check currency
			array('mcCurrency', 'compare', 'compareValue' => self::$currency),

			// Check payment amount
			array('mcGross', 'paymentValidator', 'amount'),
		);
	}

	public function attributeNames() {
		return array(
			array('paymentStatus', 'txnId', 'receiverEmail', 'mcCurrency', 'mcGross', 'quantity')
		);
	}

	/**
	 * Same as Yii's unique validator, but for PHP file storage.
	 *
	 * @param mixed $attribute
	 * @param array $params Not in use
	 */
	public function uniqueValidator($attribute, $params) {
		foreach ($this->getTransactions() as $v) {
			if ($v->txnId == $this->$attribute) {
				$this->addError($attribute, "Transaction is a duplicate", "error", "payPal.model.PPPhpTransaction");
				return;
			}
		}
	}

	/**
	 * Validate payment.
	 * 
	 * @param string $attribute mc_gross from PayPal
	 * @param array $params
	 */
	public function paymentValidator($attribute, $params) {
		if ($attribute < self::$amount)
			$this->addError($attribute, "Insufficient payment amount");
	}

	/**
	 * Save transaction to PHP file (Webroot/modules/payPal/data/transactions.php).
	 * 
	 * @return true on success, false on failure
	 */
	public function save() {
		
		Yii::log("SAVING TRANSACTION", "error", "payPal.controllers.ipn.PPIpnAction");
		if (!$this->validate()) {
			$logstr = print_r($this->getErrors(), true);
			Yii::log("ERRORS:".$logstr, "error", "payPal.controllers.ipn.PPIpnAction");
			return false;
		}

		self::addTransaction($this);
		$fn = Yii::app()->modulePath."/payPal/data/transactions.php";
		$content = "<?php\nreturn array(\n";
		foreach (self::getTransactions() as $v)
			$content .= "\tnew PPPhpTransaction('{$v->txnId}'),\n";
		$content .= ");\n?>";
		$fp = fopen($fn, 'w');
		fwrite($fp, $content);
		fclose($fp);
		
		return true;
	}

	/**
	 * If $this->transactions == null, all transactions are loaded by
	 * including a PHP file (see save()).
	 * @return array Transactions
	 */
	static public function getTransactions() {
		if (self::$transactions == null) {
			$fn = Yii::app()->modulePath."/payPal/data/transactions.php";
			self::$transactions = require($fn);
		}
		return self::$transactions;
	}

	/**
	 * @param PPPhpTransaction $txn
	 */
	static public function addTransaction($txn) {
		self::getTransactions();
		self::$transactions[] = $txn;
	}
}

?>
