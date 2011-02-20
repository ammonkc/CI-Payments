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

class Payments_paypal extends CI_Driver {

    protected $CI;
    protected $_response;
    protected $_fields = array();
    protected $_config = array();

    public function __construct()
    {
        $this->CI =& get_instance();
        
        $this->CI->load->config('payments');
        
        $this->_config = $this->CI->config->item('paypal');
        
        // Return method is POST
        $this->_fields['rm']  = '2';
        
        // Type of payment this is
        $this->_fields['cmd'] = "_xclick";
        
        // Currency code for this payment
        $this->_fields['currency_code'] = $this->_config['currency_code'];
        
        // No note along with the payment
        $this->_fields['no_note'] = "1";
        
        // Upon successful payment
        $this->_fields['return'] = $this->_config['success_url'];
        
        // Failure URL
        $this->_fields['cancel_return'] = $this->_config['failure_url'];
        
        // IPN notification url
        $this->_fields['notify_url']    = $this->_config['notify_url'];
        
    }
    
    public function decorate() {}

    /**
    * Processes the payment. This will display a form which will then redirect to Paypal.
    * var mixed $fields
    *
    */
    public function process($fields = array())
    {
        $this->CI->load->helper('form');
        
        foreach ($fields AS $name => $value)
        {
            $this->_fields[$name] = $value;
        }
        
        // If we are in test mode, lets overwrite the gateway url with the sandbox url
        if ($this->_config['mode'] == 'test')
        {
            $gateway_url = $this->_config['sandbox_url'];
        }
        else
        {
            $gateway_url = $this->_config['gateway_url'];
        }

        $str = '<html><head><title>Processing Paypal payment..</title></head><body>
            <h2>Preparing Transaction</h2>
            <p style="text-align:center;">Please wait while your order is being processed.<br />You will be redirected to the paypal website.</p>
            <p style="text-align:center;">If your browser does not redirect you, please<br />click the Continue button below to proceed.</p>
            <form id="paypal" name="paypal" method="post" action="'.$gateway_url.'"><p style="text-align:center;padding-top:20px;">' . "\n";

        foreach ($this->_fields as $name => $value)
        {
            $str .= '<input type="hidden" name="' . $name . '" value="' . $value . '" />' . "\n";
        }

        $str .= '<input type="submit" value="'.$this->_config["submit_button"].'" name="pp_submit" id="pp_submit" /></p></form></body></html>';

        echo $str;
    }

    /**
    * Validates the response from Paypal
    *
    */
    public function callback()
    {
        // If we are in test mode, lets overwrite the gateway url with the sandbox url
        if ($this->_config['mode'] == 'test')
        {
            $gateway_url = $this->_config['sandbox_url'];
        }
        else
        {
            $gateway_url = $this->_config['gateway_url'];
        }
        
        $url_parsed = parse_url($gateway_url);

        $post_string = '';

        // If we have post data
        if ($_POST)
        {
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

}