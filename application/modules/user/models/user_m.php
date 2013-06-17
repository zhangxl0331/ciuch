<?php defined('BASEPATH') OR exit('No direct script access allowed');

class User_m extends MY_Model
{	
	protected $_db = '';
	protected $_table = 'member';
	
	public function __construct()
	{
		parent::__construct();
		$this->load->helper('uch');
	}
	
	function checkauth() {
		$auth = get_cookie('auth');
		if($auth) {
			@list($password, $uid) = explode("\t", authcode($auth, 'DECODE'));
			$uch['supe_uid'] = intval($uid);
			if($uid) {
				$member = $this->db->where(array('uid'=>$uid, 'password'=>$password))->get('session')->row_array();
				return $member;
				if(!$member) {
					$member = $this->db->where(array('uid'=>$uid, 'password'=>$password))->get('member')->row_array();
					if($member) {
						$this->db->or_where('lastactivity <', intval($uch['timestamp']-$uch['config']['onlinehold']))->delete('session', array('uid'=>$uid));
	
						$setarr = array('uid' => $uch['supe_uid'], 'username' => $member['username'], 'password' => $password);
						$ips = explode('.', $this->input->ip_address());
						for($i=0;$i<3;$i++) {
							$ips[$i] = intval($ips[$i]);
						}
						$ip = sprintf('%03d%03d%03d', $ips[0], $ips[1], $ips[2]);
						$setarr['lastactivity'] = time();
						$setarr['ip'] = $ip;
						$this->db->replace('session', $setarr);
	
						$this->db->update('space', array('lastlogin'=>$uch['timestamp'], 'ip' => $ip), array('uid'=>$uch['supe_uid']));
						return $member;
					} else {
						$uid = 0;
					}
				}
			}
		}
		if(empty($uid)) {
			set_cookie('auth', '', -86400 * 365);
		} 
	
		return FALSE;
	}
}