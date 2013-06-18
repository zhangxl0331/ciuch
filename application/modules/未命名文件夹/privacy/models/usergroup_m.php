<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Usergroup_m extends MY_Model
{
	protected $_table = 'usergroup';
	
	public function __construct()
	{
		parent::__construct();
	}
	
	public function get_all()
	{
		return $this->db
		
		->order_by('creditlower', 'DESC')
		->get($this->_table)
		->result();
	}
	
	public function get_gid_by($data)
	{
		return $this->db
		->select('gid')
		->where($data)
		->order_by('creditlower', 'DESC')
		->get($this->_table)
		->limit(1)
		->row();
	}

}