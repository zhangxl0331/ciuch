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
		if($start >= $count)
		{
			redirect(site_url('doing/index/'.$user['uid']).'/'.ceil($count/$perpage));
		}
		$list = $this->doing_m->db->where('uid', $uid)->order_by('dateline DESC')->get('doing', $perpage, $start)->result_array();

		$config['base_url'] = site_url('doing/index/'.$user['uid']);
		$config['total_rows'] = $count;
		$config['per_page'] = $perpage;
		$config['use_page_numbers'] = TRUE;
		$config['uri_segment'] = 4;
		$config['num_links'] = 5;
		$this->load->library('pagination');
		$this->pagination->initialize($config);
		$pager = $this->pagination->create_links();
		$data = array('user'=>$user, 'count'=>$count, 'list'=>$list, 'pager'=>$pager);
		$this->template->build('index', $data);
	}
	
	public function add()
	{
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
		
		$this->load->library('form_validation');
		
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

	public function delete($id)
	{
		$config = $this->load->get_var('config');
		$auth = $this->load->get_var('auth');
		if(empty($auth['uid']))
		{
			redirect(site_url('member/'.$config['login_action']));
		}
		
		$this->load->library('form_validation');
		$this->form_validation->set_rules('deletesubmit', '', 'required');
		if($this->form_validation->run())
		{		
			if($this->doing_m->deletedoings($id))
			{
// 				$this->comment_m->deletecomments($id);
			}
			
			redirect($_POST['refer']);
		}
		
		$this->load->vars('id', $id);
		$this->template->build('delete');
	}
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */