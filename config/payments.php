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
    "paypal",
    "googlecheckout"
);

/**
* Default payment driver used to process site payments
*
* @var mixed
*/
$config['default_driver'] = "paypal";

/**
* Paypal driver configuration settings
*
* @var mixed
*/
$config['paypal'] = array
(
    // Where payments are processed
    'gateway_url'   => 'https://www.paypal.com/cgi-bin/webscr',

    // Currency code
    'currency_code' => 'AU',

    // PayPal API and username
    'username'      => NULL,
    'password'      => NULL,

    // PayPal API signature
    'signature'     => NULL,

    // PayPal environment: live, sandbox, beta-sandbox
    'environment'   => 'sandbox',
);