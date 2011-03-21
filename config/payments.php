<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * CodeIgniter Payments
 *
 * An open source driver based payments system for Codeigniter 2.0+
 * 
 * Set all configurarion options below. When adding new drivers remember
 * to copy the same conventions as seen with the Paypal configuration 
 * settings below.
 *
 * @package       CI Payments
 * @category      Configuration
 * @author        Dwayne Charrington
 * @copyright     Copyright (c) 2011 Dwayne Charrington.
 * @link          http://ilikekillnerds.com
 * @license       http://ilikekillnerds.com/licence.txt
 */

/**
* Valid payment drivers.
* All drivers must be prefixed with the name payments_ 
* otherwise the drivers will not be loaded.
*
* @var mixed
*/
$config['valid_drivers'] = array
(
    "payments_paypal"
);

/**
* Default payment driver used to process site payments
*
* @var mixed
*/
$config['default_driver'] = "paypal";

/**
* Currency code
*
* @var mixed
*/
$config['currency_code'] = "AUD";

/**
* Paypal driver configuration settings
*
* @var mixed
*/
$config['paypal'] = array
(
    // test means use the sandbox, live means use in production
    'mode'          => 'test',
    
    // Default currency code for payments
    'currency_code' => 'AU',

    // Where payments are processed
    'gateway_url'   => 'https://www.paypal.com/cgi-bin/webscr',
    
    // For testing
    'sandbox_url'   => 'https://www.sandbox.paypal.com/cgi-bin/webscr',

    'success_url'   => 'payment/success',
    'failure_url'   => 'payment/failure',
    'notify_url'    => 'payment/validate',
    
    // Payment amount defaults to false
    'amt'    => FALSE,
    
    // Submit button for Paypal button
    'submit_button'    => 'Proceed to Paypal!',

    // PayPal API and username
    'user'      => NULL,
    'pwd'      => NULL,

    // PayPal API signature
    'signature'     => NULL,
    
    'debug'         => TRUE   
);

/**
* Google Checkout driver configuration settings
* No settings as of yet, because Google checkout
* is different to Paypal.
*
* @var mixed
*/
$config['googlecheckout'] = array
(
);