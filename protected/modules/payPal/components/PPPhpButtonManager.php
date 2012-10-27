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
 * THIS SOFTWARE IS PROVIDED BY <COPYRIGHT HOLDER> ``AS IS'' AND ANY EXPRESS OR IMPLIED
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
 * PHP (buttons stored in a PHP-file) implementation of PPButtonManager.
 *
 * CREATED: 2010-10-24
 * UPDATED: 2010-10-28
 *
 * THIS SHOULD ONLY BE USED FOR INSTALLATION SCRIPTS AND SIMILAR, DYNAMIC
 * BUTTON MANAGING IS DONE USING THE DATABASE IMPLEMENTATION.
 *
 * File location: Yii::app()->modulePath."/payPal/data/buttons.php";
 *
 * @author Stian Liknes <stianlik@gmail.com>
 */
class PPPhpButtonManager extends PPButtonManager {
	public function getButton($name) {
		$buttons = PPPhpButton::getButtons();
		return isset($buttons[$name]) ? $buttons[$name] : false;
	}

	protected function createNewButtonModel() {
		return new PPPhpButton;
	}
}
?>
