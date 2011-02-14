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
    
    // Valid child drivers
    protected $valid_drivers = array(
        'paypal',
        'googlecheckout'
    );
    
    // Default payment driver
    protected $_adapter = "paypal";
    
    // Fields of info for sending of payments
    protected $_fields = array();
    
    // Payment processor result
    protected $_result = '';
    
    /**
    * Constructor
    * 
    * @param mixed $params
    * @return Payments
    */
    public function __construct()
    {
    }
    
    /**
    * Sets payment field data
    * 
    * @param mixed $fields
    */
    public function set_fields($fields = array())
    {
        foreach ($fields as $name => $value)
        {
            $this->_fields[$name] = $value;
        }
    }
    
    /**
    * Process payment using Payment processing function
    * 
    */
    public function process()
    {
        $this->{$this->_adapter}->process();
    }

}