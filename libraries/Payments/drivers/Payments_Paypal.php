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
 
class Payments_Paypal extends CI_Driver {
    
    protected $_driver_fields = array(
        'paypal_email' => ''
    );
    
    protected $test_mode = TRUE;

    public function __construct()
    {
        parent::set_gateway('https://www.paypal.com/cgi-bin/webscr');   
    }
    
    public function _add_field($field, $value)
    {
        $this->fields[$field] = $value;
    }

}