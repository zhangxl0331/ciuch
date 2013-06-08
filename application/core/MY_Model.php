<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * A base model to provide the basic CRUD actions for all models that inherit
 * from it.
 *
 * @author Jamie Rumbelow <http://jamierumbelow.net>
 * @author Phil Sturgeon <http://philsturgeon.co.uk>
 * @author Dan Horrigan <http://dhorrigan.com>
 * @author Jerel Unruh <http://unruhdesigns.com>
 * @license GPLv3 <http://www.gnu.org/licenses/gpl-3.0.txt>
 * @link http://github.com/philsturgeon/codeigniter-base-model
 * @version 1.3
 * @copyright Copyright (c) 2009, Jamie Rumbelow <http://jamierumbelow.net>
 * @package PyroCMS\Core\Libraries
 */
class MY_Model extends CI_Model
{
	protected $_db;
	
	/**
	 * The class constructor, tries to guess the table name.
	 *
	 * @author Jamie Rumbelow
	 */
	public function __construct()
	{					
		parent::__construct();
	}
	
	function __get($key)
	{		
		$CI =& get_instance();
		$CI->load->database($this->_db, FALSE, TRUE);
		return $CI->$key;
	}	

	function __call($method, $args=array())
	{
		return call_user_func(array($this->db, $method));
	}
}