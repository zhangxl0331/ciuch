<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Doing extends Space_Controller {

	function __construct()
	{
		parent::__construct();
	}
	
	public function index()
	{
		$uch = $this->load->get_var('uch');
			
		$perpage = 20;
		$page = empty($_GET['page'])?0:intval($_GET['page']);
		if($page<1) $page=1;
		$start = ($page-1)*$perpage;
		Events::trigger('ckstart', array('start'=>$start, 'perpage'=>$perpage));
		
		$list = array();
		$count = 0;
		
// 		$f_index = '';
		if($_GET['view'] == 'all') {
			
			$count = $this->doing_m->db->count_all_results('doing');
			$list = $this->doing_m->db->order_by('dateline DESC')->get('doing', $perpage, $start)->result_array();
			
		} elseif($_GET['view'] == 'me') {
			
			$count = $this->doing_m->db->where('uid', $uch['space']['uid'])->count_all_results('doing');
			$list = $this->doing_m->db->where('uid', $uch['space']['uid'])->order_by('dateline DESC')->get('doing', $perpage, $start)->result_array();
			
		} else {
			
			$count = $this->doing_m->db->where_in('uid', $uch['space']['feedfriend'])->count_all_results('doing');
			$list = $this->doing_m->db->where_in('uid', $uch['space']['feedfriend'])->order_by('dateline DESC')->get('doing', $perpage, $start)->result_array();
		}
		
		
		
// 		$doid = empty($_GET['doid'])?0:intval($_GET['doid']);
// 		if($doid) {
// 			$count = 1;
// 			$f_index = '';
// 			$wheresql = "doid='$doid'";
// 			$theurl .= "&doid=$doid";
// 		}
		
		
// 		$doids = $clist = $newdoids = array();
// 		if(empty($count)) {
// 			$count = $this->doing_m->db->query("SELECT COUNT(*) FROM ".$this->doing_m->db->dbprefix."doing WHERE $wheresql")->num_rows();
// 		}
		if($list)
		{
			foreach($list as $key=>$value)
			{
				$doids[] = $value['doid'];
			}
				
		}

		
// 		if($doid) {
// 			$dovalue = empty($list)?array():$list[0];
// 			if($dovalue) {
// 				if($dovalue['uid'] == $_SGLOBAL['supe_uid']) {
// 					$actives = array('me'=>' class="active"');
// 				} else {
// 					$space = getspace($dovalue['uid']);//�Է��Ŀռ�
// 					$actives = array('all'=>' class="active"');
// 				}
// 			}
// 		}
		

// 		if($doids) {
// 			$values = array();
// 			$query = $_SGLOBAL['db']->query("SELECT * FROM ".tname('docomment')." WHERE doid IN (".simplode($doids).")");
// 			while ($value = $_SGLOBAL['db']->fetch_array($query)) {
// 				$values[$value['dateline']] = $value;
// 			}
			
// 			ksort($values);
			
// 			include_once(S_ROOT.'./source/class_tree.php');
// 			$tree = new tree();
// 			foreach ($values as $value) {
// 				realname_set($value['uid'], $value['username']);
// 				$newdoids[$value['doid']] = $value['doid'];
// 				if(empty($value['upid'])) {
// 					$value['upid'] = "do$value[doid]";
// 				}
// 				$tree->setNode($value['id'], $value['upid'], $value);
// 			}
// 		}
		
// 		foreach ($newdoids as $cdoid) {
// 			$values = $tree->getChilds("do$cdoid");
// 			foreach ($values as $key => $id) {
// 				$one = $tree->getValue($id);
// 				$one['layer'] = $tree->getLayer($id) * 2;
// 				$clist[$cdoid][] = $one;
// 			}
// 		}
		

// 		$multi = multi($count, $perpage, $page, $theurl);
		
		
		$moodlist = array();
		if($uch['space']['mood'] && empty($start)) {
			$moodlist = $this->space_m->db->select('s.uid,s.username,s.name,s.namestatus,s.mood,s.updatetime,s.groupid,sf.note,sf.sex')
				->from('space s')
				->join('spacefield sf', 'sf.uid=s.uid', 'LEFT')
				->where(array('s.mood'=>$uch['space']['mood'], 's.uid !='=>$uch['space']['uid']))
				->order_by('s.updatetime DESC')
				->get('', 12, 0)
				->result_array();
		}
		
// 		realname_get();
		$uch['doing']['count'] = $count;
		$uch['doing']['list'] = $list;
		$uch['mood']['list'] = $moodlist;
		$this->load->vars('uch', $uch);
		$this->template->build('doing');
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

}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */