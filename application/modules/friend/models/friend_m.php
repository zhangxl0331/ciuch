<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Friend_m extends MY_Model
{
	protected $_table = 'friend';

	public function get_fuid_by_uid($uid, $status=1)
	{
		return $this->db->select('fuid')->where(array('uid'=>$uid, 'status'=>$status))->get('friend')->result_array();
	}
}