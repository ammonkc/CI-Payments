<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * CodeIgniter Payments
 *
 * An open source driver based payments system for Codeigniter 2.0+.
 * 
 * This class is the Paypal driver which controls payments handling via
 * Paypal IPN (Instant Payment Notifications).
 *
 * @package       CI Payments
 * @subpackage    Paypal
 * @category      Driver
 * @author        Dwayne Charrington
 * @copyright     Copyright (c) 2011 Dwayne Charrington.
 * @link          http://ilikekillnerds.com
 * @license       http://ilikekillnerds.com/licence.txt
 */

class Payments_paypal extends CI_Driver {
    
    // Codeigniter instance
    protected $CI;
    
    // Response from Paypal
    protected $_response;
    
    // Fields of data to send off to Paypal
    protected $_fields;
    
    // Configuration settings for Paypal driver
    protected $_config;
    
    /**
    * Constructor
    * 
    */
    public function __construct()
    {
        $this->CI = get_instance();
        
        // Load our Payments config file
        $this->CI->load->config('payments');
        
        // Store settings for this gateway into the class variable _config
        $this->_config = config_item('paypal');
        
        /**
        * Set some default Paypal fields. These can be overwritten by passing
        * through new values to the process function
        */
        
        // Return method
        $this->_fields['rm']  = $this->config_item('return_method');
        
        // Type of payment this is
        $this->_fields['cmd'] = $this->config_item('payment_type');
        
        // Currency code for this payment
        $this->_fields['currency_code'] = $this->config_item('currency_code');
        
        // No note along with the payment
        $this->_fields['no_note'] = "1";
        
        // Upon successful payment
        $this->_fields['return'] = $this->config_item('success_url');
        
        // Failure URL
        $this->_fields['cancel_return'] = $this->config_item('failure_url');
        
        // IPN notification url
        $this->_fields['notify_url']    = $this->config_item('notify_url');
        
    }
    
    /**
    * Config Item
    * Just a shortcut function so you don't have to write
    * $this->_config['itemname']
    * 
    * @param mixed $name
    */
    public function config_item($name)
    {
        if ( isset($this->_config[$name]) )
        {
            return $this->_config[$name]; 
        }
        else
        {
            return FALSE;
        }
    }

    /**
    * Process the Payment (send off to Paypal)
    * 
    * @param mixed $fields
    */
    public function process($fields = array())
    {
        $this->CI->load->helper('form');
        
        foreach ($fields AS $name => $value)
        {
            $this->_fields[$name] = $value;
        }
        
        // View data
        $data['page_title'] = "Processing Paypal payment..";
        $data['page_heading'] = "Preparing Transaction";
        $data['gateway_url'] = $this->get_gateway_url();
        $data['fields'] = $this->_fields;
        $data['submit_button'] = $this->config_item("submit_button");

        // Load our processing view
        $this->load->view('payments/paypal/processing.php', $data);
    }

    /**
    * Validates the response from Paypal
    *
    */
    public function callback()
    {
        $gateway_url = $this->get_gateway_url();
        $url_parsed  = parse_url($gateway_url);

        $post_string = '';

        // If we have post data
        if ($_POST)
        {
            log_message('debug', 'IPN data posted: '.$_POST);
            foreach ($_POST as $key => $val)
            {
                $this->_response[$key] = $val;
                $post_string .= $key.'='.urlencode(stripslashes($val)).'&';
            }
        }

        $post_string .= "cmd=_notify-validate";

        if ($this->_response['payment_status'] !== 'Completed')
        {
            return FALSE;
        }

        $curl = curl_init();
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_HEADER , 0);
        curl_setopt($curl, CURLOPT_VERBOSE, 1);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($curl, CURLOPT_TIMEOUT, 30);
        curl_setopt($curl, CURLOPT_URL, $gateway_url);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $post_string);
        curl_setopt($curl, CURLOPT_HTTPHEADER, array("Content-Type: application/x-www-form-urlencoded", "Content-Length: " . strlen($post_string)));
        $result = curl_exec($curl);

        if ($result === "VERIFIED")
        {
            return $this->_response;
        }
        else
        {
            return FALSE;
        }
    }

    /**
    * Debug field data to make sure everything you've added
    * is in its right place.
    *
    * Credit is where credit due. The basis of this function
    * was lifted from Pankcake Payments. Such a good idea,
    * and really liked how it presents the data.
    *
    */
    public function debug_fields()
    {
        ksort($this->_fields);
        echo '<h3>Paypal Fields</h3>'."\n";
        echo '<pre>'."\n";
        print_r($this->_fields);
        echo "</pre>\n";
        return;
    }

    /**
    * Debug response data from Paypal to see what Paypal is sending
    * you and to make sure everything is being received properly.
    *
    * Credit is where credit due. The basis of this function
    * was lifted from Pankcake Payments. Such a good idea,
    * and really liked how it presents the data.
    *
    */
    public function debug_response()
    {
        ksort($this->_response);
        echo '<h3>Paypal Reponse</h3>'."\n";
        echo '<pre>'."\n";
        print_r($this->_response);
        echo "</pre>\n";
        return;
    }
    
    /**
    * Get Gateway URL
    * Returns the Paypal API URL to use
    * 
    */
    private function get_gateway_url()
    {
        return ($this->config_item('mode') == 'test') ? $this->config_item('sandbox_url') : $this->config_item('gateway_url');
    }
    
    // Read somewhere that you need this function for Codeigniter drivers...
    public function decorate() {}

}