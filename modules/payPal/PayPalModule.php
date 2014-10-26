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
 * PPExt - PayPal extension
 *
 * CREATED: 2010-10-23
 * UPDATED: 2010-10-28
 *
 * This is a thin, event based wrapper for PayPal PDT, IPN- and button manager
 * APIs.
 *
 * Features:
 * - Manage buttons (PPButtonManager)
 * - Process PDT events (PPPdtAction)
 * - Process IPN events (PPInpAction)
 * - Logging via Yii::log()
 *
 * USAGE:
 *
 * 1. Copy the folder "payPal" into WebRoot/modules/payPal
 *
 * 2. Edit WebRoot/testdrive/protected/config/main.php and add
 * the following:
 *
 * 'modules' => array(
 *	...
 *		'payPal'=>array(
 *			'env' => 'sandbox',
 *			'account'=>array(
 *					'username'=>'Your PayPal API username',
 *					'password'=>'Your PayPal API password',
 *					'signature'=>'Your PayPal API signature',
 *					'email'=>'Your PayPal email address',
 *					'identityToken'=>'Your PayPal identity token',
 *			),
 *			'components'=>array(
 *				'buttonManager'=>array(
 *					//'class'=>'payPal.components.PPDbButtonManager'
 *					'class'=>'payPal.components.PPPhpButtonManager',
 *				),
 *			),
 *		),
 *	...
 *  ),
 *
 * 3. Take a look at WebRoot/modules/payPal/controllers/DefaultController
 * for an example on IPN and PDT listeners (payment processing).
 *
 * 4. Take a look at WebRoot/modules/payPal/controllers/TestController for
 * an example on the button manager (actually just test code, but it should
 * give you a good idea of how it works).
 *
 * All examples are tested and functional in the sandbox, unless otherwise stated
 * in their class / file description.
 *
 * Most classes and functions are documented using PHPDoc.
 *
 * FILE STRUCTURE
 *
 * payPal
 *		PayPalModule.php - Binds the module together
 *			components
 *				PPButtonManager.php	- Abstract PayPal button manager class
 *				PPPhpButtonManager.php - Implementation using PHP for storage
 *				PPDbButtonManager.php - Database implementation of PPButtonManager
 *				PPEvent.php - Used to pass onSuccess, onFailure and onRequest events
 *				PPUtils.php - Misc. helper methods (httpPost, httpGet, etc.)
 *			controllers
 *				PPDefaultController.php - IPN and PDT example (PHP >= 5.3)
 *				PPDefaultLegacyController.php - IPN and PDT example (PHP >= 5.2)
 *				PPTestController.php - PayPal button manager examples / tests
 *				ipn
 *					PPIpnAction.php	- IPN handler
 *				pdt
 *					PPPdtAction.php - PDT handler
 *			models
 *				PPAccount.php - PayPal account configuration (username, etc.)
 *				PPPhpButton.php - PayPal button model (php storage)
 *				PPDbButton.php - PayPal button model (database)
 *				PPPhpTransaction.php - PayPal transaction model (for examples and testing only)
 *
 * @author Stian Liknes <stianlik@gmail.com>
 */
class PayPalModule extends CWebModule
{
	/**
	 * PayPal API version
	 */
	const VERSION = "63.0";

	/**
	 * Environment:
	 * - Testing		= "sandbox"
	 * - Production		= ""
	 * @var string 
	 */
	public $env = "sandbox"; // Set to "" to get out of the sandbox
	
	/**
	 * Account details used in communication with PayPal
	 * @var PPAccount
	 */
	public $account;

	public function init()
	{
		$this->setImport(array(
			'payPal.models.*',
			'payPal.components.*',
			'payPal.controllers.pdt.*',
			'payPal.controllers.ipn.*',
		));

		// Create account
		if (is_array($this->account)) {
			$account = new PPAccount;
			$account->setAttributes($this->account,false);
			$this->account = $account;
		}
	}

	public function beforeControllerAction($controller, $action)
	{
		if(parent::beforeControllerAction($controller, $action))
		{
			return true;
		}
		else
			return false;
	}
}
