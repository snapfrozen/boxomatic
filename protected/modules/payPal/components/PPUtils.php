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
 * Utility methods for the PayPal module. Mainly used for HTTP requests.
 *
 * CREATED: 2010-10-25
 * UPDATED: 2010-10-28
 *
 * @author Stian Liknes <stianlik@gmail.com>
 */
class PPUtils
{
	const NVP = 0, PDT = 1, IPN = 2;
	
	/**
	 * Implode assoc array into a string in ..key1=value1&key2=value2.. form.
	 * 
	 * @param array $pieces Assoc array
	 * @return string String in ..key1=value1&key2=value2... form
	 */
	public static function implode($glue, $pieces) {
		if (count($pieces) < 1)
			return "";
		$keys = array_keys($pieces);
		$str = $keys[0]."=".$pieces[$keys[0]];
		for ($i=1;$i<count($keys);$i++)
			$str .= "$glue{$keys[$i]}={$pieces[$keys[$i]]}";
		return $str;
	}

	/**
	 * URL-encode all keys and values in assoc array.
	 * 
	 * @param array $arr Assoc array
	 * @return array URL-encoded assoc array
	 */
	public static function urlencode($arr) {
		$result = array();
		foreach ($arr as $k => $v)
			$result[urlencode($k)] = urlencode($v);
		return $result;
	}

	/**
	 * Send a HTTP GET request and return the response. Do not include "?" in
	 * the url, all get variables should be placed in $getVars.
	 * 
	 * @param string $url Destination URL
	 * @param string $getVars String in the form var1=val1&var2=val2&...
	 * @param bool $parsed true to parse the response into assoc array
	 * @return array array("status"=>bool,"error_msg"=>string,"error_no"=>string) on failure,
	 *		array("status"=>true,"httpResponse"=>string) on success if $parsed is false, or
	 *		array("status"=>true,"httpParsedResponseAr"=>array) on success if $parsed is true.
	 */
	public static function httpGet($url, $getVars ="", $parsed=true) {
		// Send request
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, "$url?$getVars");
		curl_setopt($ch, CURLOPT_VERBOSE, true);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		$httpResponse = curl_exec($ch);

		$result = null;

		// Return error if the request failed
		if(!$httpResponse) {
			$result = array("status" => false, "error_msg" => curl_error($ch), "error_no" => curl_errno($ch));
		}

		// Return unparsed response if $parsed = false
		else if(!$parsed) {
			$result = array("status" => true, "httpResponse" => urldecode($httpResponse));
		}

