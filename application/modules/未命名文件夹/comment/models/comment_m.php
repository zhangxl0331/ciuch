<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Comment_m extends MY_Model
{
	protected $_table = 'comment';
	
	public function get_comment($wherearr, $orderby='dateline', $direction='DESC', $offset='0', $length='18')
	{
		return $this->db
		->where($wherearr)
		->order_by($orderby, $direction)
		->limit($length, $offset)
		->get($this->_table)
		->result();
	}

}