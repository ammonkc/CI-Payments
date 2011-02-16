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

    // cURL instance
    protected $_curl;

    // Gateway config
    protected $_config;

    // Fields of info for sending of payments
    protected $_fields = array();

    // Payment processor result
    protected $_result = '';

    // Last error we had
    protected $last_error = '';

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

}