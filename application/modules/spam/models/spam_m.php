<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Spam_m extends MY_Model
{	
	public function __construct()
	{
		parent::__construct();
		
		$this->load->driver('cache');
	}
	
	public function spam_cache($update=FALSE)
	{
		$spam = $this->cache->get('spam');
		
		if( ! $spam || $update)
		{
			$row = $this->db->where('var', 'spam')->get('data')->row_array();
			$spam = empty($row['datavalue'])?array():unserialize($row['datavalue']);		
		
			$this->cache->save('spam', $spam);
		}
		
		return $spam;
	}
}