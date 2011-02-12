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
    
    // Codeigniter superobject
    protected $CI;
    
    // Gateway config options
    protected $_gateway_config = array(
        'api_key'     => '',
        'gateway_url' => ''
    );
    
    // Information passed to third party provider
    protected $_fields = array(
        'card_number'   => '',
        'expiry_date'   => '',
        'cvn_code'      => '',
        'first_name'    => '',
        'last_name'     => '',
        'total_amount'  => '',
        'currency_code' => 'AUD'
    );
    
    // Test mode means we're not really processing payments
    protected $test_mode;
    
    /**
    * Constructor
    * 
    * @param mixed $params
    * @return Payments
    */
    public function __construct($params = array())
    {
        $this->valid_drivers[] = 'paypal';
        $this->valid_drivers[] = 'googlecheckout';
    }
    
    /**
    * Set the payment gateway url
    * 
    * @param mixed $gateway_url
    */
    public function set_gateway($gateway_url)
    {
        $this->_gateway_config['gateway_url'] = trim($gateway_url);
    }
    
    /**
    * All payment providers need some kind of API key to process payments.
    * 
    * @param mixed $api_key
    */
    public function set_apikey($api_key)
    {
        $this->$this->_gateway_config['api_key'] = trim($api_key);
    }
    
    /**
    * The total amount to charge the user
    * 
    * @param mixed $total
    */
    public function set_total($total)
    {
        $this->_fields['total_amount'] = trim($total);
    }
    
    /**
    * Process a payment
    * 
    */
    public function process()
    {
        
    }

}