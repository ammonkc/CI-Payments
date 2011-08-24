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
$config['valid_drivers'] = array("payments_paypal", "payments_authorize_aim");

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
$config['currency_code'] = "USD";

/**
* Paypal driver configuration settings
*
* @var mixed
*/
$config['paypal'] = array(
    // test means use the sandbox, live means use in production
    'mode'          => 'test',
    
    // Return method of which Paypal will respond with (2 is POST)
    'return_method' => '2',
    
    // Payment type default is: one click payment
    'payment_type' => '_xclick',
    
    // Default currency code for payments
    'currency_code' => 'US',

    // Where payments are processed
    'gateway_url'   => 'https://www.paypal.com/cgi-bin/webscr',
    
    // For testing
    'sandbox_url'   => 'https://www.sandbox.paypal.com/cgi-bin/webscr',

    'success_url'   => 'payment/success',
    'failure_url'   => 'payment/failure',
    'notify_url'    => 'payment/validate',
    
    // Payment amount defaults to false
    'amt'           => FALSE,
    
    // Submit button for Paypal button
    'submit_button' => 'Proceed to Paypal!',

    // PayPal API and username
    'user'          => NULL,
    'pwd'           => NULL,

    // PayPal API signature
    'signature'     => NULL,
    
    'debug'         => TRUE   
);

/**
* Authorize.net AIM api driver configuration
*
* @var mixed
*/
$config['authorize_net'] = array(
    'test_mode'            => 'TRUE',  // Set this to FALSE for live processing
    
    // Test server
    'test_x_login'         => '', // Authorize.net developer login
    'test_x_tran_key'      => '', // Authorize.net developer transaction key
    'test_api_host'        => 'https://test.authorize.net/gateway/transact.dll',
    
    // Live server
    'live_x_login'         => '', // Authorize.net live login
    'live_x_tran_key'      => '', // Authorize.net live transaction key
    'live_api_host'        => 'https://secure.authorize.net/gateway/transact.dll',
    
    // Transaction details
    'x_version'            => '3.1',
    'x_type'               => 'AUTH_CAPTURE',
    'x_relay_response'     => 'FALSE',
    'x_delim_data'         => 'TRUE',
    'x_delim_char'         => '|',
    'x_encap_char'         => '',
    'x_url'                => 'FALSE',
    'x_method'             => 'CC',
    'x_currency_code'      => 'USD',
    'x_duplicate_window'   => '120',
);

/**
* Google Checkout driver configuration settings
* No settings as of yet, because Google checkout
* is different to Paypal.
*
* @var mixed
*/
$config['googlecheckout'] = array(
    
);