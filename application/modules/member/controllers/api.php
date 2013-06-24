<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Api extends REST_Controller {

	function __construct()
	{
		parent::__construct();
	}
	
	public function captcha_get()
	{
		$result = create_captcha(array(
				'img_path' => APPPATH.'cache/captcha/',
				'img_url' => base_url(APPPATH.'cache/captcha/').'/'
		));
		
		$this->response($result);
	}
}