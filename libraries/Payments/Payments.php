<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * CodeIgniter Payments
 *
 * An open source driver based payments system for Codeigniter 2.0+. 
 * 
 * This is the main driver parent class that takes care of setting up 
 * the valid drivers and calling child functions depending on what the 
 * default class is set too.
 *
 * @package       CI Payments
 * @category      Parent driver
 * @author        Dwayne Charrington
 * @copyright     Copyright (c) 2011 Dwayne Charrington.
 * @link          http://ilikekillnerds.com
 * @license       http://ilikekillnerds.com/licence.txt
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
        $this->CI = get_instance();

        // Load our config file
        $this->load->config('payments');

        // Get valid drivers
        foreach ($this->config->item('valid_drivers') as $driver)
        {
            $this->valid_drivers[] = $driver;
        }

        // Get default driver
        $this->_adapter = $this->config->item('default_driver');
    }
    
    /**
    * This function lets us access Codeigniter instance objects like;
    * helpers, libraries and core functions without having to prefix
    * our faux Codeigniter instance variable 'CI' we can load Codeigniter
    * libraries and other goodness like we would normally within controllers
    * and other things.
    * 
    * @param mixed $bleh
    */
    public function __get($bleh)
    {
        return $this->CI->$bleh;
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
    * Some payment processors like Paypal send back data via POST or GET depending
    * whether or not the transaction was successfull. Not all payment gateways
    * will have a callback, but it's here in-case.
    *
    */
    public function callback()
    {
        return $this->{$this->_adapter}->callback();
    }

}