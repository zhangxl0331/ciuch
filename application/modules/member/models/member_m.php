<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Member_m extends MY_Model
{	
	public function __construct()
	{
		parent::__construct();
	}
	
	function checkauth() 
	{		
		@list($password, $uid) = explode("\t", authcode(get_cookie('auth'), 'DECODE', UC_KEY));
		$auth = $this->db->where(array('uid'=>$uid, 'password'=>$password))->get('session')->row_array();
		if( ! $auth) 
		{
			$auth = $this->db->where(array('uid'=>$uid, 'password'=>$password))->get('member')->row_array();		
		}		
		if( ! $auth)
		{
			$auth = array('uid'=>0, 'username'=>'', 'password'=>'');
		}	
		return $auth;
	}
	
	public function insertsession($setarr) 
	{
		extract($this->load->get_var('global'));

		$this->db->or_where('lastactivity <', intval(time()-$config['onlinehold']))->delete('session', array('uid'=>$setarr['uid']));
		
		$ips = explode('.', $this->input->ip_address());
		for($i=0;$i<3;$i++) {
			$ips[$i] = intval($ips[$i]);
		}
		$ip = sprintf('%03d%03d%03d', $ips[0], $ips[1], $ips[2]);
		$setarr['lastactivity'] = time();
		$setarr['ip'] = $ip;
		$this->db->replace('session', $setarr);
	}
	
	function clearcookie() 
	{
		set_cookie('auth', '', -86400 * 365);
	}

}