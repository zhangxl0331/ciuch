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
class Blog_l1 extends Uch 
{	
	protected $CI;
	
	public function __construct()
	{
		$this->CI = & get_instance();
		$this->CI->load->model(
			array(
				'blog/blog_m',
			)
		);
		$this->CI->load->helper(
				array(
						'global',
				)
		);
	}

	/**
	 * Getter
	 *
	 * Gets the setting value requested
	 *
	 * @param	string	$name
	 */
	public function __get($var)
	{
		$method = strtolower(preg_replace('/(_l|_lib)?$/', '', get_class($this)));
	
		if (is_callable(array($this, $method)))
		{
			$callback = call_user_func(array($this, $method));
			if(isset($callback[$var]))
			{
				return $callback[$var];
			}
		}
	
		return FALSE;
	}
	
	private function blog($value='', $key='blogid')
	{
		if( ! $value)
		{
			if($value = $this->CI->input->get('id'))
			{
				$key = 'blogid';
			}
		}
		
		if($blog = $this->CI->blog_m->get_row_by($key, $value))
		{
			$blogfield = $this->CI->blogfield_m->get_row($blog['blogid']);
			$blog = array_merge($blog, $blogfield);
			
			return $this->format_blog($blog);
		}
		
		return FALSE;
	}
	
	public function format_blog($blog)
	{		
		if( ! empty($blog['pic']))
		{
			$blog['pic'] = $this->CI->uch->mkpicurl($blog);
		}
		if( ! empty($blog['message']))
		{
			$blog['message'] = bbcode($blog['message']);
		}
		if( ! empty($blog['tag']))
		{
			$blog['tag'] = unserialize($blog['tag']);
		}
		if(isset($blog['related']))
		{
		$uc_tagrelatedtime = $this->CI->config_l->uc_tagrelatedtime;
		if($uc_tagrelatedtime && (now() - $blog['relatedtime'] > $uc_tagrelatedtime))
		{
			$blog['related'] = '';
		}
// 		if( ! empty($blog->tag) && empty($blog->related))
// 		{
// 			@include_once(S_ROOT.'./data/data_tagtpl.php');

// 			$b_tagids = $b_tags = $blog->related = array();
// 			$tag_count = -1;
// 			foreach ($blog->tag as $key => $value)
// 			{
// 				$b_tags[] = $value;
// 				$b_tagids[] = $key;
// 				$tag_count++;
// 			}
// 			if( ! $uc_tagrelated = $this->get('config', 'uc_tagrelated'))
// 			{
// 				if(!empty($_SGLOBAL['tagtpl']['limit']))
// 				{
// 					include_once(S_ROOT.'./uc_client/client.php');
// 					$tag_index = mt_rand(0, $tag_count);
// 					$blog['related'] = uc_tag_get($b_tags[$tag_index], $_SGLOBAL['tagtpl']['limit']);
// 				}
// 			}
// 			else
// 			{
// 				$tag_blogids = array();
// 				$query = $_SGLOBAL['db']->query("SELECT DISTINCT blogid FROM ".tname('tagblog')." WHERE tagid IN (".simplode($b_tagids).") AND blogid<>'$blog[blogid]' ORDER BY blogid DESC LIMIT 0,10");
// 				while ($value = $_SGLOBAL['db']->fetch_array($query))
// 				{
// 					$tag_blogids[] = $value['blogid'];
// 				}
// 				if($tag_blogids)
// 				{
// 					$query = $_SGLOBAL['db']->query("SELECT uid,username,subject,blogid FROM ".tname('blog')." WHERE blogid IN (".simplode($tag_blogids).")");
// 					while ($value = $_SGLOBAL['db']->fetch_array($query))
// 					{
// 						realname_set($value['uid'], $value['username']);
// 						$value['url'] = "space.php?uid=$value[uid]&do=blog&id=$value[blogid]";
// 						$blog['related'][UC_APPID]['data'][] = $value;
// 					}
// 					$blog['related'][UC_APPID]['type'] = 'UCHOME';
// 				}
// 			}
// 			if(!empty($blog['related']) && is_array($blog['related']))
// 			{
// 				foreach ($blog['related'] as $appid => $values)
// 				{
// 					if(!empty($values['data']) && $_SGLOBAL['tagtpl']['data'][$appid]['template'])
// 					{
// 						foreach ($values['data'] as $itemkey => $itemvalue)
// 						{
// 							if(!empty($itemvalue) && is_array($itemvalue))
// 							{
// 								$searchs = $replaces = array();
// 								foreach (array_keys($itemvalue) as $key)
// 								{
// 									$searchs[] = '{'.$key.'}';
// 									$replaces[] = $itemvalue[$key];
// 								}
// 								$blog['related'][$appid]['data'][$itemkey]['html'] = stripslashes(str_replace($searchs, $replaces, $_SGLOBAL['tagtpl']['data'][$appid]['template']));
// 							}
// 							else
// 							{
// 								unset($blog['related'][$appid]['data'][$itemkey]);
// 							}
// 						}
// 					}
// 					else
// 					{
// 						$blog['related'][$appid]['data'] = '';
// 					}
// 					if(empty($blog['related'][$appid]['data']))
// 					{
// 						unset($blog['related'][$appid]);
// 					}
// 				}
// 			}
// 			updatetable('blogfield', array('related'=>addslashes(serialize(sstripslashes($blog['related']))), 'relatedtime'=>$_SGLOBAL['timestamp']), array('blogid'=>$blog['blogid']));//����
// 		}
// 		else
// 		{
// 			$blog['related'] = empty($blog['related'])?array():unserialize($blog['related']);
// 		}
		}
		return $blog;
	}
		
