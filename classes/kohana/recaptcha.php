<?php defined('SYSPATH') or die('No direct script access.');

/**
 * Kohana 3.2.0
 * reCaptcha module
 * 
 * @author     Piotr "Wilku" Wilk <wilku002@gmail.com>
 * @copyright  Copyright (c) 2012 Piotr Wilk
 * @license    See README file
 */

class Kohana_Recaptcha {
    
    /**
	 * Public key
	 * @var string
	 */
    protected $_public_key;
    
   	/**
	 * Private key
	 * @var string
	 */
    protected $_private_key;
      
   	/**
	 * Captcha view config
     * @var string
	 */
    protected $_view_config;
     
   	/**
	 * Module configuration
	 *
	 * @param   array  $config
	 * @return  object
	 */
	public function __construct(array $config = NULL)
	{   
        if(empty($config))
        {
            $config = Kohana::$config->load('recaptcha');
        }    
        
        $this->_public_key = $config['public_key'];
		$this->_private_key = $config['private_key'];
        
        $this->_view_config = View::factory('config');
        $this->_view_config->theme = $config['theme'];
        $this->_view_config->lang = $config['lang'];
	}
    
   	/**
	 * Generate reCaptcha HTML code with view configuration
	 *
	 * @return  string
	 */
   	public function get_html()
	{   
        $html = $this->_view_config.recaptcha_get_html($this->_public_key);
        
		return $html;
	}
    
   	/**
	 * Validation reCaptcha code
	 *
	 * @return  bool
	 */
    public function validate()
    {
	    $challenge = Request::$current->post('recaptcha_challenge_field');
        $response = Request::$current->post('recaptcha_response_field');
        
        $result = recaptcha_check_answer($this->_private_key, Request::$client_ip, $challenge, $response);
        
        return (bool) $result->is_valid;
    }
}