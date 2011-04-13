<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
 
/**
 * Authorize.NET AIM processing class for CodeIgniter
 *
 * A class to simplify the processing of payments using Autorize.NET AIM.
 * This does not do everything but is a good start to processing payments
 * using CodeIgniter.
 *
 * 
 * @package		CI Payments
 * @subpackage  Authorize.net
 * @author		Ammon Casey < @ammonkc >
 * @category    Driver
 * @copyright	Copyright (c) 2011, Ammon Casey
 * @link		http://brokenparadigmlabs.com
 * @license     http://www.opensource.org/licenses/mit-license.php
 * @version		Version 0.2
 */
class Payments_authorize_aim extends CI_Driver {
	
	// Codeigniter instance
	protected $_ci;
	
	// Response from Authorize.net
	protected $_response = array();
	
	// Fields of data to send off to Authorize.net
	protected $_fields = array();
	
	// Configuration settings for Authorize.net driver
	protected $_config;
	
    var $field_string;
    var $response_string;
    var $debuginfo;
    var $gateway_url;
    
    /**
     * __construct
     * 
     * Loads the configuration settings for Authorize.NET
     * 
     * @return void
     * @author Ammon Casey
     **/
    public function __construct()
    {
		$this->_ci = get_instance();
		
		// Load our Payments config file
		$this->_ci->load->config('payments');
		
		// Store settings for this gateway into the class variable _config
		$this->_config = $this->_ci->config->item('authorize_net');
        
		if( $this->_config['test_mode'] == 'TRUE' ) {
			$this->gateway_url = $this->_config['test_api_host'];
			$this->add_x_field('x_test_request', $this->_config['test_mode']);
			$this->add_x_field('x_login', $this->_config['test_x_login']);
			$this->add_x_field('x_tran_key', $this->_config['test_x_tran_key']);
		}else{
			$this->gateway_url = $this->_config['live_api_host'];
			$this->add_x_field('x_test_request', $this->_config['test_mode']);
			$this->add_x_field('x_login', $this->_config['live_x_login']);
			$this->add_x_field('x_tran_key', $this->_config['live_x_tran_key']);
		}
		$this->add_x_field('x_version', $this->_config['x_version']);
      	$this->add_x_field('x_delim_data', $this->_config['x_delim_data']);
      	$this->add_x_field('x_delim_char', $this->_config['x_delim_char']);  
      	$this->add_x_field('x_encap_char', $this->_config['x_encap_char']); 
      	$this->add_x_field('x_url', $this->_config['x_url']);
      	$this->add_x_field('x_type', $this->_config['x_type']);
      	$this->add_x_field('x_method', $this->_config['x_method']);
      	$this->add_x_field('x_relay_response', $this->_config['x_relay_response']);
      	$this->add_x_field('x_currency_code', $this->_config['x_currency_code']);
      	$this->add_x_field('x_duplicate_window', $this->_config['x_duplicate_window']);
	}
	
	/**
	 * Add field to query for processing
	 * 
	 * Used to add a field to send to Autorize.NET for payment processing.
	 * 
	 * @param mixed $field
	 * @param mixed $value
	 * @access	public
	 */
	function add_x_field( $field, $value = '' ) {
	    if ( is_array($field) )
	    {
	        foreach($field as $key => $val)
	        {
	            $this->_fields[$key] = $val;
	        }
	    }else{
            $this->_fields[$field] = $value;
        }
    }

