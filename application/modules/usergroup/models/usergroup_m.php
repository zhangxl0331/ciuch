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
	
	function usergroup_cache()
	{
		$sglobal = $this->load->get_var('sglobal');
	
		$sglobal['usergroup'] = array();
		$highest = true;
		$lower = '';
		$rows = $this->db->order_by('creditlower DESC')->get('usergroup')->result_array();
		foreach($rows as $group) {
			$group['maxattachsize'] = $group['maxattachsize'] * 1024 * 1024;//M
			if($group['system'] == 0) {
				//�Ƿ����������
				if($highest) {
					$group['credithigher'] = 999999999;
					$highest = false;
					$lower = $group['creditlower'];
				} else {
					$group['credithigher'] = $lower - 1;
					$lower = $group['creditlower'];
				}
			}
			$sglobal['usergroup'][$group['gid']] = $group;
		}
		$this->cache->save('usergroup', $sglobal['usergroup']);
		$this->load->vars('sglobal', $sglobal);
	}
	
	function usergroup()
	{
		return $this->cache->get('usergroup');
	}
	
	function getgroupid($credit, $gid=0) {

		$usergroup = $this->usergroup_cache();
	
		$needfind = false;
		if($gid && !empty($usergroup[$gid])) {
			$group = $usergroup[$gid];
			if(empty($group['system'])) {
				if($group['credithigher']<$credit || $group['creditlower']>$credit) {
					$needfind = true;
				}
			}
		} else {
			$needfind = true;
		}
		if($needfind) {
			$gid = $this->db->select('gid')->where(array('creditlower<='=>$credit, 'system'=>0))->order_by('creditlower DESC')->get('usergroup', 1)->first_row();
		}
		return $gid;
	}

}