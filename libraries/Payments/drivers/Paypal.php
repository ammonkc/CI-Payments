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
 
class Paypal extends CI_Driver {
    
    protected $fields      = array();
    protected $process_url = "https://www.paypal.com/cgi-bin/webscr";

    public function __construct()
    {
        
    }
    
    public function add_field($field, $value)
    {
        $this->fields[$field] = $value;
    }

}