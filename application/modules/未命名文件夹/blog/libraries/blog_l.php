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
class Blog_l 
{	
	protected $CI;
	
	public function __construct()
	{
		$this->CI = & get_instance();
		$this->CI->load->library(
			array(
				'config/config_l',
				'space/space_l',
				//'uch',
			)
		);
		$this->CI->load->model(
			array(
				'blog/blog_m',
				'blog/blogfield_m',
				'blog/class_m',
			)
		);
		$this->CI->load->helper(
				array(
						'global', 'uch', 'date'
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
		
		if($blog = $this->CI->blog_m->where($key, $value)->get('blog')->row_array())
		{
			$blogfield = $this->CI->blogfield_m->where('blogid', $blog['blogid'])->get('blogfield')->row_array();
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
		
	public function lists($params)
	{
		extract($params);
		$uid = isset($uid)?$uid:'';
		$view = isset($view)?$view:'all';		
		$classid = isset($classid)?$classid:0;		
		$friend = isset($friend)?$friend:0;	
		$orderby = isset($orderby)?$orderby:'dateline DESC';
		$offset = isset($offset)?$offset:0;
		$length = isset($length)?$length:10;
		
		$result = $wherearr = $classarr = $userarr = array();
		$list = $fieldlist = array();
		$count = $pricount = 0;

		$feedfriend = $this->CI->space_l->feedfriend;
		if($view == 'all')
		{
			$wherearr['friend'] = 0;	
			$list = $this->CI->blog_m->where($wherearr)->get('blog')->result_array();
		}
		elseif($view == 'me')
		{
			$wherearr['uid'] = $uid;
			if($friend)
			{
				$wherearr['friend'] = $friend;
			}
			if($classid) 
			{
				$wherearr['classid'] = $classid;
				
			}
			$cachekey = "class_{$uid}";
			if( ! $classarr = $this->CI->cache->get($cachekey))
			{
				$classarr = $this->CI->class_m->where('uid', $uid)->get('class')->result_array();
				$this->CI->cache->save($cachekey, $classarr);
			}
			$list = $this->CI->blog_m->where($wherearr)->get('blog')->result_array();
		}
		else
		{
			$feedfriend = $this->CI->space_l->feedfriend;
			if($friend AND $feedfriend)
			{
				$list = $this->CI->blog_m->where('friend', $friend)->where_in('uid', $feedfriend)->get('blog')->result_array();
			}
			elseif($friend)
			{
				$list = $this->CI->blog_m->where('friend', $friend)->get('blog')->result_array();
			}
			elseif($feedfriend)
			{
				$list = $this->CI->blog_m->where_in('uid', $feedfriend)->get('blog')->result_array();
			}
		}
		
		if(count($list)) 
		{
			$blogids = $blogfieldarr = array();
			foreach($list as $value)
			{
				$blogids[] = $value['blogid'];
			}
			if($blogfieldlist = $this->CI->blogfield_m->where_in('blogid', $blogids)->get('blogfield')->result_array())
			{
				foreach($blogfieldlist as $value)
				{
					$blogfieldarr[$value['blogid']] = $value;
				}
			}
			foreach($list as $key=>$value)
			{
				if(isset($blogfieldarr[$value['blogid']]))
				{
					$value = array_merge($value, $blogfieldarr[$value['blogid']]);
				}				
				
				if(ckfriend($value))
				{
					// 					realname_set($value['uid'], $value['username']);
					$value = $this->format_blog($value);
					$list[$key] = $value;
					$userarr[] = array('uid'=>$value['uid'], 'username'=>$value['username']);
				}
				else
				{
					$pricount++;
				}
			}
		}
		$result['total_rows'] = count($list);
		$result['base_url'] = $this->CI->input->get('rewrite');
		$result['list'] = $list;
		$result['classarr'] = $classarr;
		$result['userarr'] = $userarr;
		$result['pricount'] = $pricount;
 		//echo '<pre>';var_dump($result);exit;
		return $result;
	}
	
	public function classlists($uid)
	{
		return $this->CI->class_m->get_rows_by('uid', $uid);
	}
	
	public function classname($classid)
	{
		return $this->CI->class_m->where('classid', $classid)->get('class')->row_array();
	}
	
	function deleteblogs($blogids) {
		global $_SGLOBAL;
	
		//��ȡ������Ϣ
		$spaces = $blogs = $newblogids = array();
		$allowmanage = checkperm('manageblog');
		$query = $_SGLOBAL['db']->query("SELECT * FROM ".tname('blog')." WHERE blogid IN (".simplode($blogids).")");
		while ($value = $_SGLOBAL['db']->fetch_array($query)) {
			if($allowmanage || $value['uid'] == $_SGLOBAL['supe_uid']) {
				$blogs[] = $value;
				$newblogids[] = $value['blogid'];
				//��Ҫ����ͳ��
				//�ռ�
				$spaces[$value['uid']]++;
				//tag
				$tags = array();
				$subquery = $_SGLOBAL['db']->query("SELECT tagid, blogid FROM ".tname('tagblog')." WHERE blogid='$value[blogid]'");
				while ($tag = $_SGLOBAL['db']->fetch_array($subquery)) {
					$tags[] = $tag['tagid'];
				}
				if($tags) {
					$_SGLOBAL['db']->query("UPDATE ".tname('tag')." SET blognum=blognum-1 WHERE tagid IN (".simplode($tags).")");
					$_SGLOBAL['db']->query("DELETE FROM ".tname('tagblog')." WHERE blogid='$value[blogid]'");
				}
			}
		}
		if(empty($blogs)) return array();
	
		//�ռ���
		updatespaces($spaces, 'blog');
	
		//���ɾ��
		$_SGLOBAL['db']->query("DELETE FROM ".tname('blog')." WHERE blogid IN (".simplode($newblogids).")");
		$_SGLOBAL['db']->query("DELETE FROM ".tname('blogfield')." WHERE blogid IN (".simplode($newblogids).")");
	
		//����
		$_SGLOBAL['db']->query("DELETE FROM ".tname('comment')." WHERE id IN (".simplode($newblogids).") AND idtype='blogid'");
	
		//ɾ��ٱ�
		$_SGLOBAL['db']->query("DELETE FROM ".tname('report')." WHERE id IN (".simplode($newblogids).") AND idtype='blog'");
	
		//ɾ���ӡ
		$_SGLOBAL['db']->query("DELETE FROM ".tname('trace')." WHERE blogid IN (".simplode($newblogids).")");
	
		return $blogs;
	}

}

/* End of file Settings.php */