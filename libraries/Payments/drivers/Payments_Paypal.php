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
    );

    public function __construct()
    {
          
    }

}