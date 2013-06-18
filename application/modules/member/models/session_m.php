<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Session_m extends MY_Model
{	
	protected $_table = 'session';
	
	public function __construct()
	{
		parent::__construct();
		
	}
	
	public function delete_by_uid_or_lastactivity($uid, $lastactivity)
	{		
		$this->db->where('uid', $uid)->or_where('lastactivity <', $lastactivity)->delete($this->_table);
	}

}