<?php
/**
 * Created by PhpStorm.
 * User: toantv
 * Date: 24/10/2014
 * Time: 13:53
 */

class PinPaymentForm extends CFormModel
{
    public $name;
    public $email;
    public $number;
    public $description;
    public $expiry_month;
    public $expiry_year;
    public $address_line1;
    public $address_line2;
    public $address_city;
    public $address_state;
    public $address_postcode;
    public $address_country;
    public $cvc;

    public function rules() {
        return array(
            array('name, number, address_line1, address_city, cvc, description, address_country, email, expiry_month, expiry_year', 'required'),
            array('email', 'email'),
            array('cvc, address_postcode', 'numerical')
        );
    }

    /**
     * Send data to pin.net.au
     */
    public function pinPayMent()
    {
        $pin = new Pin();
        $pin->charges_parameters = array(
            'amount' => sprintf('%d00', $_POST['amount']),
            'currency' => 'USD',
            'description' => $this->description,
            'email' => $this->email,
            'ip_address' => Yii::app()->request->userHostAddress,
            'card' => array(
                'number' => $this->number,
                'expiry_month' => $this->expiry_month,
                'expiry_year' => $this->expiry_year,
                'cvc' => $this->cvc,
                'name' => $this->name,
                'address_line1' => $this->address_line1,
                'address_line2' => $this->address_line2,
                'address_city' => $this->address_city,
                'address_postcode' => $this->address_postcode,
                'address_state' => $this->address_state,
                'address_country' => $this->address_country
            )
        );
        return json_decode($pin->test(),true);
    }
}