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
        $this->add_field('no_note', '1');
        $this->add_field('return', 'payment/success');
        $this->add_field('cancel_return', 'payment/fail');
        $this->add_field('notify_url', 'payment/notify');

        $this->_driver_fields['submit_button'] = "Please pay now";
    }

    public function process()
    {
        $str = '<h2>Preparing Transaction</h2>
            <p style="text-align:center;"><img src="' . base_url() . 'img/slimbox/loading.gif" alt="loading" /></p>
            <p style="text-align:center;">Please wait while your order is being processed.<br />You will be redirected to the paypal website.</p>
            <p style="text-align:center;">If your browser does not redirect you, please<br />click the Continue button below to proceed.</p>
            <form id="paypal" method="post" action="'.$this->_api_config['gateway_url'].'"><p style="text-align:center;padding-top:20px;">' . "\n";

        foreach ($this->_fields as $name => $value)
        {
            $str .= '<input type="hidden" name="' . $name . '" value="' . $value . '" />' . "\n";
        }
        $str .= '<input type="submit" value="'.$this->_api_config["submit_button"].'" /></p></form>';
        return $str;
    }

}