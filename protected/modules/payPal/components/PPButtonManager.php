<?php
/**
 * Website Payments Standard Button Manager API (NVP)
 *
 * CREATED: 2010-10-24
 * UPDATED: 2010-10-28
 *
 * Every request/response is logged.
 *
 * @author Stian Liknes <stianlik@gmail.com>
 */
abstract class PPButtonManager extends CApplicationComponent {

	/**
	 * @param mixed $name PPButton if it exists, false otherwise
	 */
	abstract public function getButton($name);

	/**
	 * Create and return a new intance of the model class for
	 * PayPal buttons.
	 * 
	 * Structure of a PPButton-class:
	 * class PP<StorageType>Button {
	 *		public $name;
	 *		public $webSiteCode;
	 *		public $emailLink;
	 *		public $hostedButtonId;
	 *		public bool delete();
	 *		public bool save();
	 * }
	 *
	 * @return PPButton A instance of a class implementing the PPButton structure
	 */
	abstract protected function createNewButtonModel();

	/**
	 * Initialize component and load buttons from persistent storage.
	 */
	public function init() {
		parent::init();
	}

	/**
	 * Create a button hosted on PayPal.
	 *
	 * Required NPVs:
	 * BUTTONTYPE ::= BUYNOW | CART | GIFTCERTIFICATE | SUBSCRIBE | DONATE
	 *		| UNSUBSCRIBE | VIEWCART | PAYMENTPLAN | AUTOBILLING
	 *
	 * @param string $name Button name (used locally to track buttons)
	 * @param array $nvp Name-value-pairs (assoc array)
	 * @return mixed PPButton on success, false on failure
	 */
	public function createButton($name, $nvp = array('BUTTONTYPE'=>'BUYNOW')) {
		if (empty($name))
			return false;
		$nvp['METHOD'] = 'BMCreateButton';
		if (!($response = PPUtils::nvpRequest($nvp))) {
			self::log("Failed create button", "error", $nvp);
			return false;
		}
		$button = $this->createNewButtonModel();
		$button->name = $name;
		$button->webSiteCode = $response['WEBSITECODE'];
		$button->emailLink = $response['EMAILLINK'];
		$button->hostedButtonId = $response['HOSTEDBUTTONID'];
		if (!$button->save()) {
			self::log("Failed to save button", "error", $nvp);
			return false;
		}
		self::log("Successfully created button", "info", $nvp);
		return $button;
	}

	/**
	 * Modify a button that is hosted on PayPal. The buttons current values is
	 * fetched using $this->getButtonDetails and merged with $nvp (values in
	 * $nvp take precedence over values in $this->get>ButtonDetails).
	 *
	 * @param string $name Button name (used locally to track buttons)
	 * @param array $nvp Name-value-pairs (assoc array)
	 * @return mixed PPButton on success, false on failure
	 */
	public function updateButton($name, $nvp = array()) {
		if (empty($name) || !$this->getButton($name))
			return false;
		$btNvp = $this->getButtonDetails($name);
		$btNvp['METHOD'] = 'BMUpdateButton';
		foreach ($nvp as $k => $v)
			$btNvp[$k] = $v;
		if (!($response = PPUtils::nvpRequest($btNvp))) {
			self::log("Failed to update button", "error", $btNvp);
			return false;
		}
		$button = $this->getButton($name);
		$button->webSiteCode = $response['WEBSITECODE'];
		$button->emailLink = $response['EMAILLINK'];
		$button->hostedButtonId = $response['HOSTEDBUTTONID'];
		if (!$button->save()) {
			self::log("Failed save button", "error", $nvp);
			return false;
		}
		self::log("Successfully updated button", "info", $btNvp);
		return $button;
	}

	/**
	 * Change the status of a hosted button (currently only delete is supported).
	 *
	 * Required NVPs:
	 * BUTTONSTATUS ::= DELETE
	 * 
	 * @param string $name Button name (used locally to track buttons)
	 * @param array $nvp Name-value-pairs (assoc array)
	 * @return bool true on success, false on failure
	 */
	public function manageButtonStatus($name, $nvp = array('BUTTONSTATUS'=>'DELETE')) {
		if (empty($name) || !$this->getButton($name))
			return false;
		$button = $this->getButton($name);
		$nvp['METHOD'] = 'BMManageButtonStatus';
		$nvp['HOSTEDBUTTONID'] = $button->hostedButtonId;
		if (!($result = PPUtils::nvpRequest($nvp))) {
			$this->log("Problem managing button status", "error", $nvp);
			return false;
		}
		if ($nvp['BUTTONSTATUS'] === 'DELETE') {
			if (!$button->delete()) {
				$this->log("Unable to delete button.");
				return false;
			}
		}
		$this->log("Sucessfully managed button status", "info", $nvp, $result);
		return true;
	}

