<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Mtag extends Space_Controller {

	function __construct()
	{
		parent::__construct();
	}
	
	public function index()
	{	
		if(intval($this->input->get('id')))
		{
			$this->template->build('view');
		}
		else 
		{
			$this->template->build('list');
		}
	}
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */