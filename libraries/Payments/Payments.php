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
    
    // Default payment driver
    protected $default_driver;
    
    /**
    * Constructor
    * 
    * @param mixed $params
    * @return Payments
    */
    public function __construct()
    {
        // Load payments config
        $this->CI->load->config('payments');
        
        // Get valid drivers
        foreach ($this->ci->config->item('valid_drivers') AS $driver)
        {
            $this->valid_drivers[] = $driver;   
        }
        
        // Get the default payment driver
        $this->default_driver = $this->ci->config->item('default_driver');
    }
    
    /**
    * Process payment
    * 
    */
    public function process()
    {
        
    }

}