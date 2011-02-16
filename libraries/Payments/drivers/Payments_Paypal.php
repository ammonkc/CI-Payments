<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * CodeIgniter Payments
 *
 * An open source driver based payments system for Codeigniter
 *
 * @package       CI Payments
 * @subpackage    Paypal
 * @author        Dwayne Charrington
 * @copyright     Copyright (c) 2011 Dwayne Charrington.
 * @link          http://ilikekillnerds.com
 */

class Payments_Paypal extends CI_Driver {

    // Driver specific fields
    protected $_driver_fields = array
    (
        // PayPal API and username
        'username' => NULL,
        'password' => NULL,

        // PayPal API signature
        'signature' => NULL,

        // PayPal environment: live, sandbox, beta-sandbox
        'environment' => 'sandbox',

        // Text on the payment button
        'submit_button' => 'Pay Now'

    );

    public function __construct()
    {
        $this->_api_config['gateway_url'] = "https://www.paypal.com/cgi-bin/webscr";

        // Default payment fields
        $this->add_field('rm','2'); // Return method is POST
        $this->add_field('cmd','_xclick');

        $this->add_field('currency_code', $this->_api_config['currency_code']);
        $this->add_field('quantity', '1');

        $this->_driver_fields['submit_button'] = "Please pay now";
    }

}