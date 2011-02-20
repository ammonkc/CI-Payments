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

    /**
    * Constructor
    *
    * @param mixed $params
    * @return Payments
    */
    public function __construct()
    {
        $this->CI =& get_instance();

        // Load our config file
        $this->CI->load->config('payments');

        // Get valid drivers
        foreach ($this->CI->config->item('valid_drivers') as $driver)
        {
            $this->valid_drivers[] = $driver;
        }

        // Get default driver
        $this->_adapter = $this->CI->config->item('default_driver');
    }

    /**
    * Process payment using Payment processing function
    * which will call the child driver process function
    * which will process the payment.
    *
    */
    public function process($fields = array())
    {
        $this->{$this->_adapter}->process($fields);
    }

    /**
    * Some payment processors like Paypal send back data via POST depending
    * whether or not the transaction was successfull. Not all payment gateways
    * will have a callback, but it's here in-case.
    *
    */
    public function callback()
    {
        return $this->{$this->_adapter}->callback();
    }

}