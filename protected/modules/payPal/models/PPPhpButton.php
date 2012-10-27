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
 * @todo Validation and exception handling.
 *
 * Used by PPPhpButtonManager.
 *
 * CREATED: 2010-10-24
 * UPDATED: 2010-10-26
 *
 * @author Stian Liknes <stianlik@gmail.com>
 */
class PPPhpButton extends CModel {
	private static $buttons = null;
	public $name = "default";
	public $webSiteCode;
	public $emailLink;
	public $hostedButtonId;

	public function  __construct($name = null, $webSiteCode = null,
			$emailLink = null, $hostedButtonId = null) {
			$this->name = $name;
			$this->webSiteCode = $webSiteCode;
			$this->emailLink = $emailLink;
			$this->hostedButtonId = $hostedButtonId;
	}

	public function attributeNames() {
		return array(
			'name',
			'webSiteCode',
			'emailLink',
			'hostedButtonId',
		);
	}

	public function save() {
		self::addButton($this);
		return self::write();
	}

	public function delete() {
		self::getButtons();
		unset(self::$buttons[$this->name]);
		return self::write();
	}

	public static function getButtons() {
		if (self::$buttons == null) {
			$fn = Yii::app()->modulePath."/payPal/data/buttons.php";
			self::$buttons = require($fn);
		}
		return self::$buttons;
	}

	public static function addButton($button) {
		self::getButtons();
		self::$buttons[$button->name] = $button;
	}

	private static function write() {
		$fn = Yii::app()->modulePath."/payPal/data/buttons.php";
		$content = "<?php\nreturn array(\n";
		foreach (self::getButtons() as $k => $v) {
			$wsc = preg_replace('/\n/', '\\n',$v->webSiteCode);
			$wsc = preg_replace('/"/', '\"',$wsc);
			$content .= "\t'$v->name'=>new PPPhpButton(\n" .
					"\t\t\"{$v->name}\",\n" .
					"\t\t\"{$wsc}\",\n" .
					"\t\t\"{$v->emailLink}\",\n" .
					"\t\t\"{$v->hostedButtonId}\"\n" .
				"\t),\n";
		}
		$content .= ");\n?>";
		if (!($fp = fopen($fn, 'w')))
			return false;
		$result = fwrite($fp, $content);
		return fclose($fp) && $result;
	}
}

?>
