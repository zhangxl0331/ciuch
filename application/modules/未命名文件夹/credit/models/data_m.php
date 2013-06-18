<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Data_m extends MY_Model
{	
	protected $_table = 'data';
	
	public function __construct()
	{
		parent::__construct();
	}
	
	function data_get($var, $isarray=0) 
	{
		$value = $this->db->where('var', $var)->get('data', 1)->first_row('array');
		if($value) 
		{
			return $isarray?$value:$value['datavalue'];
		} 
		else 
		{
			return '';
		}
	}
	
	function data_set($var, $datavalue, $clean=0) 
	{
		if($clean) 
		{
			$this->db->delete('data', array('var'=>$var));
		} 
		else 
		{
			if(is_array($datavalue)) 
			{
				$datavalue = serialize($datavalue);
			}
			$this->db->replace('data', array('var'=>$var, 'datavalue'=>$datavalue, 'dateline'=>time()));
		}
	}

}