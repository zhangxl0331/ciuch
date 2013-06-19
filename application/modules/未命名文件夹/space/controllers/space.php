<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Space extends Space_Controller {

	public function __construct()
	{
		parent::__construct();
	}
	
	public function guide()
	{
		$uch = $this->load->get_var('uch');
		$step = empty($_GET['step'])?1:intval($_GET['step']);
		if(!in_array($step, array(1,2,3,4,5))) $step = 1;
		
		$actives = array($step => ' class="active"');
		$_SGLOBAL['guidemode'] = 1;
		
		if($step == 1) {
			redirect(base_url('space/cp/avatar'));
		} elseif($step == 2) {
			redirect(base_url('space/cp/profile'));
		} elseif($step == 3) {
			$_GET['op'] = 'find';
			redirect(base_url('space/cp/friend'));
		} elseif($step == 4) {
			Events::trigger('update_space', array(array('updatetime'=>$uch['timestamp'], 'uid'=>$uch['supe_uid'])));
			show_message('do_success', base_url('space/home?view=all'), 0);
		}
	}
	
	public function home()
	{
// 		$uch = $this->load->get_var('uch');
// 		if($_GET['view'] == 'guide') {
// 			redirect(base_url('space/guide'));
// 		}
		
// 		$perpage = $uch['config']['feedmaxnum']<50?50:$uch['config']['feedmaxnum'];
// 		$start = empty($_GET['start'])?0:intval($_GET['start']);
// 		Events::trigger('ckstart', array('start'=>$start, 'perpage'=>$perpage));
		
// 		$uch['today'] = strtotime(sgmdate('Y-m-d'));
		
// 		if(empty($_GET['view']) && $uch['space']['self'] && ($uch['space']['friendnum']<$_SCONFIG['showallfriendnum'])) {
// 			$_GET['view'] = 'all';//Ĭ����ʾȫվ
// 		}
		
// 		if($_SCONFIG['my_status'] && $_SCONFIG['feeddefaultfilter'] && empty($_GET['filter'])) {
// 			$_GET['filter'] = $_SCONFIG['feeddefaultfilter'];
// 		}
		
// 		$notime = 0;
// 		if($_GET['view'] == 'all') {
// 			$wheresql = "friend='0'";//û����˽
// 			$theurl = "space.php?uid=$uch['space'][uid]&do=$do&view=all";
// 			$f_index = '';
// 		} else {
// 			if(empty($uch['space']['feedfriend'])) {
// 				$wheresql = "uid='$uch['space'][uid]'";
// 				$theurl = "space.php?uid=$uch['space'][uid]&do=$do&view=me";
// 				$f_index = '';
// 				$_GET['view'] = 'me';
// 			} else {
// 				$wheresql = "uid IN ('0',$uch['space'][feedfriend])";
// 				$theurl = "space.php?uid=$uch['space'][uid]&do=$do&view=we";
// 				$f_index = 'USE INDEX(dateline)';
// 				$_GET['view'] = 'we';
// 				$notime = 1;
// 			}
// 		}
		
// 		$appid = empty($_GET['appid'])?0:intval($_GET['appid']);
// 		if($appid) {
// 			$wheresql .= " AND appid='$appid'";
// 		}
// 		$icon = empty($_GET['icon'])?'':trim($_GET['icon']);
// 		if($icon) {
// 			$wheresql .= " AND icon='$icon'";
// 		}
// 		$filter = empty($_GET['filter'])?'':trim($_GET['filter']);
// 		if($filter == 'site') {
// 			$wheresql .= " AND appid>0";
// 		} elseif($filter == 'myapp') {
// 			$wheresql .= " AND appid='0'";
// 		}
		
// 		$feed_list = array();
// 		$count = 0;
// 		$query = $_SGLOBAL['db']->query("SELECT * FROM ".tname('feed')." $f_index
// 				WHERE $wheresql
// 				ORDER BY dateline DESC
// 				LIMIT $start,$perpage");
// 		if(empty($uch['space']['feedfriend'])) {
// 			//���˶�̬
// 			while ($value = $_SGLOBAL['db']->fetch_array($query)) {
// 				if(ckfriend($value) && ckicon_uid($value)) {
// 					realname_set($value['uid'], $value['username']);
// 					$feed_list[] = $value;
// 				}
// 				$count++;
// 			}
		
// 			//��ҳ
// 			$multi = smulti($start, $perpage, $count, $theurl);
// 		} else {
// 			//���Ѷ�̬
// 			$uch['space']['filter_icon'] = empty($uch['space']['privacy']['filter_icon'])?array():array_keys($uch['space']['privacy']['filter_icon']);
// 			while ($value = $_SGLOBAL['db']->fetch_array($query)) {
// 				if(empty($feed_list[$value['hash_data']][$value['uid']])) {
// 					if(ckfriend($value) && ckicon_uid($value)) {
// 						realname_set($value['uid'], $value['username']);
// 						$feed_list[$value['hash_data']][$value['uid']] = $value;
// 					}
// 				}
// 				$count++;
// 			}
// 		}
		
// 		$olfriendlist = $visitorlist = $task = $ols = $birthlist = $myapp = array();
// 		$namestatus = $addfriendcount = $mtaginvitecount = $myinvitecount = $pokecount = $newreport = 0;
		
// 		if($uch['space']['self'] && empty($start)) {
		
// 			//��������
// 			$addfriendcount = getcount('friend', array('fuid'=>$uch['space']['uid'], 'status'=>0));
		
// 			//Ⱥ������
// 			$mtaginvitecount = getcount('mtaginvite', array('uid'=>$uch['space']['uid']));
		
// 			//Ӧ������
// 			if($_SCONFIG['my_status']) {
// 				$myinvitecount = getcount('myinvite', array('touid'=>$uch['space']['uid']));
// 			}
		
// 			//�ٱ�����
// 			if(checkperm('managereport')) {
// 				$newreport = $_SGLOBAL['db']->result($_SGLOBAL['db']->query("SELECT COUNT(*) FROM ".tname('report')." WHERE new='1'"), 0);
// 			}
		
// 			//�ȴ�ʵ����֤
// 			if($_SCONFIG['realname'] && checkperm('managename')) {
// 				$namestatus = $_SGLOBAL['db']->result($_SGLOBAL['db']->query("SELECT COUNT(*) FROM ".tname('space')." WHERE namestatus='0' AND name!=''"), 0);
// 			}
// 			//���к�
// 			$pokecount = getcount('poke', array('uid'=>$uch['space']['uid']));
		
// 			//���ÿ��б�
// 			$oluids = array();
// 			$query = $_SGLOBAL['db']->query("SELECT * FROM ".tname('visitor')." WHERE uid='$uch['space'][uid]' ORDER BY dateline DESC LIMIT 0,15");
// 			while ($value = $_SGLOBAL['db']->fetch_array($query)) {
// 				realname_set($value['vuid'], $value['vusername']);
// 				$visitorlist[] = $value;
// 				$oluids[] = $value['vuid'];
// 			}
// 			//�ÿ�����
// 			if($oluids) {
// 				$query = $_SGLOBAL['db']->query("SELECT uid FROM ".tname('session')." WHERE uid IN (".simplode($oluids).")");
// 				while ($value = $_SGLOBAL['db']->fetch_array($query)) {
// 					$ols[$value['uid']] = 1;
// 				}
// 			}
		
// 			$oluids = array();
// 			if($uch['space']['feedfriend']) {
// 				//���ߺ���
// 				$query = $_SGLOBAL['db']->query("SELECT * FROM ".tname('session')." WHERE uid IN ($uch['space'][feedfriend]) ORDER BY lastactivity DESC LIMIT 0,15");
// 				while ($value = $_SGLOBAL['db']->fetch_array($query)) {
// 					realname_set($value['uid'], $value['username']);
// 					$value['isonline'] = 1;
// 					$olfriendlist[] = $value;
// 					$oluids[] = $value['uid'];
// 				}
// 			}
// 			if(count($olfriendlist) < 15) {
// 				//�ҵĺ���
// 				$limit = 15 - count($olfriendlist);
// 				$whereplus = $oluids?" AND fuid NOT IN (".simplode($oluids).")":'';
// 				$query = $_SGLOBAL['db']->query("SELECT fuid AS uid, fusername AS username, num FROM ".tname('friend')." WHERE uid='$uch['space'][uid]' AND status='1' $whereplus ORDER BY num DESC, dateline DESC LIMIT 0,$limit");
// 				while ($value = $_SGLOBAL['db']->fetch_array($query)) {
// 					realname_set($value['uid'], $value['username']);
// 					$value['isonline'] = 0;
// 					$olfriendlist[] = $value;
// 				}
// 			}
		
// 			//��ȡ�
// 			include_once(S_ROOT.'./source/function_space.php');
// 			$task = gettask();
		
// 			//��������
// 			if($uch['space']['feedfriend']) {
// 				list($s_month, $s_day) = explode('-', sgmdate('n-j', $_SGLOBAL['timestamp']-3600*24*7));
// 				list($n_month, $n_day) = explode('-', sgmdate('n-j', $_SGLOBAL['timestamp']));
// 				list($e_month, $e_day) = explode('-', sgmdate('n-j', $_SGLOBAL['timestamp']+3600*24*7));
// 				if($e_month == $s_month) {
// 					$wheresql = "sf.birthmonth='$s_month' AND sf.birthday>='$s_day' AND sf.birthday<='$e_day'";
// 				} else {
// 					$wheresql = "(sf.birthmonth='$s_month' AND sf.birthday>='$s_day') OR (sf.birthmonth='$e_month' AND sf.birthday<='$e_day' AND sf.birthday>'0')";
// 				}
// 				$query = $_SGLOBAL['db']->query("SELECT s.uid,s.username,s.name,s.namestatus,s.groupid,sf.birthyear,sf.birthmonth,sf.birthday
// 			FROM ".tname('spacefield')." sf
// 			LEFT JOIN ".tname('space')." s ON s.uid=sf.uid
// 						WHERE (sf.uid IN ($uch['space'][feedfriend])) AND ($wheresql)");
// 				while ($value = $_SGLOBAL['db']->fetch_array($query)) {
// 					realname_set($value['uid'], $value['username'], $value['name'], $value['namestatus']);
// 					$value['istoday'] = 0;
// 					if($value['birthmonth'] == $n_month && $value['birthday'] == $n_day) {
// 						$value['istoday'] = 1;
// 					}
// 					$key = sprintf("%02d", $value['birthmonth']).sprintf("%02d", $value['birthday']);
// 					$birthlist[$key][] = $value;
// 					ksort($birthlist);
// 				}
// 			}
		
// 			//���
// 			$uch['space']['creditstar'] = getstar($uch['space']['credit']);
		
// 			//����
// 			$uch['space']['domainurl'] = space_domain($uch['space']);
// 		}
		
// 		//ʵ����
// 		realname_get();
		
// 		//feed�ϲ�
// 		$list = array();
// 		if(empty($uch['space']['feedfriend'])) {
// 			foreach ($feed_list as $value) {
// 				$value = mkfeed($value);
// 				if($value['dateline']>=$_SGLOBAL['today']) {
// 					$list['today'][] = $value;
// 				} elseif ($value['dateline']>=$_SGLOBAL['today']-3600*24) {
// 					$list['yesterday'][] = $value;
// 				} else {
// 					$theday = sgmdate('Y-m-d', $value['dateline']);
// 					$list[$theday][] = $value;
// 				}
// 			}
// 		} else {
// 			foreach ($feed_list as $values) {
// 				$actors = array();
// 				$a_value = array();
// 				foreach ($values as $value) {
// 					if(empty($a_value)) {
// 						$a_value = $value;
// 					}
// 					$actors[] = "<a href=\"space.php?uid=$value[uid]\">".$_SN[$value['uid']]."</a>";
// 				}
// 				$a_value = mkfeed($a_value, $actors);
// 				if($a_value['dateline']>=$_SGLOBAL['today']) {
// 					$list['today'][] = $a_value;
// 				} elseif ($a_value['dateline']>=$_SGLOBAL['today']-3600*24) {
// 					$list['yesterday'][] = $a_value;
// 				} else {
// 					$theday = sgmdate('Y-m-d', $a_value['dateline']);
// 					$list[$theday][] = $a_value;
// 				}
// 			}
// 		}
		
// 		//��ø���ģ��
// 		$templates = $default_template = array();
// 		$tpl_dir = sreaddir(S_ROOT.'./template');
// 		foreach ($tpl_dir as $dir) {
// 			if(file_exists(S_ROOT.'./template/'.$dir.'/style.css')) {
// 				$tplicon = file_exists(S_ROOT.'./template/'.$dir.'/image/template.gif')?'template/'.$dir.'/image/template.gif':'image/tlpicon.gif';
// 				$tplvalue = array('name'=> $dir, 'icon'=>$tplicon);
// 				if($dir == $_SCONFIG['template']) {
// 					$default_template = $tplvalue;
// 				} else {
// 					$templates[$dir] = $tplvalue;
// 				}
// 			}
// 		}
// 		$_TPL['templates'] = $templates;
// 		$_TPL['default_template'] = $default_template;
		
// 		//��ǩ����
// 		$my_actives = array(in_array($_GET['filter'], array('site','myapp'))?$_GET['filter']:'all' => ' class="active"');
// 		$actives = array(in_array($_GET['view'], array('me','all'))?$_GET['view']:'we' => ' class="active"');
		
// 		if(empty($cp_mode)) include_once template("space_feed");
		
// 		//ɸѡ
// 		function ckicon_uid($feed) {
// 			global $_SGLOBAL, $uch['space'], $_SCONFIG;
		
// 			if($uch['space']['filter_icon']) {
// 				$key = $feed['icon'].'|0';
// 				if(in_array($key, $uch['space']['filter_icon'])) {
// 					return false;
// 				} else {
// 					$key = $feed['icon'].'|'.$feed['uid'];
// 					if(in_array($key, $uch['space']['filter_icon'])) {
// 						return false;
// 					}
// 				}
// 			}
// 			if(empty($_GET['filter']) && empty($feed['appid']) && empty($_SGLOBAL['my_userapp'][$feed['icon']])) {
// 				//�����ʾ����
// 				$_SGLOBAL['feedfilter'][$feed['icon']]++;
// 				if($_SGLOBAL['feedfilter'][$feed['icon']] > $_SCONFIG['feedfilternum']) return false;
// 			}
// 			return true;
// 		}
		
		$this->template->build('home');
	}
	
	public function index()
	{
		$uch = $this->load->get_var('uch');		
		$uch['space']['isfriend'] = $uch['space']['self'];
		if($uch['space']['friends'] && in_array($uch['supe_uid'], $uch['space']['friends'])) {
			$uch['space']['isfriend'] = 1;
		}
		
		$uch['space']['sex_org'] = $uch['space']['sex'];
		if($this->space_m->ckprivacy('profile')) {
			$uch['space']['showprofile'] = 1;
			$uch['space']['sex'] = $uch['space']['sex']=='1'?'<a href="network.php?ac=space&sex=1&searchmode=1">'.lang('man').'</a>':($uch['space']['sex']=='2'?'<a href="network.php?ac=space&sex=2&searchmode=1">'.lang('woman').'</a>':'');
			$uch['space']['birthday'] = ($uch['space']['birthyear']?"$uch[space][birthyear]".lang('year'):'').($uch['space']['birthmonth']?"$uch[space][birthmonth]".lang('month'):'').($uch['space']['birthday']?"$uch[space][birthday]".lang('day'):'');
			$uch['space']['marry'] = $uch['space']['marry']=='1'?'<a href="network.php?ac=space&marry=1&searchmode=1">'.lang('unmarried').'</a>':($uch['space']['marry']=='2'?'<a href="network.php?ac=space&marry=2&searchmode=1">'.lang('married').'</a>':'');
			$uch['space']['birth'] = trim(($uch['space']['birthprovince']?"<a href=\"network.php?ac=space&birthprovince=".rawurlencode($uch['space']['birthprovince'])."&searchmode=1\">$uch[space][birthprovince]</a>":'').($uch['space']['birthcity']?" <a href=\"network.php?ac=space&birthcity=".rawurlencode($uch['space']['birthcity'])."&searchmode=1\">$uch[space][birthcity]</a>":''));
			$uch['space']['reside'] = trim(($uch['space']['resideprovince']?"<a href=\"network.php?ac=space&resideprovince=".rawurlencode($uch['space']['resideprovince'])."&searchmode=1\">$uch[space][resideprovince]</a>":'').($uch['space']['residecity']?" <a href=\"network.php?ac=space&residecity=".rawurlencode($uch['space']['residecity'])."&searchmode=1\">$uch[space][residecity]</a>":''));
			$uch['space']['qq'] = empty($uch['space']['qq'])?'':"<a target=\"_blank\" href=\"http://wpa.qq.com/msgrd?V=1&Uin=$uch[space][qq]&Site=$uch[space][username]&Menu=yes\">$uch[space][qq]</a>";
			@include_once(S_ROOT.'./data/data_profilefield.php');
			$fields = empty($uch['profilefield'])?array():$uch['profilefield'];
		} else {
			$uch['space']['showprofile'] = 0;
		}
				
		if($uch['space']['spacenote']) {
			$uch['space']['spacenote'] = getstr($uch['space']['spacenote'], 50);
		}
					
		if(!$uch['space']['self']) {
			$this->db->set('num', 'num+1', FALSE)->update('friend', array(), array('uid'=>$uch['global']['supe_uid'], 'fuid'=>$uch['space']['uid']));
		}
	
		
		$uch['ad'] = array();
	
		
		if(!$uch['space']['self']) {
			$this->db->set('viewnum', 'viewnum+1', FALSE)->update('space', array(), array('uid'=>$uch['space']['uid']));
		}
	
		$_GET['view'] = 'me';

		$this->load->vars('uch', $uch);
		$this->load->vars('nosidebar', 1);
		$this->template->build('index');
	}
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */