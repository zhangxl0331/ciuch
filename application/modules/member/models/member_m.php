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
	
	public function avatar($uid, $size='middle', $type='')
	{
		extract($this->load->get_var('global'));
		$this->load->library('uc/user_l', '', 'uc_user_l');
		$cachekey = "avatarfile_{$uid}_{$size}_{$type}";
		if( ! $avatar = $this->cache->get($cachekey))
		{
		$avatar = $this->uc_user_l->avatar($uid, $size, $type);
		$this->cache->save($cachekey, $avatar);
		}
	
		return $config['uc_dir'].$avatar;
	}
	
	public function updatelogin()
	{
		extract($this->load->get_var('global'));
		if($auth['uid'] AND  ! isset($auth['lastactivity']))
		{
			$ips = explode('.', $this->input->ip_address());
			for($i=0;$i<3;$i++) {
				$ips[$i] = intval($ips[$i]);
			}
			$ip = sprintf('%03d%03d%03d', $ips[0], $ips[1], $ips[2]);
	
			$this->db->update('space', array('lastlogin'=>time(), 'ip' => $ip), array('uid'=>$auth['uid']));
		}
	}
	
	public function getmember()
	{
		extract($this->load->get_var('global'));
		if($auth['uid'])
		{
			return $this->getspace($auth['uid']);
		}
		return array();
	}
	
	function question() 
	{
		$spam = $this->cache->get('spam');
		return $spam[mt_rand(0, max(count($spams)-1, 0))];
	}
	
	function getspace($key, $indextype='uid', $auto_open=1)
	{
		$uch = $this->load->get_var('uch');
	
		$var = "space_{$key}_{$indextype}";
		if(empty($uch[$var])) {
			$space = $this->db->select('sf.*, s.*')->from('space s')->join('spacefield sf', 'sf.uid=s.uid', 'LEFT')->where("s.{$indextype}", $key)->get()->row_array();
			if(!$space) {
				$space = array();
				if($indextype=='uid' && $auto_open) {
					include_once(S_ROOT.'./uc_client/client.php');
					if($user = uc_get_user($key, 1)) {
						include_once(S_ROOT.'./source/function_space.php');
						$space = space_open($user[0], addslashes($user[1]), 0, addslashes($user[2]));
					}
				}
			}
			if($space) {
				$space['realname'] = ($uch['config']['realname'] && $space['name'] && $space['namestatus'])?$space['name']:$space['username'];
				$space['self'] = $space['uid']==$uch['global']['supe_uid'];
				$isonline = $this->db->where('uid', $space['uid'])->get('session')->row_array();
				$space['isonline'] = $isonline?sgmdate('H:i:s', $isonline['lastactivity'], 1):0;
				$space['creditstar'] = $this->getstar($space['credit']);
				$space['domainurl'] = $this->space_domain($space);
					
				$space['friends'] = array();
				if(empty($space['friend'])) {
					if($space['friendnum']>0) {
						$fstr = $fmod = '';
						$rows = $this->db->select('fuid')->where(array('uid'=>$uid, 'status'=>$status))->get('friend')->result_array();
						foreach($rows as $value) {
							$space['friends'][] = $value['fuid'];
							$fstr .= $fmod.$value['fuid'];
							$fmod = ',';
						}
						$space['friend'] = $fstr;
					}
				} else {
					$space['friends'] = explode(',', $space['friend']);
				}
					
				$space['privacy'] = empty($space['privacy'])?(empty($uch['config']['privacy'])?array():$uch['config']['privacy']):unserialize($space['privacy']);
				if($space['self']) {
					$uch['member'] = $space;
				}
				$uch['space'] = $space;
			}
			else {
				show_message('space_does_not_exist', base_url('space/home'), 0);
			}
			$uch[$var] = $space;
			$this->load->vars('uch', $uch);
		}
		return $uch[$var];
	}
	
	function checklogin() {
		$uch = $this->load->get_var('uch');
	
		if(empty($uch['global']['supe_uid'])) {
			set_cookie('_refer', rawurlencode($_SERVER['REQUEST_URI']));
			show_message('to_login', 'do.php?ac='.$uch['config']['login_action']);
		}
	}
	
	function space_domain($space) {
		$uch = $this->load->get_var('uch');
	
		if($space['domain'] && $uch['config']['allowdomain'] && $uch['config']['domainroot']) {
			$space['domainurl'] = 'http://'.$space['domain'].'.'.$uch['config']['domainroot'];
		} else {
			if($uch['config']['allowrewrite']) {
				$space['domainurl'] = base_url()."space/uid-{$space['uid']}.html";
			} else {
				$space['domainurl'] = base_url()."space?uid={$space['uid']}";
			}
		}
		return $space['domainurl'];
	}
	
	function getstar($credit) {
		$uch = $this->load->get_var('uch');
	
		$starimg = '';
		if($uch['config']['starcredit'] > 1) {
			//����������
			$starnum = intval($credit/$uch['config']['starcredit']) + 1;
			if($uch['config']['starlevelnum'] < 2) {
				if($starnum > 10) $starnum = 10;
				for($i = 0; $i < $starnum; $i++) {
					$starimg .= '<img src="'.base_url().'image/star_level10.gif" align="absmiddle" />';
				}
			} else {
				//����ȼ�(10��)
				for($i = 10; $i > 0; $i--) {
					$numlevel = intval($starnum / pow($uch['config']['starlevelnum'], ($i - 1)));
					if($numlevel > 10) $numlevel = 10;
					if($numlevel) {
						for($j = 0; $j < $numlevel; $j++) {
							$starimg .= '<img src="'.base_url().'image/star_level'.$i.'.gif" align="absmiddle" />';
						}
						break;
					}
				}
			}
		}
		if(empty($starimg)) $starimg = '<img src="'.base_url().'image/credit.gif" alt="'.$credit.'" align="absmiddle" alt="'.$credit.'" title="'.$credit.'" />';
		return $starimg;
	}
	
	function ckprivacy($type, $feedmode=0, $uid=0) {
		$uch = $this->load->get_var('uch');
		if($uid)
		{
			$uch['space'] = $this->getspace($uid);
		}
	
		$var = "ckprivacy_{$type}_{$feedmode}";
		if(isset($uch[$var])) {
			return $uch[$var];
		}
		$result = false;
		if($feedmode) {
			if(!empty($uch['space']['privacy']['feed'][$type])) {
				$result = true;
			}
		} elseif($uch['space']['self']){
			//�Լ�
			$result = true;
		} else {
			if(empty($uch['space']['privacy']['view'][$type])) {
				$result = true;
			}
			if(!$result && $uch['space']['privacy']['view'][$type] == 1) {
				//�Ƿ����
				if(!isset($uch['space']['isfriend'])) {
					$uch['space']['isfriend'] = $uch['space']['self'];
					if($uch['space']['friends'] && in_array($uch['supe_uid'], $uch['space']['friends'])) {
						$uch['space']['isfriend'] = 1;//�Ǻ���
					}
				}
				if($uch['space']['isfriend']) {
					$result = true;
				}
			}
		}
		$uch[$var] = $result;//��ǰҳ�滺��
		return $result;
	}

}