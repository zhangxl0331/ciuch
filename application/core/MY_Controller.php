<?php defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH."third_party/MX/Controller.php";

/**
 * Code here is run before ALL controllers
 * 
 * @package PyroCMS\Core\Controllers 
 */
class MY_Controller extends MX_Controller
{
	/**
	 * No longer used globally
	 * 
	 * @deprecated remove in 2.2
	 */
	protected $data;

	/**
	 * The name of the module that this controller instance actually belongs to.
	 *
	 * @var string 
	 */
	public $module;

	/**
	 * The name of the controller class for the current class instance.
	 *
	 * @var string
	 */
	public $controller;

	/**
	 * The name of the method for the current request.
	 *
	 * @var string 
	 */
	public $method;

	/**
	 * Load and set data for some common used libraries.
	 */
	public function __construct()
	{
		parent::__construct();

		$this->benchmark->mark('my_controller_start');
		
		$this->load->database();
		
		$this->load->model('user/user_m');
		$this->user = $this->user_m->checkauth();
		$this->load->vars('user', $this->user);
		
		$this->load->model('config/config_m');
		$this->config = $this->config_m->config_cache();
		$this->load->vars('config', $this->config);
		
		$this->benchmark->mark('my_controller_end');
	}
}