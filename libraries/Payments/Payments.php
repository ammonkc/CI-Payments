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
    
    // URL where payments are processed
    protected $payment_api = "";
    
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
    
    function payment_form($form_name='')
    {
        if ($form_name == '')
        {
            return FALSE;
        }
        
        $str = '';
        $str .= '<form method="post" action="'.$this->payment_api.'" name="'.$form_name.'"/>' . "\n";
        foreach ($this->fields as $name => $value)
                $str .= "<input type='hidden' name='".$name."' value='".$value."' />" . "\n";
        $str .= '<p>'. "<button type='submit' id='submit_btn'>Submit</button>" . '</p>';
        $str .= "</form>" . "\n";

        return $str;
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