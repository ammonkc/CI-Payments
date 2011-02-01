<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * CodeIgniter Payments
 *
 * An open source driver based payments system for Codeigniter
 *
 * @package       CI Payments
 * @author        Dwayne Charrington
 * @copyright     Copyright (c) 2011 Dwayne Charrington.
 * @link          http://ilikekillnerds.com
 */
 
class Payments extends CI_Driver_Library {

    protected $valid_drivers = array(
        'paypal', 'googlecheckout'
    );
    
    public function __construct($params = array())
    {
    }
    
    /**
    * All payment providers need some kind of API key to process payments.
    * 
    * @param mixed $api_key
    */
    function set_apikey($api_key)
    {
        
    }

}