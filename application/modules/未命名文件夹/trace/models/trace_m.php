<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Trace_m extends MY_Model
{
	protected $_table = 'trace';
	
	public function get_trace($id, $orderby='dateline', $direction='desc', $offset=0, $length=18)
	{
		return $this->db
		->where('blogid', $id)
		->order_by($orderby, $direction)
		->limit($length, $offset)
		->get($this->_table)
		->result();	
	}
	
	public function get_trace_list($where, $orderby='dateline', $offset=0, $length=18)
	{
		return $this->db
		->select('blog.*, blogfield.message, blogfield.target_ids')
		->join('blog', 'blog.blogid = trace.blogid', 'left')
		->join('blogfield', 'blogfield.blogid = trace.blogid', 'left')
		->where($where)
		->order_by($orderby)
		->limit($length, $offset)
		->get($this->_table)
		->result();
	}
	
	public function get_trace_count($where)
	{
		return $this->db
		->select('blog.*, blogfield.message, blogfield.target_ids')
		->join('blog', 'blog.blogid = trace.blogid', 'left')
		->join('blogfield', 'blogfield.blogid = trace.blogid', 'left')
		->where($where)
		->get($this->_table)
		->num_rows();
		
	}

}