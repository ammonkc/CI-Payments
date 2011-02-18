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

    const BUYNOW       = '_xclick';
    const SUBSCRIPTION = '_xclick-subscriptions';

    protected $CI;

    public function __construct()
    {
        $this->CI =& get_instance();

        // Add the gateway URL
        $this->add_config_item('paypal', 'gateway_url', 'https://www.paypal.com/cgi-bin/webscr');

        // Default payment fields
        $this->add_field('rm','2'); // Return method is POST
        $this->add_field('cmd', SELF::BUYNOW);

        $this->add_field('currency_code', $this->CI->config->item('currency_code'));
        $this->add_field('quantity', $this->_fields['quantity']);
        $this->add_field('no_note', '1');
        $this->add_field('return', $this->_config['paypal']['success_url']);
        $this->add_field('cancel_return', $this->_config['paypal']['failure_url']);
        $this->add_field('notify_url', $this->_config['paypal']['notify_url']);
    }

    /**
    * Processes the payment. This will display a form which will then redirect to Paypal.
    *
    */
    public function process()
    {
        $this->CI->load->helper('form');

        $str = '<html><head><title>Processing Paypal payment..</title></head><body onLoad="document.forms[\'paypal\'].submit();">
            <h2>Preparing Transaction</h2>
            <p style="text-align:center;">Please wait while your order is being processed.<br />You will be redirected to the paypal website.</p>
            <p style="text-align:center;">If your browser does not redirect you, please<br />click the Continue button below to proceed.</p>
            <form id="paypal" name="paypal" method="post" action="'.$this->_config['paypal']['gateway_url'].'"><p style="text-align:center;padding-top:20px;">' . "\n";

        foreach ($this->_fields as $name => $value)
        {
            $str .= '<input type="hidden" name="' . $name . '" value="' . $value . '" />' . "\n";
        }

        $str .= '<input type="submit" value="'.$this->_config['paypal']["submit_button"].'" name="pp_submit" id="pp_submit" /></p></form></body></html>';

        return $str;
    }

    /**
    * Validates the response from Paypal
    *
    */
    public function callback()
    {
        $url_parsed = parse_url($this->_config['paypal']['gateway_url']);

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

        $this->_curl = curl_init();
        curl_setopt($this->_curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($this->_curl, CURLOPT_POST, 1);
        curl_setopt($this->_curl, CURLOPT_HEADER , 0);
        curl_setopt($this->_curl, CURLOPT_VERBOSE, 1);
        curl_setopt($this->_curl, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($this->_curl, CURLOPT_TIMEOUT, 30);
        curl_setopt($this->_curl, CURLOPT_URL, $this->_config['paypal']['gateway_url']);
        curl_setopt($this->_curl, CURLOPT_POSTFIELDS, $post_string);
        curl_setopt($this->_curl, CURLOPT_HTTPHEADER, array("Content-Type: application/x-www-form-urlencoded", "Content-Length: " . strlen($post_string)));
        $result = curl_exec($this->_curl);

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