	public function lists($uid, $view='', $orderby='blog.dateline DESC', $offset=0, $length=10)
	{
		$result = $wherearr = array();
		$list = array();
		$count = $pricount = 0;

		$feedfriend = $this->get('space', 'feedfriend');
		if($view == 'all')
		{
			$wherearr = array('friend'=>0);
			$count = $this->CI->blog_m->db->where($wherearr)->count_all_results('blog');
			$return = $this->CI->blog_m->db->where($wherearr)->get('blog')->result_array();
			$theurl = "blog?uid=$uid&view=all";		
		}
		elseif($view == 'me')
		{
			$wherearr = array('uid'=>$uid);
			$count = $this->CI->blog_m->db->where($wherearr)->count_all_results('blog');
			$return = $this->CI->blog_m->db->where($wherearr)->get('blog')->result_array();
			$theurl = "blog?uid=$uid&view=me";
			$actives = array('me'=>' class="active"');
		}
		else
		{
			$wherearr = array('uid'=>$feedfriend);
			$count = $this->CI->blog_m->db->where_in($wherearr)->count_all_results('blog');
			$return = $this->CI->blog_m->db->where_in($wherearr)->get('blog')->result_array();
			$theurl = "blog?uid=$uid";
			$f_index = 'USE INDEX(dateline)';
		}
		
		if($count) 
		{
			foreach($return as $value)
			{
				if($this->ckfriend($value))
				{
					// 						realname_set($value['uid'], $value['username']);
					$value = $this->format_blog($value);
					$list[] = $value;
					$userlist[$value['uid']] = $value['username'];
				}
				else
				{
					$pricount++;
				}
			}
			
			
		}
		$result['total_rows'] = $count;
		$result['base_url'] = $theurl;
		$result['list'] = $list;
		$result['pricount'] = $pricount;
// 		echo '<pre>';var_dump($result);exit;
		return $result;
	}
	
	public function classlist($uid)
	{
		return $this->CI->class_m->get_rows_by('uid', $uid);
	}
	
	public function classname($classid)
	{
		return $this->CI->class_m->db->where('classid', $classid)->get()->row(2);
	}

}

/* End of file Settings.php */