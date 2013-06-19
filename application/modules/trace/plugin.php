<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Global Plugin
 *
 * Make global constants available as tags
 * 
 * @author		PyroCMS Dev Team
 * @package		PyroCMS\Core\Plugins
 */
class Plugin_Trace extends Plugin
{
	public function __construct()
	{
		$this->load->library(array('trace/trace_l'));
	}
	
	/**
	 * Execute whitelisted php functions
	 *
	 * {{ helper:foo parameter1="bar" parameter2="bar" }}
	 * NOTE: the attribute name is irrelevant as only 
	 * the values are concatenated and passed as arguments
	 *
	 */
// 	public function __call($name, $args)
// 	{
// 		$this->load->library('blog/blog_l');
		
// 		return $this->blog_l->$name;
// 	}
	
	public function lists()
	{
		$id = $this->attribute('id', $this->input->get('id'));		
		$orderby = $this->attribute('orderby', 'dateline');
		$direction = $this->attribute('direction', 'DESC');
		$offset = $this->attribute('offset', 0);
		$length = $this->attribute('length', 18);
		
		return $this->trace_l->lists();
	}
}