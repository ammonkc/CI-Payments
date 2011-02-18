<?php

/**
* Payment module configuration options
*/

/**
* Valid payment drivers.
*
* @var mixed
*/
$config['valid_drivers'] = array
(
    "paypal"
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
$config['currency_code'] = "AU";

/**
* Paypal driver configuration settings
*
* @var mixed
*/
$config['paypal'] = array
(
    // test means use the sandbox, live means use in production
    'mode'          => 'test',

    // Where payments are processed
    'gateway_url'   => 'https://www.paypal.com/cgi-bin/webscr',
    
    // For testing
    'sandbox_url'   => 'https://www.sandbox.paypal.com/cgi-bin/webscr',

    'success_url'   => 'payment/success',
    'failure_url'   => 'payment/failure',
    'notify_url'    => 'payment/validate',

    // PayPal API and username
    'username'      => NULL,
    'password'      => NULL,

    // PayPal API signature
    'signature'     => NULL,
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