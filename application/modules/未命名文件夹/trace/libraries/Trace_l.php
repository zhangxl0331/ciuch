<?php defined('BASEPATH') or exit('No direct script access allowed');
/**
 * PyroCMS Settings Library
 *
 * Allows for an easy interface for site settings
 *
 * @author		Dan Horrigan <dan@dhorrigan.com>
 * @author		PyroCMS Dev Team
 * @package		PyroCMS\Core\Modules\Settings\Libraries
 */
class Trace_l extends Uch 
{	
	protected $CI;
	
	public function __construct()
	{
		$this->CI = & get_instance();
		$this->CI->load->model(
			array(
				'trace/trace_m',
			)
		);
	}
	
	public function lists($uid='', $id='', $type='')
	{
		if( ! $uid)
		{
			if( ! $uid = $this->CI->input->get('uid'))
			{
				return FALSE;
			}
		}
		if( ! $id)
		{
			if( ! $id = $this->CI->input->get('id'))
			{
				return FALSE;
			}
		}
		
		return $this->CI->trace_m->get_row_by(array('uid'=>$uid, 'blogid'=>$id));
	}

}

/* End of file Settings.php */