<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Usergroup_m extends MY_Model
{
	protected $_table = 'usergroup';
	
	public function __construct()
	{
		parent::__construct();
		$this->load->driver('cache');
	}
	
	
	
	function usergroup_cache($update=FALSE)
	{
		$usergroup = $this->cache->get('usergroup');
		if( ! $usergroup || $update)
		{
			$highest = true;
			$lower = '';
			$rows = $this->db->order_by('creditlower DESC')->get('usergroup')->result_array();
			foreach($rows as $group) {
				$group['maxattachsize'] = $group['maxattachsize'] * 1024 * 1024;//M
				if($group['system'] == 0) {
					if($highest) {
						$group['credithigher'] = 999999999;
						$highest = false;
						$lower = $group['creditlower'];
					} else {
						$group['credithigher'] = $lower - 1;
						$lower = $group['creditlower'];
					}
				}
				$usergroup[$group['gid']] = $group;
			}
			
			$this->cache->save('usergroup', $usergroup);
		}
	
		return $usergroup;
	}
	
	public function checkclose()
	{
		$config = $this->load->get_var('config');
		$auth = $this->load->get_var('auth');
		$usergroup = $this->load->get_var('usergroup');
		$closeignore = isset($usergroup[$auth['uid']]['closeignore']) OR $usergroup[$auth['uid']]['closeignore'];
		if(isset($config['close']) AND $config['close'] AND !$closeignore) 
		{
			if(empty($config['closereason'])) 
			{
				showmessage('site_temporarily_closed');
			} 
			else 
			{
				showmessage($config['closereason']);
			}
		}
		
		if((isset($config['ipaccess']) AND !$this->ipaccess($config['ipaccess'])) OR (isset($config['ipbanned']) AND $this->ipbanned($config['ipbanned'])) AND !$closeignore) 
		{
			showmessage('ip_is_not_allowed_to_visit');
		}
	}
	
	//ip��������
	function ipaccess($ipaccess) {
		return empty($ipaccess)?true:preg_match("/^(".str_replace(array("\r\n", ' '), array('|', ''), preg_quote($ipaccess, '/')).")/", $this->input->ip_address());
	}
	
	//ip���ʽ�ֹ
	function ipbanned($ipbanned) {
		return empty($ipbanned)?false:preg_match("/^(".str_replace(array("\r\n", ' '), array('|', ''), preg_quote($ipbanned, '/')).")/", $this->input->ip_address());
	}
	
	public function checkperm($permtype)
	{
		$auth = $this->load->get_var('auth');
		$usergroup = $this->load->get_var('usergroup');
// 		echo '<PRe>';var_dump($usergroup);exit;
		return $usergroup[$auth['groupid']][$permtype];
	}
	
	public function ckinterval($type)
	{
		$auth = $this->load->get_var('auth');
		$usergroup = $this->load->get_var('usergroup');
		$waittime = 0;
		if(isset($usergroup[$auth['groupid']][$type.'interval']) AND !empty($usergroup[$auth['groupid']][$type.'interval']))
		{
			$waittime = $usergroup[$auth['groupid']][$type.'interval'] - (time() - $auth['last'.$type]);
		}
		return $waittime;
	}
	
	public function ckseccode()
	{
		$auth = $this->load->get_var('auth');
		$usergroup = $this->load->get_var('usergroup');
		
		if( ! empty($usergroup[$auth['groupid']]['seccode']))
		{
			$this->load->library('form_validation');
			$this->form_validation->set_rules('seccode', '', 'imatches[verify]');
			$this->form_validation->set_message('imatches', lang('incorrect_code'));
		}
	}
}