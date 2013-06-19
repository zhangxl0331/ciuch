<?php defined('BASEPATH') OR exit('No direct script access allowed');
class Events_Privacy
{
    protected $CI;
    
    public function __construct()
    {
        $this->CI = get_instance();       
        $this->CI->load->helper(array('cookie', 'uch'));
        $this->CI->load->model(array('config/config_m'));
        Events::register('ckprivacy', array($this, 'ckprivacy'));
     }
    
    // this will be triggered by the Events::trigger('public_controller') code in Public_Controller.php
   
    
    function ckprivacy($data = array()) {
    	extract($data);
    	$result = false;
    	if($feedmode) {
    		if(!empty($space['privacy']['feed'][$type])) {
    			$result = true;
    		}
    	} elseif($space['self']){
    		$result = true;
    	} else {
    		if(empty($space['privacy']['view'][$type])) {
    			$result = true;
    		}
    		if(!$result && $space['privacy']['view'][$type] == 1) {
    			if(!isset($space['isfriend'])) {
    				$space['isfriend'] = $space['self'];
    				if($space['friends'] && in_array($uid, $space['friends'])) {
    					$space['isfriend'] = 1;
    				}
    			}
    			if($space['isfriend']) {
    				$result = true;
    			}
    		}
    	}
    	
    	return $result;
    }
}
/* End of file events.php */


