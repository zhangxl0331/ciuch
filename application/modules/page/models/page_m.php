<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Page_m extends MY_Model
{	
	public function __construct()
	{
		parent::__construct();
		
		$this->load->driver('cache');
	}
}