   /**
    * Process payment
    * 
    * Send the payment to Authorize.NET for processing. Returns the response codes
    * 1 - Approved
    * 2 - Declined
    * 3 - Transaction Error
    * There is no need to check the MD5 Hash according to Authorize.NET documentation
	* since the process is being sent and received using SSL. 
    * 
    * For your reference, you can use the following test credit card numbers when testing your connection. The expiration date must be set to the present date or later:
    * - American Express Test Card: 370000000000002
    * - Discover Test Card: 6011000000000012	
    * - Visa Test Card: 4007000000027	
    * - Second Visa Test Card: 4012888818888	
    * - JCB: 3088000000000017	
    * - Diners Club/ Carte Blanche: 38000000000006
    *
    * @access	public
    * @return	returns response code 1,2,3
    */
    function process( $fields = array() ) {
    
        $this->_fields = array_merge($this->_fields, $fields);
        
        foreach( $this->_fields as $key => $value ) {
        	$this->field_string .= "$key=" . urlencode( $value ) . "&";
        }
        $ch = curl_init($this->gateway_url); 
        curl_setopt($ch, CURLOPT_HEADER, 0); 
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
        curl_setopt($ch, CURLOPT_POSTFIELDS, rtrim( $this->field_string, "& " )); 
        $this->response_string = urldecode(curl_exec($ch)); 
        
        if (curl_errno($ch)) {
        	$this->_response['Response_Reason_Text'] = curl_error($ch);
        	return 3;
        }else{
        	curl_close ($ch);
        }
        $temp_values = explode($this->_config['x_delim_char'], $this->response_string);
        $temp_keys = array ( 
        	"Response_Code", "Response_Subcode", "Response_Reason_Code", "Response_Reason_Text",
        	"Approval_Code", "AVS_Result_Code", "Transaction_ID", "Invoice_Number", "Description",
        	"Amount", "Method", "Transaction_Type", "Customer_ID", "Cardholder_First_Name",
        	"Cardholder Last_Name", "Company", "Billing_Address", "City", "State",
        	"Zip", "Country", "Phone", "Fax", "Email", "Ship_to_First_Name", "Ship_to_Last_Name",
        	"Ship_to_Company", "Ship_to_Address", "Ship_to_City", "Ship_to_State",
        	"Ship_to_Zip", "Ship_to_Country", "Tax_Amount", "Duty_Amount", "Freight_Amount",
        	"Tax_Exempt_Flag", "PO_Number", "MD5_Hash", "Card_Code_CVV_Response Code",
        	"Cardholder_Authentication_Verification_Value_CAVV_Response_Code"
        );
        for ($i=0; $i<=27; $i++) {
        	array_push($temp_keys, 'Reserved_Field '.$i);
        }
        $i=0;
        while (sizeof($temp_keys) < sizeof($temp_values)) {
        	array_push($temp_keys, 'Merchant_Defined_Field '.$i);
        	$i++;
        }
        for ($i=0; $i<sizeof($temp_values);$i++) {
        	$this->_response["$temp_keys[$i]"] = $temp_values[$i];
        }
        return $this->_response['Response_Code'];
    }
   
   /**
    * Get the response text.
    * 
    * Returns the response reason text for the payment processed. Must be called
    * after you have caled process_payment().
    * 
    * @access	public
    * @return	returns the response reason text
    */
   function get_response_reason_text() {
		return $this->_response['Response_Reason_Text'];
   }
   
	/**
	 * Get all the codes returned
	 * 
	 * With this function you can retreive all response codes and values
	 * from your transaction. This must be called after your have called 
	 * the process_payment() function.
	 * 
	 * @access	public
	 * @return returns all codes and values in a array.
	 */
	function get_all_response_codes() {
		return $this->_response;
	}

   /**
    * Dump fields sent to Authorize.NET
    * 
    * This is used for de bugging purposes. It will output the
    * field/value pairs sent to Authorize.NET to process the 
    * payment. Must be called after the process_payment() function
    * 
    * @access	public
    * @return	prints output directly to browser.
    */
   function debug_fields() {				
		echo "<h3>payments->debug_fields() Output:</h3>";
		echo "<table width=\"95%\" border=\"1\" cellpadding=\"2\" cellspacing=\"0\">
		    <tr>
		       <td bgcolor=\"black\"><b><font color=\"white\">Field Name</font></b></td>
		       <td bgcolor=\"black\"><b><font color=\"white\">Value</font></b></td>
		    </tr>"; 
		    
		foreach ($this->_fields as $key => $value) {
		 echo "<tr><td>$key</td><td>".urldecode($value)."&nbsp;</td></tr>";
		}
		
		echo "</table><br>"; 
   }

   /**
    * Dump response from Authorize.NET
    * 
    * This will return the complete output sent from Authorize.NET
    * after payment has been processed. Whether approved, declined 
    * or transaction error. Must be called after the process_payment()
    * function.
    * 
    * @access	public
    * @return	returns all the field/value pairs
    */
   function debug_response() {             
      $i = 0;
      foreach ($this->_response as $key => $value) {
         $this->debuginfo .= "$key: $value\n";
         $i++;
      } 
      return $this->debuginfo;
   }
   
   /**
   * Returns the right gateway URL for the Authorize.net gateway,
   * depending whether or not we are in test mode.
   * 
   */
   private function get_gateway_url()
   {
       $gateway_url = '';
       
       // If we are in test mode, lets overwrite the gateway url with the sandbox url
       if ( $this->_config['test_mode'] == 'TRUE' )
       {
           $gateway_url = $this->_config['test_api_host'];
       }
       else
       {
           $gateway_url = $this->_config['live_api_host'];
       }
       
       return $gateway_url;
   }
   
   /**
    * Get the fields array
    * 
    * Returns the full _fields array for the payment processed. 
    * 
    * @access	public
    * @return	returns the response reason text
    */
   function get_fields() {
   		return $this->_fields;
   }
   
}