	/**
	 * Use the BMGetButtonDetails API operation to obtain information about a hosted Website
	 * Payments Standard button. You can use this information to set the fields that have not changed
	 * when updating a button.
	 *
	 * @param string $name Button name (used locally to track buttons)
	 * @return mixed $nvp Name-value-pairs (assoc array) on success, false on failure
	 */
	public function getButtonDetails($name) {
		if (empty($name) || !($button = $this->getButton($name)))
			return false;
		$nvp['METHOD'] = "BMGetButtonDetails";
		$nvp['HOSTEDBUTTONID'] = $button->hostedButtonId;
		$result = PPUtils::nvpRequest($nvp);
		if ($result)
			self::log("Successfully fetched button details", "info", $nvp, $result);
		else
			self::log("Failed to fetch button details", "error", $nvp);
		return $result;
	}

	/**
	 * Use the BMButtonSearch API operation to obtain a list of your hosted Website Payments
	 * Standard buttons. To access buttons created with this class you should use
	 * PPButtonManager::buttons in most cases.
	 * 
	 * Required NVPs:
	 * STARTDATE ::=  <Start date, example: '2009-08-24T05:38:48Z'>
	 *
	 * @param array $nvp Name-value-pairs (assoc array) (default: array('STARTDATE'=>gmdate('c')))
	 * @return mixed $nvp Name-value-pairs (assoc array) on success, false on failure
	 */
	public function buttonSearch($nvp = array()) {
		if (empty($nvp))
			$nvp['STARTDATE'] = gmdate('c');
		$nvp['METHOD'] = 'BMButtonSearch';
		$result = PPUtils::nvpRequest($nvp);
		if ($result)
			self::log("Successfully performed button search", "info", $nvp, $result);
		else
			self::log("Failed to perform button search", "error", $nvp);
		return $result;
	}

	/**
	 * Use the BMGetInventory API operation to determine the inventory levels and other
	 * inventory-related information for a button and menu items associated with the button.
	 * Typically, you call BMGetInventory to obtain field values before calling BMSetInventory
	 * to change the inventory levels
	 *
	 * @param string $name Button name (used locally to track buttons)
	 * @param array $nvp Name-value-pairs (assoc array)
	 * @return mixed $nvp Name-value-pairs (assoc array) on success, false on failure
	 */
	public function getInventory($name, $nvp = array()) {
		if (empty($name) || !($button = $this->getButton($name)))
			return false;
		$nvp['METHOD'] = 'BMGetInventory';
		$nvp['HOSTEDBUTTONID'] = $button->hostedButtonId;
		$result = PPUtils::nvpRequest($nvp);
		if ($result)
			self::log("Successfully performed getInventory call", "info", $nvp, $result);
		else
			self::log("Failed to perform getInventory call", "error", $nvp);
		return $result;
 	}

	/**
	 * Set the inventory level and inventory management features for the specified button.
	 * When you set the inventory level for a button, PayPal can track inventory,
	 * calculate the gross profit associated with sales, send you an alert
	 * when inventory drops below a specified quantity, and manage sold out conditions.
	 *
	 * Required NVPs:
	 * TRACKINV ::= 0 | 1
	 * TRACKPNL ::= 0 | 1
	 * ..Plus some additional information depending on your choice, see PayPal's documentation..
	 *
	 * Response NVP: TRANSACTIONID
	 *
	 * @param string $name Button name (used locally to track buttons)
	 * @param array $nvp Name-value-pairs (assoc array)
	 * @return mixed $nvp Name-value-pairs (assoc array) on success, false on failure
	 */
	public function setInventory($name, $nvp) {
		if (empty($name) || !($button = $this->getButton($name)))
			return false;
		$nvp['METHOD'] = 'BMSetInventory';
		$nvp['HOSTEDBUTTONID'] = $button->hostedButtonId;
		$result = PPUtils::nvpRequest($nvp);
		if ($result)
			self::log("Successfully performed setInventory call", "info", $nvp, $result);
		else
			self::log("Failed to perform setInventory call", "error", $nvp);
		return $result;
	}

	private static function log($msg, $level, $requestAr = null, $responseAr = null) {
		if ($requestAr != null)
			$msg .= "\nRequest: ".PPUtils::implode("&",$requestAr);
		if ($responseAr != null)
			$msg .= "\nResponse: ".PPUtils::implode("&",$responseAr);
		Yii::log($msg, $level, "payPal.components.PPButtonManager");
	}
}
?>
