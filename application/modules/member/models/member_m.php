<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Member_m extends MY_Model
{	
	protected $_table = 'member';
	protected $_primary_key = 'uid';
	
	public function __construct()
	{
		parent::__construct();
	}

}