		// Otherwise, return parsed response
		else {
			$httpResponseAr = explode("&", $httpResponse);
			$httpParsedResponseAr = array();
			foreach ($httpResponseAr as $value) {
				$tmpAr = explode("=", $value);
				if(sizeof($tmpAr) > 1) {
					$httpParsedResponseAr[urldecode($tmpAr[0])] = urldecode($tmpAr[1]);
				}
			}
			if(count($httpParsedResponseAr) == 0) {
				$error = "Invalid HTTP Response for GET request($getVars) to $url_.";
				$result = array("status" => false, "error_msg" => $error, "error_no" => 0);
			}
			else
				$result = array("status" => true, "httpParsedResponseAr" => $httpParsedResponseAr);
		}
		curl_close($ch);
		return $result;
	}

	/**
	 * Send a HTTP POST request and return the response. All post variables must
	 * be placed in $postVars. When parsed is true, only lines on key=value form is
	 * returned, others are discarded.
	 *
	 * @param string $url Destination URL
	 * @param mixed $postVars Assoc array, or string in the form ..key=value&..
	 * @param bool $parsed true to parse the response into assoc array
	 * @return array array("status"=>bool,"error_msg"=>string,"error_no"=>string) on failure,
	 *		array("status"=>true,"httpResponse"=>string) on success if $parsed is false, or
	 *		array("status"=>true,"httpResponseAr"=>array) on success if $parsed is true.
	 */
	public static function httpPost($url, $postVars, $parsed)
	{
		// Send request
		$ch = curl_init();
		curl_setopt($ch,CURLOPT_URL,$url);
		curl_setopt($ch,CURLOPT_VERBOSE,true);
		curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
		curl_setopt($ch,CURLOPT_POST,true);
		curl_setopt($ch,CURLOPT_POSTFIELDS,$postVars);
		$httpResponse = curl_exec($ch);

		//turning off the server and peer verification(TrustManager Concept).
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);

		$result = null;

		// Return error if the request failed
		if(!$httpResponse) {
			$result = array("status" => false, "error_msg" => curl_error($ch), "error_no" => curl_errno($ch));
		}

		// Return unparsed response if $parsed = false
		else if(!$parsed) {
			$result = array("status" => true, "httpResponse" => $httpResponse);
		}

		// Otherwise, return parsed response
		else {
			$httpResponseAr = explode("\n", $httpResponse);
			$httpParsedResponseAr = array();
			foreach ($httpResponseAr as $value) {
				$tmpAr = explode("=", $value);
				if(count($tmpAr) > 1) {
					$httpParsedResponseAr[urldecode($tmpAr[0])] = urldecode($tmpAr[1]);
				}
			}
			if(count($httpParsedResponseAr) == 0) {
				$postStr = self::implode("&",$postVars);
				$error = "Invalid HTTP Response for POST request($postStr) to $url.";
				$result = array("status" => false, "error_msg" => $error, "error_no" => 0);
			}
			else
				$result = array("status" => true, "httpParsedResponseAr" => $httpParsedResponseAr);
		}
		curl_close($ch);
		return $result;
	}

	/**
	 * Send a HTTP GET request to PayPal's NVP API and return the response.
	 * Settings from Yii::app()->getModule('payPal') is used for authentication
	 * and API version.
	 *
	 * Get variables are urlencoded, and imploded using self::urlencode() and
	 * self::implode().
	 *
	 * Failure ACKs received from PayPal is logged.
	 *
	 * @param array $getVars Assoc array (NVP values as specified by PayPal NVP API)
	 * @return mixed Assoc array on success, false on failure
	 * @throws CException Unrecognized response
	 */
	public static function nvpRequest($getVars) {
		$module = Yii::app()->getModule('payPal');

		// Prepare NVP
		$getVars['VERSION'] = PayPalModule::VERSION;
		foreach ($module->account->getNvp() as $k => $v)
			$getVars[$k] = $v;

		// Sent HTTP GET request
		$getStr = self::urlencode($getVars);
		$getStr = self::implode("&",$getStr);
		$response = PPUtils::httpGet(self::getUrl(self::NVP), $getStr, true);

		// Return false on HTTP error
		if ($response['status'] === false)
			return false;

		$ack = $response['httpParsedResponseAr']['ACK'];

		// Log and return false on PayPal failures / warnings
		if ($ack == "Failure" || $ack == "FailureWithWarning" || $ack == "Warning") {
			Yii::log("NVP request failed" .
				"\nRequest:$getStr" .
				"\nResponse:".self::implode("&", $response['httpParsedResponseAr']),
				"error", "payPal.helpers.PPUtils");
			return false;
		}

		// Return response on success
		if ($ack == "Success" || $ack == "SuccessWithWarning")
			return $response['httpParsedResponseAr'];

		// Log error and throw exception on unrecognized response
		$message = "Received unrecognized response";
		Yii::log($message .
			"\nRequest:$getStr" .
			"\nResponse:".self::implode("&", $response['httpParsedResponseAr']),
			"error", "payPal.helpers.PPUtils");
		throw new CException($message);
	}

	/**
	 * @param int $service PPUtils::NVP | PPUtils::PDT | PPUtils::IPN
	 * @return mixed URL if service exists, false otherwise
	 */
	public static function getUrl($service) {
		$module = Yii::app()->getModule('payPal');
		$env = empty($module->env) ? "" : "{$module->env}.";
		switch($service) {
			case self::NVP:
				return "https://api-3t.{$env}paypal.com/nvp";
			case self::IPN:
				return "https://www.{$env}paypal.com/cgi-bin/webscr";
			case self::PDT:
				return "https://www.{$env}paypal.com/cgi-bin/webscr";
			default:
				return false;
		}
	}
}
?>