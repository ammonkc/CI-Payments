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

    // Gateway config
    protected $_config;

    // Fields of info for sending of payments
    protected $_fields = array();

    /**
    * Constructor
    *
    * @param mixed $params
    * @return Payments
    */
    public function Payments()
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

        // Get the config depending on what adapter we have
        $this->_config = $this->CI->config->item($this->_adapter);
    }

    /**
    * Sets payment field data
    *
    * @param mixed $fields
    */
    public function add_fields($fields = array())
    {
        foreach ($fields as $name => $value)
        {
            $this->_fields[$name] = $value;
        }
    }

    /**
    * Add a single payment field
    *
    * @param mixed $name
    * @param mixed $value
    */
    public function add_field($name, $value)
    {
        $this->_fields[$name] = $value;
    }

    /**
    * Process payment using Payment processing function
    * which will call the child driver process function
    * which will process the payment.
    *
    */
    public function process()
    {
        $this->{$this->_adapter}->process();
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

    /**
    * Adds a new config item to the _config array or
    * overwrites a previous value in the config array
    * if the value we are trying to add exists.
    *
    * @param mixed $driver
    * @param mixed $name
    * @param mixed $value
    */
    public function add_config_item($driver, $name, $value)
    {
        $this->_config[$driver][$name] = $value;
    }

}