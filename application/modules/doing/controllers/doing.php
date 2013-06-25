<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Doing extends MY_Controller {

	function __construct()
	{
		parent::__construct();
		$this->load->model('doing/doing_m');
	}
	
	public function index($uid)
	{
		$user = $this->member_m->getspace($uid);
			
		$perpage = 2;
		$page = intval($this->uri->segment(4));
		if($page<1) $page=1;
		$start = ($page-1)*$perpage;
		Events::trigger('ckstart', array('start'=>$start, 'perpage'=>$perpage));
		
		$list = array();
		$count = 0;
		
// 		$f_index = '';

		$count = $this->doing_m->db->where('uid', $uid)->count_all_results('doing');
		$list = $this->doing_m->db->where('uid', $uid)->order_by('dateline DESC')->get('doing', $perpage, $start)->result_array();

		$config['base_url'] = site_url('doing/index/'.$user['uid']);
		$config['total_rows'] = $count;
		$config['per_page'] = $perpage;
// 		$config['cur_page'] = $page;
		$config['uri_segment'] = 4;
		$config['num_links'] = 5;
		$this->load->library('pagination');
		$this->pagination->initialize($config);
		$pager= $this->pagination->create_links();
		
		$this->load->vars('user', $user);
		$this->load->vars('count', $count);
		$this->load->vars('list', $list);
		$this->load->vars('pager', $pager);
		$this->template->build('index');
	}
	
	public function cp()
	{
		$doid = empty($_GET['doid'])?0:intval($_GET['doid']);
		$id = empty($_GET['id'])?0:intval($_GET['id']); 
		
		if(empty($_POST['refer'])) $_POST['refer'] = base_url('doing?view=me');
		
		if(submitcheck('addsubmit')) {
		
			$add_doing = 1;
			if(empty($_POST['spacenote'])) {
				if(!checkperm('allowdoing')) {
					showmessage('no_privilege');
				}
				
				//ʵ����֤
				ckrealname('doing');
				
				//���û���ϰ
				cknewuser();
			
				//��֤��
				if(checkperm('seccode') && !ckseccode($_POST['seccode'])) {
					showmessage('incorrect_code');
				}
			
				//�ж��Ƿ����̫��
				$waittime = interval_check('post');
				if($waittime > 0) {
					showmessage('operating_too_fast', '', 1, array($waittime));
				}
			} else {
				if(!checkperm('allowdoing')) {
					$add_doing = 0;
				}
				if(checkperm('seccode') && !ckseccode($_POST['seccode'])) {
					$add_doing = 0;
				}
				//ʵ��
				if(!ckrealname('doing', 1)) {
					$add_doing = 0;
				}
				//���û�
				if(!cknewuser(1)) {
					$add_doing = 0;
				}
				$waittime = interval_check('post');
				if($waittime > 0) {
					$add_doing = 0;
				}
			}
			
			//��ȡ����
			$mood = 0;
			preg_match("/\[em\:(\d+)\:\]/s", $_POST['message'], $ms);
			$mood = empty($ms[1])?0:intval($ms[1]);
		
			$message = getstr($_POST['message'], 200, 1, 1, 1);
			//�滻����
			$message = preg_replace("/\[em:(.+?):]/is", "<img src=\"image/face/\\1.gif\" class=\"face\">", $message);
			$message = preg_replace("/\<br.*?\>/is", ' ', $message);
			
			if(strlen($message) < 1) {
				showmessage('should_write_that');
			}
			
			if($add_doing) {
				$setarr = array(
					'uid' => $_SGLOBAL['supe_uid'],
					'username' => $_SGLOBAL['supe_username'],
					'dateline' => $_SGLOBAL['timestamp'],
					'message' => $message,
					'mood' => $mood,
					'ip' => getonlineip()
				);
				//���
				$newdoid = inserttable('doing', $setarr, 1);
			}
			
			//���¿ռ�note
			$setarr = array(mood=>$mood, 'updatetime'=>$_SGLOBAL['timestamp']);
			updatetable('space', $setarr, array('uid'=>$_SGLOBAL['supe_uid']));
			
			$note_text = getstr($_POST['message'], 200, 1, 1, 1, 0, -1);
			$note_message = strlen($message)>200?$note_text:$message;
			$setarr = array('note'=>$note_message);
			if(!empty($_POST['spacenote'])) {
				$setarr['spacenote'] = $note_text;
			}
			updatetable('spacefield', $setarr, array('uid'=>$_SGLOBAL['supe_uid']));
			
		
			//�¼�feed
			$fs = array();
			$fs['icon'] = 'doing';
			$fs['title_template'] = cplang('feed_doing_title');
			$fs['title_data'] = array('message'=>$message);
			
			$fs['body_template'] = '';
			$fs['body_data'] = array('doid'=>$newdoid);
			$fs['body_general'] = '';
		
			if($add_doing && ckprivacy('doing', 1)) {
				feed_add($fs['icon'], $fs['title_template'], $fs['title_data'], $fs['body_template'], $fs['body_data'], $fs['body_general']);
			}
		
			showmessage('do_success', 'space.php?do=doing&view=me', 0);
		
		} elseif (submitcheck('commentsubmit')) {
			
			if(!checkperm('allowdoing')) {
				showmessage('no_privilege');
			}
			
			//ʵ����֤
			ckrealname('doing');
			
			//���û���ϰ
			cknewuser();
			
			//�ж��Ƿ����̫��
			$waittime = interval_check('post');
			if($waittime > 0) {
				showmessage('operating_too_fast', '', 1, array($waittime));
			}
			
			$message = getstr($_POST['message'], 200, 1, 1, 1);
			//�滻����
			$message = preg_replace("/\[em:(.+?):]/is", "<img src=\"image/face/\\1.gif\" class=\"face\">", $message);
			$message = preg_replace("/\<br.*?\>/is", ' ', $message);
			if(strlen($message) < 1) {
				showmessage('should_write_that');
			}
			
			$updo = array();
			if($id) {
				$query = $_SGLOBAL['db']->query("SELECT * FROM ".tname('docomment')." WHERE id='$id'");
				$updo = $_SGLOBAL['db']->fetch_array($query);
			}
			if(empty($updo) && $doid) {
				$query = $_SGLOBAL['db']->query("SELECT * FROM ".tname('doing')." WHERE doid='$doid'");
				$updo = $_SGLOBAL['db']->fetch_array($query);
			}
			if(empty($updo)) {
				showmessage('docomment_error');
			} else {
				//����
				if(isblacklist($updo['uid'])) {
					showmessage('is_blacklist');
				}
			}
			
			$updo['id'] = intval($updo['id']);
			$updo['grade'] = intval($updo['grade']);
			
			$setarr = array(
				'doid' => $updo['doid'],
				'upid' => $updo['id'],
				'uid' => $_SGLOBAL['supe_uid'],
				'username' => $_SGLOBAL['supe_username'],
				'dateline' => $_SGLOBAL['timestamp'],
				'message' => $message,
				'ip' => getonlineip(),
				'grade' => $updo['grade']+1
			);
			
			//���㼶
			if($updo['grade'] >= 3) {
				$setarr['upid'] = $updo['upid'];//��ĸһ������
			}
		
			$newid = inserttable('docomment', $setarr, 1);
			
			//���»ظ���
			$_SGLOBAL['db']->query("UPDATE ".tname('doing')." SET replynum=replynum+1 WHERE doid='$updo[doid]'");
			
			//֪ͨ
			if($updo['uid'] != $_SGLOBAL['supe_uid']) {
				$note = cplang('note_doing_reply', array("space.php?do=doing&doid=$updo[doid]&highlight=$newid"));
				notification_add($updo['uid'], 'doing', $note);
			}
		
			$_POST['refer'] = preg_replace("/((\#|\&highlight|\-highlight|\.html).*?)$/", '', $_POST['refer']);
			if(strexists($_POST['refer'], '?')) {
				$_POST['refer'] .= "&highlight={$newid}#dl{$updo[doid]}";
			} else {
				$_POST['refer'] .= "-highlight-{$newid}.html#dl{$updo[doid]}";
			}
			showmessage('do_success', $_POST['refer'], 0);
		
		}
		
		if($_GET['op'] == 'delete') {
			
			if(submitcheck('deletesubmit')) {
				if($id) {
					$allowmanage = checkperm('managedoing');
					$query = $_SGLOBAL['db']->query("SELECT dc.*, d.uid as duid FROM ".tname('docomment')." dc, ".tname('doing')." d WHERE dc.id='$id' AND dc.doid=d.doid");
					if($value = $_SGLOBAL['db']->fetch_array($query)) {
						if($allowmanage || $value['uid'] == $_SGLOBAL['supe_uid'] ||  $value['duid'] == $_SGLOBAL['supe_uid'] ) {
							$_SGLOBAL['db']->query("DELETE FROM ".tname('docomment')." WHERE (id='$id' || upid='$id')");
							$replynum = getcount('docomment', array('doid'=>$value['doid']));
							updatetable('doing', array('replynum'=>$replynum), array('doid'=>$value['doid']));
						}
					}
				} else {
					include_once(S_ROOT.'./source/function_delete.php');
					deletedoings(array($doid));
				}
				
				showmessage('do_success', $_POST['refer'], 0);
			}
		}
		
		$this->template->build('cp_doing');
	}
	
	public function add()
	{
		$this->load->library('form_validation');
		
		$config = $this->load->get_var('config');
		$auth = $this->load->get_var('auth');
		$usergroup = $this->load->get_var('usergroup');
		if(empty($auth['uid']))
		{		
			redirect(site_url('member/'.$config['login_action']));
		}

		if(isset($usergroup[$auth['groupid']]['allowdoing']) AND empty($usergroup[$auth['groupid']]['allowdoing']))
		{
			$this->load->vars(array('message'=>lang('no_privilege'), 'url_forword'=>'', 'second'=>1));
			exit($this->template->build('showmessage', array(), TRUE));
		}
		
		if($config['realname'] && empty($auth['namestatus']) && empty($config['name_allowdoing'])) {
			$this->load->vars(array('message'=>lang('no_privilege_realname'), 'url_forword'=>'', 'second'=>1));
			exit($this->template->build('showmessage', array(), TRUE));
		}
		
		if($config['newusertime'] && time()-$auth['dateline']<$config['newusertime']*3600) {
			$this->load->vars(array('message'=>sprintf(lang('no_privilege_realname'), $config['newusertime']), 'url_forword'=>'', 'second'=>1));
			exit($this->template->build('showmessage', array(), TRUE));
		}
		//��Ҫ�ϴ�ͷ��
		if($config['need_avatar'] && empty($auth['avatar'])) {
			$this->load->vars(array('message'=>lang('no_privilege_avatar'), 'url_forword'=>'', 'second'=>1));
			exit($this->template->build('showmessage', array(), TRUE));
		}
		//ǿ�����û����Ѹ���
		if($config['need_friendnum'] && $auth['friendnum']<$config['need_friendnum']) {
			$this->load->vars(array('message'=>sprintf(lang('no_privilege_friendnum'), $config['need_friendnum']), 'url_forword'=>'', 'second'=>1));
			exit($this->template->build('showmessage', array(), TRUE));
		}
		//ǿ�����û����Ѹ���
		if($config['need_email'] && empty($auth['emailcheck'])) {
			$this->load->vars(array('message'=>lang('no_privilege_email'), 'url_forword'=>'', 'second'=>1));
			exit($this->template->build('showmessage', array(), TRUE));
		}
		
		if(isset($usergroup[$auth['groupid']]['seccode']) AND ! empty($usergroup[$auth['groupid']]['seccode']) && strcasecmp($_POST['seccode'], $_POST['verify']) == 0) {
			$this->load->vars(array('message'=>lang('incorrect_code'), 'url_forword'=>'', 'second'=>1));
			exit($this->template->build('showmessage', array(), TRUE));
		}
		
		if(isset($usergroup[$auth['groupid']]['postinterval']) AND !empty($usergroup[$auth['groupid']]['postinterval']))
		{
			if($waittime = $usergroup[$auth['groupid']]['postinterval'] - (time() - $auth['lastpost']) > 0) {
				$this->load->vars(array('message'=>sprintf(lang('operating_too_fast'), $waittime), 'url_forword'=>'', 'second'=>1));
				exit($this->template->build('showmessage', array(), TRUE));
			}
		}
		
		$this->form_validation->set_rules('message', '', 'required');
		if($this->form_validation->run())
		{
			$setarr = array(
				'uid' => $auth['uid'],
				'username' => $auth['username'],
				'dateline' => time(),
				'message' => $_POST['message'],
				'mood' => '',
				'ip' => $this->input->ip_address()
			);
			//���
			$newdoid = $this->doing_m->db->insert('doing', $setarr);
			redirect(site_url('doing/index/'.$auth['uid']));
		}
		else 
		{
			$this->load->vars(array('message'=>lang('should_write_that'), 'url_forword'=>'', 'second'=>1));
			exit($this->template->build('showmessage', array(), TRUE));
		}		
		$this->template->build('add');
	}

}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */