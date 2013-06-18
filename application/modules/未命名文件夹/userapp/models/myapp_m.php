<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Myapp_m extends MY_Model
{
	protected $_table = 'myapp';
	protected $_primary_key = 'appid';
	
	public function __construct()
	{
		parent::__construct();
	}

}