<?php

ini_set('display_errors', 'On');
error_reporting(E_ALL);
/**
 * @todo Class Pin payment
 * @author Chienlv levanchien.it@gmail.com
 */
class Pin {

    protected $sevice_live = 'https://api.pin.net.au',
            $service_test = 'https://test-api.pin.net.au/1/charges',
            $secret_api_key = 'Fus2R6fzohTw-gp7uO3i-Q';

    /**
     * @link https://pin.net.au/docs/api/charges tutoria
     * @var array parameter 
     */
    public $charges_parameters = array(
        'amount',
        'currency' => 'USD',
        'description',
        'ip_address',
        'email',
        'card' => array(
            'number',
            'expiry_month',
            'expiry_year',
            'cvc',
            'name',
            'address_line1',
            'address_line2',
            'address_city',
            'address_postcode',
            'address_state',
            'address_country'
        )
    );

    protected function post() {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $this->service_test);
        curl_setopt($ch, CURLOPT_POST, sizeof($this->charges_parameters));
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($this->charges_parameters));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 3);
        curl_setopt($ch, CURLOPT_TIMEOUT, 20);
        curl_setopt($ch, CURLOPT_USERPWD, $this->secret_api_key);
        $result = curl_exec($ch);
        curl_close($ch);
        return $result;
    }

    public function test() {
        return $this->post();
    }

}

