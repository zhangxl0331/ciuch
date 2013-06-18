<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

//SQL ADDSLASHES
function saddslashes($string) {
	if(is_array($string)) {
		foreach($string as $key => $val) {
			$string[$key] = saddslashes($val);
		}
	} else {
		$string = addslashes($string);
	}
	return $string;
}

//ȡ��HTML����
function shtmlspecialchars($string) {
	if(is_array($string)) {
		foreach($string as $key => $val) {
			$string[$key] = shtmlspecialchars($val);
		}
	} else {
		$string = preg_replace('/&amp;((#(\d{3,5}|x[a-fA-F0-9]{4})|[a-zA-Z][a-z0-9]{2,5});)/', '&\\1',
			str_replace(array('&', '"', '<', '>'), array('&amp;', '&quot;', '&lt;', '&gt;'), $string));
	}
	return $string;
}

//�ַ���ܼ���
function authcode($string, $operation = 'DECODE', $key = '', $expiry = 0) {

	$ckey_length = 4;	
	$key = md5($key ? $key : UC_KEY);
	$keya = md5(substr($key, 0, 16));
	$keyb = md5(substr($key, 16, 16));
	$keyc = $ckey_length ? ($operation == 'DECODE' ? substr($string, 0, $ckey_length): substr(md5(microtime()), -$ckey_length)) : '';

	$cryptkey = $keya.md5($keya.$keyc);
	$key_length = strlen($cryptkey);

	$string = $operation == 'DECODE' ? base64_decode(substr($string, $ckey_length)) : sprintf('%010d', $expiry ? $expiry + time() : 0).substr(md5($string.$keyb), 0, 16).$string;
	$string_length = strlen($string);

	$result = '';
	$box = range(0, 255);

	$rndkey = array();
	for($i = 0; $i <= 255; $i++) {
		$rndkey[$i] = ord($cryptkey[$i % $key_length]);
	}

	for($j = $i = 0; $i < 256; $i++) {
		$j = ($j + $box[$i] + $rndkey[$i]) % 256;
		$tmp = $box[$i];
		$box[$i] = $box[$j];
		$box[$j] = $tmp;
	}

	for($a = $j = $i = 0; $i < $string_length; $i++) {
		$a = ($a + 1) % 256;
		$j = ($j + $box[$a]) % 256;
		$tmp = $box[$a];
		$box[$a] = $box[$j];
		$box[$j] = $tmp;
		$result .= chr(ord($string[$i]) ^ ($box[($box[$a] + $box[$j]) % 256]));
	}

	if($operation == 'DECODE') {
		if((substr($result, 0, 10) == 0 || substr($result, 0, 10) - time() > 0) && substr($result, 10, 16) == substr(md5(substr($result, 26).$keyb), 0, 16)) {
			return substr($result, 26);
		} else {
			return '';
		}
	} else {
		return $keyc.str_replace('=', '', base64_encode($result));
	}
}

//���cookie
function clearcookie() {	
	$input = &load_class('Input', 'core');
	$input->set_cookie('auth', '', -86400 * 365);
	
	$loader = &load_class('Loader', 'core');
	$uch = $loader->get_var('uch');
	$uch['global']['supe_uid'] = 0;
	$uch['global']['supe_username'] = '';
	$uch['global']['member'] = array();
	
	$loader->vars('uch', $uch);
}

//cookie����
function ssetcookie($var, $value, $life=0) {
	$loader = &load_class('Loader', 'core');
	$_SGLOBAL = $loader->get_var('_SGLOBAL');
	$input = &load_class('Input', 'core');
	$input->set_cookie($var, $value, $life?($_SGLOBAL['timestamp']+$life):0, config_item('cookie_domain'), config_item('cookie_path'), config_item('cookie_prefix'), config_item('cookie_secure'));
}

//��ȡ����IP
function getonlineip($format=0) {
	$loader = &load_class('Loader', 'core');
	$_SGLOBAL = $loader->get_var('_SGLOBAL');

	if(empty($_SGLOBAL['onlineip'])) {
		if(getenv('HTTP_CLIENT_IP') && strcasecmp(getenv('HTTP_CLIENT_IP'), 'unknown')) {
			$onlineip = getenv('HTTP_CLIENT_IP');
		} elseif(getenv('HTTP_X_FORWARDED_FOR') && strcasecmp(getenv('HTTP_X_FORWARDED_FOR'), 'unknown')) {
			$onlineip = getenv('HTTP_X_FORWARDED_FOR');
		} elseif(getenv('REMOTE_ADDR') && strcasecmp(getenv('REMOTE_ADDR'), 'unknown')) {
			$onlineip = getenv('REMOTE_ADDR');
		} elseif(isset($_SERVER['REMOTE_ADDR']) && $_SERVER['REMOTE_ADDR'] && strcasecmp($_SERVER['REMOTE_ADDR'], 'unknown')) {
			$onlineip = $_SERVER['REMOTE_ADDR'];
		}
		preg_match("/[\d\.]{7,15}/", $onlineip, $onlineipmatches);
		$_SGLOBAL['onlineip'] = $onlineipmatches[0] ? $onlineipmatches[0] : 'unknown';
		$loader->vars('_SGLOBAL', $_SGLOBAL);
	}
	if($format) {
		$ips = explode('.', $_SGLOBAL['onlineip']);
		for($i=0;$i<3;$i++) {
			$ips[$i] = intval($ips[$i]);
		}
		return sprintf('%03d%03d%03d', $ips[0], $ips[1], $ips[2]);
	} else {
		return $_SGLOBAL['onlineip'];
	}
}

//�жϵ�ǰ�û���¼״̬
function checkauth() {
	$loader = &load_class('Loader', 'core');
	$_SGLOBAL = $loader->get_var('_SGLOBAL');
	$input = &load_class('Input', 'core');

	if($auth = $input->cookie(config_item('cookie_prefix').'auth')) {
		@list($password, $uid) = explode("\t", authcode($auth, 'DECODE'));
		$_SGLOBAL['supe_uid'] = intval($uid);
		if($_SGLOBAL['supe_uid']) {
			$query = $_SGLOBAL['db']->query("SELECT * FROM ".tname('session')." WHERE uid='$_SGLOBAL[supe_uid]' AND password='$password'");
			if($member = $_SGLOBAL['db']->fetch_array($query)) {
				$_SGLOBAL['supe_username'] = addslashes($member['username']);
				$_SGLOBAL['session'] = $member;
			} else {
				$query = $_SGLOBAL['db']->query("SELECT * FROM ".tname('member')." WHERE uid='$_SGLOBAL[supe_uid]' AND password='$password'");
				if($member = $_SGLOBAL['db']->fetch_array($query)) {
					$_SGLOBAL['supe_username'] = addslashes($member['username']);
					include_once(S_ROOT.'./source/function_space.php');
					insertsession(array('uid' => $_SGLOBAL['supe_uid'], 'username' => $_SGLOBAL['supe_username'], 'password' => $password));//��¼
				} else {
					$_SGLOBAL['supe_uid'] = 0;
				}
			}
		}
	}
	if(empty($_SGLOBAL['supe_uid'])) {
		clearcookie();
	} else {
		$_SGLOBAL['username'] = $member['username'];
	}
}

//��ȡ�û�app�б�
function getuserapp() {
	$uch = &load_class('Loader', 'core')->get_var('uch');
	
	$_SGLOBAL['my_userapp'] = $_SGLOBAL['my_menu'] = array();
	$_SGLOBAL['my_menu_more'] = 0;
	
	if($_SGLOBAL['supe_uid'] && $uch['config']['my_status']) {
		$space = getspace($_SGLOBAL['supe_uid']);
		$showcount=0;
		$query = $_SGLOBAL['db']->query("SELECT * FROM ".tname('userapp')." WHERE uid='$_SGLOBAL[supe_uid]' ORDER BY displayorder DESC", 'SILENT');
		while ($value = $_SGLOBAL['db']->fetch_array($query)) {
			$_SGLOBAL['my_userapp'][$value['appid']] = $value;
			if($value['allowsidenav'] && !isset($_SGLOBAL['userapp'][$value['appid']])) {
				if($space['menunum'] < 5) $space['menunum'] = 10;
				if($space['menunum'] > 100 || $showcount < $space['menunum']) {
					$_SGLOBAL['my_menu'][] = $value;
					$showcount++;
				} else {
					$_SGLOBAL['my_menu_more'] = 1;
				}
			}
		}
	}
}

//��ȡ������
function tname($name) {
	$uch = &load_class('Loader', 'core')->get_var('uch');
	return $_SC['tablepre'].$name;
}

//�Ի���
function showmessage($msgkey, $url_forward='', $second=1, $values=array()) {
	$uch = &load_class('Loader', 'core')->get_var('uch');
	
	obclean();
	
	//ȥ�����
	$_SGLOBAL['ad'] = array();

	if(empty($_SGLOBAL['inajax']) && $url_forward && empty($second)) {
		header("HTTP/1.1 301 Moved Permanently");
		header("Location: $url_forward");
	} else {
		include_once(S_ROOT.'./language/lang_showmessage.php');
		if(isset($_SGLOBAL['msglang'][$msgkey])) {
			$message = lang_replace($_SGLOBAL['msglang'][$msgkey], $values);
		} else {
			$message = $msgkey;
		}
		if($_SGLOBAL['inajax']) {
			if($url_forward) {
				$message = "<a href=\"$url_forward\">$message</a><ajaxok>";
			}
			if($_GET['popupmenu_box']) {
				$message = "<h1>&nbsp;</h1><a href=\"javascript:;\" onclick=\"hideMenu();\" class=\"float_del\">X</a><div class=\"popupmenu_inner\">$message</div>";
			}
			echo $message;
			ob_out();
		} else {
			if($url_forward) {
				$message = "<a href=\"$url_forward\">$message</a><script>setTimeout(\"window.location.href ='$url_forward';\", ".($second*1000).");</script>";
			}
			include template('showmessage');
		}
	}
	exit();
}

//�ж��ύ�Ƿ���ȷ
function submitcheck($var) {
	if(!empty($_POST[$var]) && $_SERVER['REQUEST_METHOD'] == 'POST') {
		if((empty($_SERVER['HTTP_REFERER']) || preg_replace("/https?:\/\/([^\:\/]+).*/i", "\\1", $_SERVER['HTTP_REFERER']) == preg_replace("/([^\:]+).*/", "\\1", $_SERVER['HTTP_HOST'])) && $_POST['formhash'] == formhash()) {
			return true;
		} else {
			return false;
		}
	} else {
		return false;
	}
}

//������
function inserttable($tablename, $insertsqlarr, $returnid=0, $replace = false, $silent=0) {
	$uch = &load_class('Loader', 'core')->get_var('uch');

	$insertkeysql = $insertvaluesql = $comma = '';
	foreach ($insertsqlarr as $insert_key => $insert_value) {
		$insertkeysql .= $comma.'`'.$insert_key.'`';
		$insertvaluesql .= $comma.'\''.$insert_value.'\'';
		$comma = ', ';
	}
	$method = $replace?'REPLACE':'INSERT';
	$_SGLOBAL['db']->query($method.' INTO '.tname($tablename).' ('.$insertkeysql.') VALUES ('.$insertvaluesql.')', $silent?'SILENT':'');
	if($returnid && !$replace) {
		return $_SGLOBAL['db']->insert_id();
	}
}

//�������
function updatetable($tablename, $setsqlarr, $wheresqlarr, $silent=0) {
	$uch = &load_class('Loader', 'core')->get_var('uch');

	$setsql = $comma = '';
	foreach ($setsqlarr as $set_key => $set_value) {
		$setsql .= $comma.'`'.$set_key.'`'.'=\''.$set_value.'\'';
		$comma = ', ';
	}
	$where = $comma = '';
	if(empty($wheresqlarr)) {
		$where = '1';
	} elseif(is_array($wheresqlarr)) {
		foreach ($wheresqlarr as $key => $value) {
			$where .= $comma.'`'.$key.'`'.'=\''.$value.'\'';
			$comma = ' AND ';
		}
	} else {
		$where = $wheresqlarr;
	}
	$_SGLOBAL['db']->query('UPDATE '.tname($tablename).' SET '.$setsql.' WHERE '.$where, $silent?'SILENT':'');
}

//��ȡ�û��ռ���Ϣ
function getspace($key, $indextype='uid', $auto_open=1) {
	$uch = &load_class('Loader', 'core')->get_var('uch');

	$var = "space_{$key}_{$indextype}";
	if(empty($_SGLOBAL[$var])) {
		$space = array();
		$query = $_SGLOBAL['db']->query("SELECT sf.*, s.* FROM ".tname('space')." s LEFT JOIN ".tname('spacefield')." sf ON sf.uid=s.uid WHERE s.{$indextype}='$key'");
		if(!$space = $_SGLOBAL['db']->fetch_array($query)) {
			$space = array();
			if($indextype=='uid' && $auto_open) {
				//�Զ���ͨ�ռ�
				include_once(S_ROOT.'./uc_client/client.php');
				if($user = uc_get_user($key, 1)) {
					include_once(S_ROOT.'./source/function_space.php');
					$space = space_open($user[0], addslashes($user[1]), 0, addslashes($user[2]));
				}
			}
		}
		if($space) {
			$_SN[$space['uid']] = ($uch['config']['realname'] && $space['name'] && $space['namestatus'])?$space['name']:$space['username'];
			$space['self'] = ($space['uid']==$_SGLOBAL['supe_uid'])?1:0;
			
			//���ѻ���
			$space['friends'] = array();
			if(empty($space['friend'])) {
				if($space['friendnum']>0) {
					$fstr = $fmod = '';
					$query = $_SGLOBAL['db']->query("SELECT fuid FROM ".tname('friend')." WHERE uid='$space[uid]' AND status='1'");
					while ($value = $_SGLOBAL['db']->fetch_array($query)) {
						$space['friends'][] = $value['fuid'];
						$fstr .= $fmod.$value['fuid'];
						$fmod = ',';
					}
					$space['friend'] = $fstr;
				}
			} else {
				$space['friends'] = explode(',', $space['friend']);
			}
			
			$space['username'] = addslashes($space['username']);
			$space['name'] = addslashes($space['name']);
			$space['privacy'] = empty($space['privacy'])?(empty($uch['config']['privacy'])?array():$uch['config']['privacy']):unserialize($space['privacy']);
			if($space['self']) {
				$_SGLOBAL['member'] = $space;
			}
		}
		$_SGLOBAL[$var] = $space;
	}
	return $_SGLOBAL[$var];
}

//��ȡ��ǰ�û���Ϣ
function getmember() {
	$uch = &load_class('Loader', 'core')->get_var('uch');
	
	if(empty($_SGLOBAL['member']) && $_SGLOBAL['supe_uid']) {
		if($space['uid'] == $_SGLOBAL['supe_uid']) {
			$_SGLOBAL['member'] = $space;
		} else {
			$_SGLOBAL['member'] = getspace($_SGLOBAL['supe_uid']);
		}
	}
}

//�����˽
function ckprivacy($type, $feedmode=0) {
	$uch = &load_class('Loader', 'core')->get_var('uch');

	$var = "ckprivacy_{$type}_{$feedmode}";
	if(isset($_SGLOBAL[$var])) {
		return $_SGLOBAL[$var];
	}
	$result = false;
	if($feedmode) {
		if(!empty($space['privacy']['feed'][$type])) {
			$result = true;
		}
	} elseif($space['self']){
		//�Լ�
		$result = true;
	} else {
		if(empty($space['privacy']['view'][$type])) {
			$result = true;
		}
		if(!$result && $space['privacy']['view'][$type] == 1) {
			//�Ƿ����
			if(!isset($space['isfriend'])) {
				$space['isfriend'] = $space['self'];
				if($space['friends'] && in_array($_SGLOBAL['supe_uid'], $space['friends'])) {
					$space['isfriend'] = 1;//�Ǻ���
				}
			}
			if($space['isfriend']) {
				$result = true;
			}
		}
	}
	$_SGLOBAL[$var] = $result;//��ǰҳ�滺��
	return $result;
}

//���APP��˽
function app_ckprivacy($privacy) {
	$uch = &load_class('Loader', 'core')->get_var('uch');

	$var = "app_ckprivacy_{$privacy}";
	if(isset($_SGLOBAL[$var])) {
		return $_SGLOBAL[$var];
	}
	$result = false;
	switch ($privacy) {
		case 0://����
			$result = true;
			break;
		case 1://����
			if(!isset($space['isfriend'])) {
				$space['isfriend'] = $space['self'];
				if($space['friends'] && in_array($_SGLOBAL['supe_uid'], $space['friends'])) {
					$space['isfriend'] = 1;//�Ǻ���
				}
			}
			if($space['isfriend']) {
				$result = true;
			}
			break;
		case 2://���ֺ���
			break;
		case 3://�Լ�
			if($space['self']) {
				$result = true;
			}
			break;
		case 4://����
			break;
		case 5://û����
			break;
		default:
			$result = true;
			break;
	}
	$_SGLOBAL[$var] = $result;
	return $result;
}

//��ȡ�û���
function getgroupid($credit, $gid=0) {
	$uch = &load_class('Loader', 'core')->get_var('uch');

	if(!@include_once(S_ROOT.'./data/data_usergroup.php')) {
		include_once(S_ROOT.'./source/function_cache.php');
		usergroup_cache();
	}

	$needfind = false;
	if($gid && !empty($_SGLOBAL['usergroup'][$gid])) {
		$group = $_SGLOBAL['usergroup'][$gid];
		if(empty($group['system'])) {
			if($group['credithigher']<$credit || $group['creditlower']>$credit) {
				$needfind = true;
			}
		}
	} else {
		$needfind = true;
	}
	if($needfind) {
		$query = $_SGLOBAL['db']->query("SELECT gid FROM ".tname('usergroup')." WHERE creditlower<='$credit' AND system='0' ORDER BY creditlower DESC LIMIT 1");
		$gid = $_SGLOBAL['db']->result($query, 0);
	}
	return $gid;
}

//���Ȩ��
function checkperm($permtype) {
	$uch = &load_class('Loader', 'core')->get_var('uch');

	@include_once(S_ROOT.'./data/data_usergroup.php');

	//�����
	if(empty($_SGLOBAL['supe_uid'])) {
		return '';
	} else {
		if(empty($_SGLOBAL['member'])) {
			//��ȡ��ǰ��
			getmember();
		}
		$gid = getgroupid($_SGLOBAL['member']['credit'], $_SGLOBAL['member']['groupid']);
		if($gid != $_SGLOBAL['member']['groupid']) {
			//��Ҫ��
			updatetable('space', array('groupid'=>$gid), array('uid'=>$_SGLOBAL['supe_uid']));
		}
	}
	if($permtype == 'admin') {
		$permtype = 'manageconfig';
	}
	return empty($_SGLOBAL['usergroup'][$gid][$permtype])?'':$_SGLOBAL['usergroup'][$gid][$permtype];
}

//д������־
function runlog($file, $log, $halt=0) {
	$uch = &load_class('Loader', 'core')->get_var('uch');
	
	$nowurl = $_SERVER['REQUEST_URI']?$_SERVER['REQUEST_URI']:($_SERVER['PHP_SELF']?$_SERVER['PHP_SELF']:$_SERVER['SCRIPT_NAME']);
	$log = sgmdate('Y-m-d H:i:s', $_SGLOBAL['timestamp'])."\t$type\t".getonlineip()."\t$_SGLOBAL[supe_uid]\t{$nowurl}\t".str_replace(array("\r", "\n"), array(' ', ' '), trim($log))."\n";
	$yearmonth = sgmdate('Ym', $_SGLOBAL['timestamp']);
	$logdir = './data/log/';
	if(!is_dir($logdir)) mkdir($logdir, 0777);
	$logfile = $logdir.$yearmonth.'_'.$file.'.php';
	if(@filesize($logfile) > 2048000) {
		$dir = opendir($logdir);
		$length = strlen($file);
		$maxid = $id = 0;
		while($entry = readdir($dir)) {
			if(strexists($entry, $yearmonth.'_'.$file)) {
				$id = intval(substr($entry, $length + 8, -4));
				$id > $maxid && $maxid = $id;
			}
		}
		closedir($dir);
		$logfilebak = $logdir.$yearmonth.'_'.$file.'_'.($maxid + 1).'.php';
		@rename($logfile, $logfilebak);
	}
	if($fp = @fopen($logfile, 'a')) {
		@flock($fp, 2);
		fwrite($fp, "<?PHP exit;?>\t".str_replace(array('<?', '?>', "\r", "\n"), '', $log)."\n");
		fclose($fp);
	}
	if($halt) exit();
}

//��ȡ�ַ�
function getstr($string, $length, $in_slashes=0, $out_slashes=0, $censor=0, $bbcode=0, $html=0) {
	$uch = &load_class('Loader', 'core')->get_var('uch');

	$string = trim($string);

	if($in_slashes) {
		//������ַ���slashes
		$string = sstripslashes($string);
	}
	if($html < 0) {
		//ȥ��html��ǩ
		$string = preg_replace("/(\<[^\<]*\>|\r|\n|\s|\[.+?\])/is", ' ', $string);
		$string = shtmlspecialchars($string);
	} elseif ($html == 0) {
		//ת��html��ǩ
		$string = shtmlspecialchars($string);
	}
	if($censor) {
		//��������
		@include_once(S_ROOT.'./data/data_censor.php');
		if($_SGLOBAL['censor']['banned'] && preg_match($_SGLOBAL['censor']['banned'], $string)) {
			showmessage('information_contains_the_shielding_text');
		} else {
			$string = empty($_SGLOBAL['censor']['filter']) ? $string :
				@preg_replace($_SGLOBAL['censor']['filter']['find'], $_SGLOBAL['censor']['filter']['replace'], $string);
		}
	}
	if($length && strlen($string) > $length) {
		//�ض��ַ�
		$wordscut = '';
		if(strtolower($_SC['charset']) == 'utf-8') {
			//utf8����
			$n = 0;
			$tn = 0;
			$noc = 0;
			while ($n < strlen($string)) {
				$t = ord($string[$n]);
				if($t == 9 || $t == 10 || (32 <= $t && $t <= 126)) {
					$tn = 1;
					$n++;
					$noc++;
				} elseif(194 <= $t && $t <= 223) {
					$tn = 2;
					$n += 2;
					$noc += 2;
				} elseif(224 <= $t && $t < 239) {
					$tn = 3;
					$n += 3;
					$noc += 2;
				} elseif(240 <= $t && $t <= 247) {
					$tn = 4;
					$n += 4;
					$noc += 2;
				} elseif(248 <= $t && $t <= 251) {
					$tn = 5;
					$n += 5;
					$noc += 2;
				} elseif($t == 252 || $t == 253) {
					$tn = 6;
					$n += 6;
					$noc += 2;
				} else {
					$n++;
				}
				if ($noc >= $length) {
					break;
				}
			}
			if ($noc > $length) {
				$n -= $tn;
			}
			$wordscut = substr($string, 0, $n);
		} else {
			for($i = 0; $i < $length - 1; $i++) {
				if(ord($string[$i]) > 127) {
					$wordscut .= $string[$i].$string[$i + 1];
					$i++;
				} else {
					$wordscut .= $string[$i];
				}
			}
		}
		$string = $wordscut;
	}
	if($bbcode) {
		include_once(S_ROOT.'./source/function_bbcode.php');
		$string = bbcode($string, $bbcode);
	}
	if($out_slashes) {
		$string = saddslashes($string);
	}
	return trim($string);
}

//ʱ���ʽ��
function sgmdate($dateformat, $timestamp='', $format=0) {
	$uch = &load_class('Loader', 'core')->get_var('uch');
	if(empty($timestamp)) {
		$timestamp = $_SGLOBAL['timestamp'];
	}
	$result = '';
	if($format) {
		$time = $_SGLOBAL['timestamp'] - $timestamp;
		if($time > 24*3600) {
			$result = gmdate($dateformat, $timestamp + $uch['config']['timeoffset'] * 3600);
		} elseif ($time > 3600) {
			$result = intval($time/3600).lang('hour').lang('before');
		} elseif ($time > 60) {
			$result = intval($time/60).lang('minute').lang('before');
		} elseif ($time > 0) {
			$result = $time.lang('second').lang('before');
		} else {
			$result = lang('now');
		}
	} else {
		$result = gmdate($dateformat, $timestamp + $uch['config']['timeoffset'] * 3600);
	}
	return $result;
}

//�ַ�ʱ�仯
function sstrtotime($string) {
	$uch = &load_class('Loader', 'core')->get_var('uch');
	$time = '';
	if($string) {
		$time = strtotime($string);
		if(sgmdate('H:i') != date('H:i')) {
			$time = $time - $uch['config']['timeoffset'] * 3600;
		}
	}
	return $time;
}

//��ҳ
function multi($num, $perpage, $curpage, $mpurl) {
	$uch = &load_class('Loader', 'core')->get_var('uch');
	$page = 5;
	$multipage = '';
	$mpurl .= strpos($mpurl, '?') ? '&' : '?';
	$realpages = 1;
	if($num > $perpage) {
		$offset = 2;
		$realpages = @ceil($num / $perpage);
		$pages = $uch['config']['maxpage'] && $uch['config']['maxpage'] < $realpages ? $uch['config']['maxpage'] : $realpages;
		if($page > $pages) {
			$from = 1;
			$to = $pages;
		} else {
			$from = $curpage - $offset;
			$to = $from + $page - 1;
			if($from < 1) {
				$to = $curpage + 1 - $from;
				$from = 1;
				if($to - $from < $page) {
					$to = $page;
				}
			} elseif($to > $pages) {
				$from = $pages - $page + 1;
				$to = $pages;
			}
		}
		$multipage = ($curpage - $offset > 1 && $pages > $page ? '<a href="'.$mpurl.'page=1" class="first">1 ...</a>' : '').
			($curpage > 1 ? '<a href="'.$mpurl.'page='.($curpage - 1).'" class="prev">&lsaquo;&lsaquo;</a>' : '');
		for($i = $from; $i <= $to; $i++) {
			$multipage .= $i == $curpage ? '<strong>'.$i.'</strong>' :
				'<a href="'.$mpurl.'page='.$i.'">'.$i.'</a>';
		}
		$multipage .= ($curpage < $pages ? '<a href="'.$mpurl.'page='.($curpage + 1).'" class="next">&rsaquo;&rsaquo;</a>' : '').
			($to < $pages ? '<a href="'.$mpurl.'page='.$pages.'" class="last">... '.$realpages.'</a>' : '');
		$multipage = $multipage ? ('<em>&nbsp;'.$num.'&nbsp;</em>'.$multipage):'';
	}
	$maxpage = $realpages;
	return $multipage;
}

//ob
function obclean() {
	$uch = &load_class('Loader', 'core')->get_var('uch');

	ob_end_clean();
	if ($_SC['gzipcompress'] && function_exists('ob_gzhandler')) {
		ob_start('ob_gzhandler');
	} else {
		ob_start();
	}
}

//ģ�����
function template($name) {
	$uch = &load_class('Loader', 'core')->get_var('uch');

	if(strexists($name,'/')) {
		$tpl = $name;
	} else {
		$tpl = "template/$uch[config][template]/$name";
	}
	$objfile = S_ROOT.'./data/tpl_cache/'.str_replace('/','_',$tpl).'.php';
	if(!file_exists($objfile)) {
		include_once(S_ROOT.'./source/function_template.php');
		parse_template($tpl);
	}
	return $objfile;
}

//��ģ����¼��
function subtplcheck($subfiles, $mktime, $tpl) {
	$uch = &load_class('Loader', 'core')->get_var('uch');

	if($_SC['tplrefresh'] && ($_SC['tplrefresh'] == 1 || mt_rand(1, $_SC['tplrefresh']) == 1)) {
		$subfiles = explode('|', $subfiles);
		foreach ($subfiles as $subfile) {
			$tplfile = S_ROOT.'./'.$subfile.'.htm';
			if(!file_exists($tplfile)) {
				$tplfile = str_replace('/'.$uch['config']['template'].'/', '/default/', $tplfile);
			}
			@$submktime = filemtime($tplfile);
			if($submktime > $mktime) {
				include_once(S_ROOT.'./source/function_template.php');
				parse_template($tpl);
				break;
			}
		}
	}
}

//ģ��
function block($param) {
	$uch = &load_class('Loader', 'core')->get_var('uch');

	include_once(S_ROOT.'./source/function_block.php');
	block_batch($param);
}

//��ȡ��Ŀ
function getcount($tablename, $wherearr, $get='COUNT(*)') {
	$uch = &load_class('Loader', 'core')->get_var('uch');
	if(empty($wherearr)) {
		$wheresql = '1';
	} else {
		$wheresql = $mod = '';
		foreach ($wherearr as $key => $value) {
			$wheresql .= $mod."`$key`='$value'";
			$mod = ' AND ';
		}
	}
	return $_SGLOBAL['db']->result($_SGLOBAL['db']->query("SELECT $get FROM ".tname($tablename)." WHERE $wheresql LIMIT 1"), 0);
}

//�������
function ob_out() {
	$uch = &load_class('Loader', 'core')->get_var('uch');

	$content = ob_get_contents();

	$preg_searchs = $preg_replaces = $str_searchs = $str_replaces = array();

	if($uch['config']['allowrewrite']) {
		$preg_searchs[] = "/\<a href\=\"space\.php\?(uid|do)+\=([a-z0-9\=\&]+?)\"/ie";
		$preg_searchs[] = "/\<a href\=\"space.php\"/i";
		$preg_searchs[] = "/\<a href\=\"network\.php\?ac\=([a-z0-9\=\&]+?)\"/ie";
		$preg_searchs[] = "/\<a href\=\"network.php\"/i";

		$preg_replaces[] = 'rewrite_url(\'space-\',\'\\2\')';
		$preg_replaces[] = '<a href="space.html"';
		$preg_replaces[] = 'rewrite_url(\'network-\',\'\\1\')';
		$preg_replaces[] = '<a href="network.html"';
	}
	if($uch['config']['linkguide']) {
		$preg_searchs[] = "/\<a href\=\"http\:\/\/(.+?)\"/ie";
		$preg_replaces[] = 'iframe_url(\'\\1\')';
	}

	if($_SGLOBAL['inajax']) {
		$preg_searchs[] = "/([\x01-\x09\x0b-\x0c\x0e-\x1f])+/";
		$preg_replaces[] = ' ';

		$str_searchs[] = ']]>';
		$str_replaces[] = ']]&gt;';
	}

	if($preg_searchs) {
		$content = preg_replace($preg_searchs, $preg_replaces, $content);
	}
	if($str_searchs) {
		$content = trim(str_replace($str_searchs, $str_replaces, $content));
	}

	obclean();
	if($_SGLOBAL['inajax']) {
		xml_out($content);
	} else{
		if($uch['config']['headercharset']) {
			@header('Content-Type: text/html; charset='.$_SC['charset']);
		}
		echo $content;
		if(D_BUG) {
			@include_once(S_ROOT.'./source/inc_debug.php');
		}
	}
}

function xml_out($content) {
	$uch = &load_class('Loader', 'core')->get_var('uch');
	@header("Expires: -1");
	@header("Cache-Control: no-store, private, post-check=0, pre-check=0, max-age=0", FALSE);
	@header("Pragma: no-cache");
	@header("Content-type: application/xml; charset=$_SC[charset]");
	echo '<'."?xml version=\"1.0\" encoding=\"$_SC[charset]\"?>\n";
	echo "<root><![CDATA[".trim($content)."]]></root>";
	exit();
}

//rewrite����
function rewrite_url($pre, $para) {
	$para = str_replace(array('&','='), array('-', '-'), $para);
	return '<a href="'.$pre.$para.'.html"';
}

//����
function iframe_url($url) {
	$url = rawurlencode($url);
	return "<a href=\"link.php?url=http://$url\"";
}

//���������ؼ���
function stripsearchkey($string) {
	$string = trim($string);
	$string = str_replace('*', '%', addcslashes($string, '%_'));
	$string = str_replace('_', '\_', $string);
	return $string;
}

//�Ƿ����ζ�������
function isholddomain($domain) {
	$uch = &load_class('Loader', 'core')->get_var('uch');

	$domain = strtolower($domain);

	if(preg_match("/^[^a-z]/i", $domain)) return true;
	$holdmainarr = empty($uch['config']['holddomain'])?array('www'):explode('|', $uch['config']['holddomain']);
	$ishold = false;
	foreach ($holdmainarr as $value) {
		if(strpos($value, '*') === false) {
			if(strtolower($value) == $domain) {
				$ishold = true;
				break;
			}
		} else {
			$value = str_replace('*', '', $value);
			if(@preg_match("/$value/i", $domain)) {
				$ishold = true;
				break;
			}
		}
	}
	return $ishold;
}

//�����ַ�
function simplode($ids) {
	return "'".implode("','", $ids)."'";
}

//��ʾ��̴���ʱ��
function debuginfo() {
	$uch = &load_class('Loader', 'core')->get_var('uch');

	if(empty($uch['config']['debuginfo'])) {
		$info = '';
	} else {
		$mtime = explode(' ', microtime());
		$totaltime = number_format(($mtime[1] + $mtime[0] - $_SGLOBAL['supe_starttime']), 6);
		$info = 'Processed in '.$totaltime.' second(s), '.$_SGLOBAL['db']->querynum.' queries'.
				($_SC['gzipcompress'] ? ', Gzip enabled' : NULL);
	}

	return $info;
}

//��ʽ����С����
function formatsize($size) {
	$prec=3;
	$size = round(abs($size));
	$units = array(0=>" B ", 1=>" KB", 2=>" MB", 3=>" GB", 4=>" TB");
	if ($size==0) return str_repeat(" ", $prec)."0$units[0]";
	$unit = min(4, floor(log($size)/log(2)/10));
	$size = $size * pow(2, -10*$unit);
	$digi = $prec - 1 - floor(log($size)/log(10));
	$size = round($size * pow(10, $digi)) * pow(10, -$digi);
	return $size.$units[$unit];
}

//��ȡ�ļ�����
function sreadfile($filename) {
	$content = '';
	if(function_exists('file_get_contents')) {
		@$content = file_get_contents($filename);
	} else {
		if(@$fp = fopen($filename, 'r')) {
			@$content = fread($fp, filesize($filename));
			@fclose($fp);
		}
	}
	return $content;
}

//д���ļ�
function swritefile($filename, $writetext, $openmod='w') {
	if(@$fp = fopen($filename, $openmod)) {
		flock($fp, 2);
		fwrite($fp, $writetext);
		fclose($fp);
		return true;
	} else {
		runlog('error', "File: $filename write error.");
		return false;
	}
}

//��������ַ�
function random($length, $numeric = 0) {
	PHP_VERSION < '4.2.0' ? mt_srand((double)microtime() * 1000000) : mt_srand();
	$seed = base_convert(md5(print_r($_SERVER, 1).microtime()), 16, $numeric ? 10 : 35);
	$seed = $numeric ? (str_replace('0', '', $seed).'012340567890') : ($seed.'zZ'.strtoupper($seed));
	$hash = '';
	$max = strlen($seed) - 1;
	for($i = 0; $i < $length; $i++) {
		$hash .= $seed[mt_rand(0, $max)];
	}
	return $hash;
}

//�ж��ַ��Ƿ����
function strexists($haystack, $needle) {
	return !(strpos($haystack, $needle) === FALSE);
}

//��ȡ���
function data_get($var, $isarray=0) {
	$uch = &load_class('Loader', 'core')->get_var('uch');

	$query = $_SGLOBAL['db']->query("SELECT * FROM ".tname('data')." WHERE var='$var' LIMIT 1");
	if($value = $_SGLOBAL['db']->fetch_array($query)) {
		return $isarray?$value:$value['datavalue'];
	} else {
		return '';
	}
}

//�������
function data_set($var, $datavalue, $clean=0) {
	$uch = &load_class('Loader', 'core')->get_var('uch');

	if($clean) {
		$_SGLOBAL['db']->query("DELETE FROM ".tname('data')." WHERE var='$var'");
	} else {
		if(is_array($datavalue)) $datavalue = serialize(sstripslashes($datavalue));
		$_SGLOBAL['db']->query("REPLACE INTO ".tname('data')." (var, datavalue, dateline) VALUES ('$var', '".addslashes($datavalue)."', '$_SGLOBAL[timestamp]')");
	}
}

//���վ���Ƿ�ر�
function checkclose() {
	$uch = &load_class('Loader', 'core')->get_var('uch');

	//վ��ر�
	if($uch['config']['close'] && !ckfounder($_SGLOBAL['supe_uid']) && !checkperm('closeignore')) {
		if(empty($uch['config']['closereason'])) {
			showmessage('site_temporarily_closed');
		} else {
			showmessage($uch['config']['closereason']);
		}
	}
	//IP���ʼ��
	if((!ipaccess($uch['config']['ipaccess']) || ipbanned($uch['config']['ipbanned'])) && !ckfounder($_SGLOBAL['supe_uid']) && !checkperm('closeignore')) {
		showmessage('ip_is_not_allowed_to_visit');
	}
}

//վ������
function getsiteurl() {
	$uch = &load_class('Loader', 'core')->get_var('uch');
	
	if(empty($_SC['siteurl'])) {
		$uri = $_SERVER['REQUEST_URI']?$_SERVER['REQUEST_URI']:($_SERVER['PHP_SELF']?$_SERVER['PHP_SELF']:$_SERVER['SCRIPT_NAME']);
		return 'http://'.$_SERVER['HTTP_HOST'].substr($uri, 0, strrpos($uri, '/')+1);
	} else {
		return $_SC['siteurl'];
	}
}

//��ȡ�ļ����׺
function fileext($filename) {
	return strtolower(trim(substr(strrchr($filename, '.'), 1)));
}

//���
function creditrule($mode, $type) {
	$uch = &load_class('Loader', 'core')->get_var('uch');

	if(!@include_once(S_ROOT.'./data/data_creditrule.php')) {
		include_once(S_ROOT.'./source/function_cache.php');
		creditrule_cache();
	}
	$credit = 0;
	if(!empty($_SGLOBAL['creditrule'])) {
		if(!empty($_SGLOBAL['creditrule'][$mode][$type])) {
			$credit = $_SGLOBAL['creditrule'][$mode][$type];
		}
	}
	return intval($credit);
}

//���»��
function updatespacestatus($creditmode, $optype) {
	$uch = &load_class('Loader', 'core')->get_var('uch');

	$lastname = $optype=='search'?'lastsearch':'lastpost';
	$credit = creditrule($creditmode, $optype);
	if($credit) {
		$creditsql = ($creditmode == 'get')?"+$credit":"-$credit";
	} else {
		$creditsql = '';
	}
	$creditsql = $creditsql?",credit=credit{$creditsql}":'';
	$updatetimesql = $optype=='search'?'':",updatetime='$_SGLOBAL[timestamp]'";//����������

	//����״̬
	$_SGLOBAL['db']->query("UPDATE ".tname('space')." SET $lastname='$_SGLOBAL[timestamp]' $updatetimesql $creditsql WHERE uid='$_SGLOBAL[supe_uid]'");
}

//ȥ��slassh
function sstripslashes($string) {
	if(is_array($string)) {
		foreach($string as $key => $val) {
			$string[$key] = sstripslashes($val);
		}
	} else {
		$string = stripslashes($string);
	}
	return $string;
}

//��ʾ���
function adshow($pagetype) {
	$uch = &load_class('Loader', 'core')->get_var('uch');

	@include_once(S_ROOT.'./data/data_ad.php');
	if(empty($_SGLOBAL['ad']) || empty($_SGLOBAL['ad'][$pagetype])) return false;
	$ads = $_SGLOBAL['ad'][$pagetype];
	$key = mt_rand(0, count($ads)-1);
	$id = $ads[$key];
	$file = S_ROOT.'./data/adtpl/'.$id.'.htm';
	echo sreadfile($file);
}

//����ת��
function siconv($str, $out_charset, $in_charset='') {
	$uch = &load_class('Loader', 'core')->get_var('uch');

	$in_charset = empty($in_charset)?strtoupper($_SC['charset']):strtoupper($in_charset);
	$out_charset = strtoupper($out_charset);
	if($in_charset != $out_charset) {
		if (function_exists('iconv') && (@$outstr = iconv("$in_charset//IGNORE", "$out_charset//IGNORE", $str))) {
			return $outstr;
		} elseif (function_exists('mb_convert_encoding') && (@$outstr = mb_convert_encoding($str, $out_charset, $in_charset))) {
			return $outstr;
		}
	}
	return $str;//ת��ʧ��
}

//��ȡ�û����
function getpassport($username, $password) {
	$uch = &load_class('Loader', 'core')->get_var('uch');

	$passport = array();
	if(!@include_once S_ROOT.'./uc_client/client.php') {
		showmessage('system_error');
	}

	$ucresult = uc_user_login($username, $password);
	if($ucresult[0] > 0) {
		$passport['uid'] = $ucresult[0];
		$passport['username'] = $ucresult[1];
		$passport['email'] = $ucresult[3];
	}
	return $passport;
}

//�û�����ʱ�������
function interval_check($type) {
	$uch = &load_class('Loader', 'core')->get_var('uch');

	$intervalname = $type.'interval';
	$lastname = 'last'.$type;

	$waittime = 0;
	if($interval = checkperm($intervalname)) {
		$lasttime = isset($space[$lastname])?$space[$lastname]:getcount('space', array('uid'=>$_SGLOBAL['supe_uid']), $lastname);
		$waittime = $interval - ($_SGLOBAL['timestamp'] - $lasttime);
	}
	return $waittime;
}

//�����ϴ�ͼƬ����
function mkpicurl($pic, $thumb=1) {
	$uch = &load_class('Loader', 'core')->get_var('uch');

	$url = '';
	if(isset($pic['picnum']) && $pic['picnum'] < 1) {
		$url = 'image/nopic.gif';
	} elseif(isset($pic['picflag'])) {
		if($pic['pic']) {
			if($pic['picflag'] == 1) {
				$url = $_SC['attachurl'].$pic['pic'];
			} elseif ($pic['picflag'] == 2) {
				$url = $uch['config']['ftpurl'].$pic['pic'];
			} else {
				$url = $pic['pic'];
			}
		}
	} elseif(isset($pic['filepath'])) {
		$pic['pic'] = $pic['filepath'];
		if($pic['pic']) {
			if($thumb && $pic['thumb']) $pic['pic'] .= '.thumb.jpg';
			if($pic['remote']) {
				$url = $uch['config']['ftpurl'].$pic['pic'];
			} else {
				$url = $_SC['attachurl'].$pic['pic'];
			}
		}
	} else {
		$url = $pic['pic'];
	}
	if($url && $pic['friend']==4) {
		$url = 'image/nopublish.jpg';
	}
	return $url;
}

//��������ͼƬ����
function getpicurl($picurl, $maxlenth='200') {
	$picurl = shtmlspecialchars(trim($picurl));
	if($picurl) {
		if(preg_match("/^http\:\/\/.{5,$maxlenth}\.(jpg|gif|png)$/i", $picurl)) return $picurl;
	}
	return '';
}

//����������
function getstar($credit) {
	$uch = &load_class('Loader', 'core')->get_var('uch');

	$starimg = '';
	if($uch['config']['starcredit'] > 1) {
		//����������
		$starnum = intval($credit/$uch['config']['starcredit']) + 1;
		if($uch['config']['starlevelnum'] < 2) {
			if($starnum > 10) $starnum = 10;
			for($i = 0; $i < $starnum; $i++) {
				$starimg .= '<img src="image/star_level10.gif" align="absmiddle" />';
			}
		} else {
			//����ȼ�(10��)
			for($i = 10; $i > 0; $i--) {
				$numlevel = intval($starnum / pow($uch['config']['starlevelnum'], ($i - 1)));
				if($numlevel > 10) $numlevel = 10;
				if($numlevel) {
					for($j = 0; $j < $numlevel; $j++) {
						$starimg .= '<img src="image/star_level'.$i.'.gif" align="absmiddle" />';
					}
					break;
				}
			}
		}
	}
	if(empty($starimg)) $starimg = '<img src="image/credit.gif" alt="'.$credit.'" align="absmiddle" alt="'.$credit.'" title="'.$credit.'" />';
	return $starimg;
}

//�����ҳ
function smulti($start, $perpage, $count, $url, $ajaxdiv='') {
	$uch = &load_class('Loader', 'core')->get_var('uch');

	$multi = array('last'=>-1, 'next'=>-1, 'begin'=>-1, 'end'=>-1, 'html'=>'');
	if($start > 0) {
		if(empty($count)) {
			showmessage('no_data_pages');
		} else {
			$multi['last'] = $start - $perpage;
		}
	}

	$showhtml = 0;
	if($count == $perpage) {
		$multi['next'] = $start + $perpage;
	}
	$multi['begin'] = $start + 1;
	$multi['end'] = $start + $count;

	if($multi['begin'] >= 0) {
		if($multi['last'] >= 0) {
			$showhtml = 1;
			if($_SGLOBAL['inajax']) {
				$multi['html'] .= "<a href=\"javascript:;\" onclick=\"ajaxget('$url&ajaxdiv=$ajaxdiv', '$ajaxdiv')\">|&lt;</a> <a href=\"javascript:;\" onclick=\"ajaxget('$url&start=$multi[last]&ajaxdiv=$ajaxdiv', '$ajaxdiv')\">&lt;</a> ";
			} else {
				$multi['html'] .= "<a href=\"$url\">|&lt;</a> <a href=\"$url&start=$multi[last]\">&lt;</a> ";
			}
		} else {
			$multi['html'] .= "&lt;";
		}
		$multi['html'] .= " $multi[begin]~$multi[end] ";
		if($multi['next'] >= 0) {
			$showhtml = 1;
			if($_SGLOBAL['inajax']) {
				$multi['html'] .= " <a href=\"javascript:;\" onclick=\"ajaxget('$url&start=$multi[next]&ajaxdiv=$ajaxdiv', '$ajaxdiv')\">&gt;</a> ";
			} else {
				$multi['html'] .= " <a href=\"$url&start=$multi[next]\">&gt;</a>";
			}
		} else {
			$multi['html'] .= " &gt;";
		}
	}

	return $showhtml?$multi['html']:'';
}

//��ȡ����״̬
function getfriendstatus($uid, $fuid) {
	$uch = &load_class('Loader', 'core')->get_var('uch');

	$query = $_SGLOBAL['db']->query("SELECT status FROM ".tname('friend')." WHERE uid='$uid' AND fuid='$fuid' LIMIT 1");
	if($value = $_SGLOBAL['db']->fetch_array($query)) {
		return $value['status'];
	} else {
		return -1;
	}
}

//�����齨
function renum($array) {
	$newnums = $nums = array();
	foreach ($array as $id => $num) {
		$newnums[$num][] = $id;
		$nums[$num] = $num;
	}
	return array($nums, $newnums);
}

//��鶨��
function ckfriend($invalue) {
	$uch = &load_class('Loader', 'core')->get_var('uch');

	if($invalue['uid'] == $_SGLOBAL['supe_uid']) return true;//�Լ�
	$result = false;
	switch ($invalue['friend']) {
		case 0://ȫվ�û��ɼ�
			$result = true;
			break;
		case 1://ȫ���ѿɼ�
			if($space['self']) {
				$result = true;
			} else {
				if($space['uid'] == $invalue['uid']) {
					//�Ƿ����
					$space['isfriend'] = $space['self'];
					if($space['friends'] && in_array($_SGLOBAL['supe_uid'], $space['friends'])) {
						$space['isfriend'] = 1;//�Ǻ���
					}
					$isfriend = $space['isfriend'];
				} else {
					$isfriend = getfriendstatus($_SGLOBAL['supe_uid'], $invalue['uid']);
				}
				if($isfriend) $result = true;
			}
			break;
		case 2://��ָ�����ѿɼ�
			if($invalue['target_ids']) {
				$target_ids = explode(',', $invalue['target_ids']);
				if(in_array($_SGLOBAL['supe_uid'], $target_ids)) $result = true;
			}
			break;
		case 3://���Լ��ɼ�
			break;
		case 4://ƾ����鿴
			$result = true;
			break;
		default:
			break;
	}
	return $result;
}

//����feed
function mkfeed($feed, $actors=array()) {
	$uch = &load_class('Loader', 'core')->get_var('uch');

	$feed['title_data'] = empty($feed['title_data'])?array():unserialize($feed['title_data']);
	$feed['body_data'] = empty($feed['body_data'])?array():unserialize($feed['body_data']);

	//title
	$searchs = $replaces = array();
	if($feed['title_data'] && is_array($feed['title_data'])) {
		foreach (array_keys($feed['title_data']) as $key) {
			$searchs[] = '{'.$key.'}';
			$replaces[] = $feed['title_data'][$key];
		}
	}

	$searchs[] = '{actor}';
	$replaces[] = empty($actors)?"<a href=\"space.php?uid=$feed[uid]\">".$_SN[$feed['uid']]."</a>":implode(lang('dot'), $actors);
	$searchs[] = '{app}';
	if(empty($_SGLOBAL['app'][$feed['appid']])) {
		$replaces[] = '';
	} else {
		$app = $_SGLOBAL['app'][$feed['appid']];
		$replaces[] = "<a href=\"$app[url]\">$app[name]</a>";
	}
	$feed['title_template'] = mktarget(str_replace($searchs, $replaces, $feed['title_template']));

	//body
	$searchs = $replaces = array();
	if($feed['body_data']) {
		foreach (array_keys($feed['body_data']) as $key) {
			$searchs[] = '{'.$key.'}';
			$replaces[] = $feed['body_data'][$key];
		}
	}
	$searchs[] = '{actor}';
	$replaces[] = "<a href=\"space.php?uid=$feed[uid]\">$feed[username]</a>";
	$feed['body_template'] = mktarget(str_replace($searchs, $replaces, $feed['body_template']));

	$feed['body_general'] = mktarget($feed['body_general']);
	
	//icon
	if($feed['appid']) {
		$feed['icon_image'] = "image/icon/{$feed['icon']}.gif";
	} else {
		$feed['icon_image'] = "http://appicon.manyou.com/icons/{$feed['icon']}";
	}
	
	//�Ķ�
	$feed['style'] = $feed['target'] = '';
	if($uch['config']['feedread']) {
		$read_feed_ids = empty($_COOKIE['read_feed_ids'])?array():explode(',',$_COOKIE['read_feed_ids']);
		if($read_feed_ids && in_array($feed['feedid'], $read_feed_ids)) {
			$feed['style'] = " class=\"feedread\"";
		} else {
			$feed['style'] = " onclick=\"readfeed(this, $feed[feedid]);\"";
		}
	}
	if($uch['config']['feedtargetblank']) {
		$feed['target'] = ' target="_blank"';
	}
	
	return $feed;
}

//����feed������
function mktarget($html) {
	$uch = &load_class('Loader', 'core')->get_var('uch');
	
	if($html && $uch['config']['feedtargetblank']) {
		$html = preg_replace("/<a(.+?)href=([\'\"]?)([^>\s]+)\\2([^>]*)>/i", '<a target="_blank" \\1 href="\\3" \\4>', $html);
	}
	return $html;
}

//�������
function mkshare($share) {
	$share['body_data'] = unserialize($share['body_data']);

	//body
	$searchs = $replaces = array();
	if($share['body_data']) {
		foreach (array_keys($share['body_data']) as $key) {
			$searchs[] = '{'.$key.'}';
			$replaces[] = $share['body_data'][$key];
		}
	}
	$share['body_template'] = str_replace($searchs, $replaces, $share['body_template']);

	return $share;
}

//ip��������
function ipaccess($ipaccess) {
	return empty($ipaccess)?true:preg_match("/^(".str_replace(array("\r\n", ' '), array('|', ''), preg_quote($ipaccess, '/')).")/", getonlineip());
}

//ip���ʽ�ֹ
function ipbanned($ipbanned) {
	return empty($ipbanned)?false:preg_match("/^(".str_replace(array("\r\n", ' '), array('|', ''), preg_quote($ipbanned, '/')).")/", getonlineip());
}

//���start
function ckstart($start, $perpage) {
	$uch = &load_class('Loader', 'core')->get_var('uch');

	$maxstart = $perpage*intval($uch['config']['maxpage']);
	if($start < 0 || ($maxstart > 0 && $start >= $maxstart)) {
		showmessage('length_is_not_within_the_scope_of');
	}
}

//����ͷ��
function avatar($uid, $size='small') {
	$uch = &load_class('Loader', 'core')->get_var('uch');
	
	$type = empty($uch['config']['avatarreal'])?'virtual':'real';
	if(empty($uch['config']['uc_dir'])) {
		return UC_API.'/avatar.php?uid='.$uid.'&size='.$size.'&type='.(empty($uch['config']['avatarreal'])?'virtual':'real');
	} else {
		if(ckavatar($uid)) {
			return UC_API.'/data/avatar/'.avatarfile($uid, $size, $type);
		} else {
			return UC_API."/images/noavatar_$size.gif";
		}
	}
}

//���ͷ���Ƿ��ϴ�
function ckavatar($uid) {
	$uch = &load_class('Loader', 'core')->get_var('uch');
	
	$type = empty($uch['config']['avatarreal'])?'virtual':'real';
	if(empty($uch['config']['uc_dir'])) {
		include_once(S_ROOT.'./uc_client/client.php');
		$file_exists = uc_check_avatar($uid, 'middle', $type);
		return $file_exists;
	} else {
		$file = $uch['config']['uc_dir'].'./data/avatar/'.avatarfile($uid, 'middle', $type);
		return file_exists($file)?1:0;
	}
}

//�õ�ͷ��
function avatarfile($uid, $size = 'middle', $type = '') {
	$uch = &load_class('Loader', 'core')->get_var('uch');
	
	$var = "avatarfile_{$uid}_{$size}_{$type}";
	if(empty($_SGLOBAL[$var])) {
		$size = in_array($size, array('big', 'middle', 'small')) ? $size : 'middle';
		$uid = abs(intval($uid));
		$uid = sprintf("%09d", $uid);
		$dir1 = substr($uid, 0, 3);
		$dir2 = substr($uid, 3, 2);
		$dir3 = substr($uid, 5, 2);
		$typeadd = $type == 'real' ? '_real' : '';
		$_SGLOBAL[$var] = $dir1.'/'.$dir2.'/'.$dir3.'/'.substr($uid, -2).$typeadd."_avatar_$size.jpg";
	}
	return $_SGLOBAL[$var];
}

//����Ƿ��¼
function checklogin() {
	$uch = &load_class('Loader', 'core')->get_var('uch');

	if(empty($_SGLOBAL['supe_uid'])) {
		ssetcookie('_refer', rawurlencode($_SERVER['REQUEST_URI']));
		showmessage('to_login', 'do.php?ac='.$uch['config']['login_action']);
	}
}

//���ǰ̨����
function lang($key, $vars=array()) {
	$uch = &load_class('Loader', 'core')->get_var('uch');

	include_once(S_ROOT.'./language/lang_source.php');
	if(isset($_SGLOBAL['sourcelang'][$key])) {
		$result = lang_replace($_SGLOBAL['sourcelang'][$key], $vars);
	} else {
		$result = $key;
	}
	return $result;
}

//��ú�̨����
function cplang($key, $vars=array()) {
	$uch = &load_class('Loader', 'core')->get_var('uch');

	include_once(S_ROOT.'./language/lang_cp.php');
	if(isset($_SGLOBAL['cplang'][$key])) {
		$result = lang_replace($_SGLOBAL['cplang'][$key], $vars);
	} else {
		$result = $key;
	}
	return $result;
}

//�����滻
function lang_replace($text, $vars) {
	if($vars) {
		foreach ($vars as $k => $v) {
			$rk = $k + 1;
			$text = str_replace('\\'.$rk, $v, $text);
		}
	}
	return $text;
}

//����û�����
function getfriendgroup() {
	$uch = &load_class('Loader', 'core')->get_var('uch');

	$groups = array();
	$spacegroup = empty($space['privacy']['groupname'])?array():$space['privacy']['groupname'];
	for($i=0; $i<$uch['config']['groupnum']; $i++) {
		if($i == 0) {
			$groups[0] = lang('friend_group_default');
		} else {
			if(!empty($spacegroup[$i])) {
				$groups[$i] = $spacegroup[$i];
			} else {
				if($i<8) {
					$groups[$i] = lang('friend_group_'.$i);
				} else {
					$groups[$i] = lang('friend_group').$i;
				}
			}
		}
	}
	return $groups;
}

//��ȡ����
function sub_url($url, $length) {
	if(strlen($url) > $length) {
		$url = str_replace(array('%3A', '%2F'), array(':', '/'), rawurlencode($url));
		$url = substr($url, 0, intval($length * 0.5)).' ... '.substr($url, - intval($length * 0.3));
	}
	return $url;
}

//��ȡ�û���
function realname_set($uid, $username, $name='', $namestatus=0) {
	$uch = &load_class('Loader', 'core')->get_var('uch');
	if($name) {
		$_SN[$uid] = ($uch['config']['realname'] && $namestatus)?$name:$username;
	} elseif(!isset($_SN[$uid])) {
		$_SN[$uid] = $username;
		$_SGLOBAL['select_realname'][$uid] = $uid;//��Ҫ����
	}
}

//��ȡʵ��
function realname_get() {
	$uch = &load_class('Loader', 'core')->get_var('uch');

	if($uch['config']['realname'] && $_SGLOBAL['select_realname']) {
		//�Ѿ��е�
		if($space && isset($_SGLOBAL['select_realname'][$space['uid']])) {
			unset($_SGLOBAL['select_realname'][$space['uid']]);
		}
		if($_SGLOBAL['member']['uid'] && isset($_SGLOBAL['select_realname'][$_SGLOBAL['member']['uid']])) {
			unset($_SGLOBAL['select_realname'][$_SGLOBAL['member']['uid']]);
		}
		//���ʵ��
		$uids = empty($_SGLOBAL['select_realname'])?array():array_keys($_SGLOBAL['select_realname']);
		if($uids) {
			$query = $_SGLOBAL['db']->query("SELECT uid, name, namestatus FROM ".tname('space')." WHERE uid IN (".simplode($uids).")");
			while ($value = $_SGLOBAL['db']->fetch_array($query)) {
				if($value['name'] && $value['namestatus']) {
					$_SN[$value['uid']] = $value['name'];
				}
			}
		}
	}
}

//Ⱥ����Ϣ
function getmtag($id) {
	$uch = &load_class('Loader', 'core')->get_var('uch');
	
	$query = $_SGLOBAL['db']->query("SELECT * FROM ".tname('mtag')." WHERE tagid='$id'");
	if(!$mtag = $_SGLOBAL['db']->fetch_array($query)) {
		showmessage('designated_election_it_does_not_exist');
	}
	//��Ⱥ��
	if($mtag['membernum']<1 && ($mtag['joinperm'] || $mtag['viewperm'])) {
		$mtag['joinperm'] = $mtag['viewperm'] = 0;
		updatetable('mtag', array('joinperm'=>0, 'viewperm'=>0), array('tagid'=>$id));
	}
	
	//����
	include_once(S_ROOT.'./data/data_profield.php');
	$mtag['field'] = $_SGLOBAL['profield'][$mtag['fieldid']];
	$mtag['title'] = $mtag['field']['title'];
	if(empty($mtag['pic'])) {
		$mtag['pic'] = 'image/nologo.jpg';
	}

	//��Ա����
	$mtag['ismember'] = 0;
	$mtag['grade'] = -9;//-9 �ǳ�Ա -2 ���� -1 ���� 0 ��ͨ 1 ���� 8 ��Ⱥ�� 9 Ⱥ��
	$query = $_SGLOBAL['db']->query("SELECT grade FROM ".tname('tagspace')." WHERE tagid='$id' AND uid='$_SGLOBAL[supe_uid]' LIMIT 1");
	if($value = $_SGLOBAL['db']->fetch_array($query)) {
		$mtag['grade'] = $value['grade'];
		$mtag['ismember'] = 1;
	}
	if($mtag['grade'] < 9 && checkperm('managemtag')) {
		$mtag['grade'] = 9;
	}
	$mtag['allowpost'] = $mtag['grade']>=0?1:0;
	$mtag['allowview'] = ($mtag['viewperm'] && $mtag['grade'] < -1)?0:1;
	
	$mtag['allowinvite'] = $mtag['grade']>=0?1:0;
	if($mtag['joinperm'] && $mtag['grade'] < 8) {
		$mtag['allowinvite'] = 0;
	}
	
	return $mtag;
}

//ȡ�����е�����
function sarray_rand($arr, $num) {
	$r_values = array();
	if($arr && count($arr) > $num) {
		if($num > 1) {
			$r_keys = array_rand($arr, $num);
			foreach ($r_keys as $key) {
				$r_values[$key] = $arr[$key];
			}
		} else {
			$r_key = array_rand($arr, 1);
			$r_values[$r_key] = $arr[$r_key];
		}
	} else {
		$r_values = $arr;
	}
	return $r_values;
}

//����û�Ψһ��
function space_key($space, $appid=0) {
	$uch = &load_class('Loader', 'core')->get_var('uch');
	return substr(md5($uch['config']['sitekey'].'|'.$space['uid'].(empty($appid)?'':'|'.$appid)), 8, 16);
}

//����û�URL
function space_domain($space) {
	$uch = &load_class('Loader', 'core')->get_var('uch');
	
	if($space['domain'] && $uch['config']['allowdomain'] && $uch['config']['domainroot']) {
		$space['domainurl'] = 'http://'.$space['domain'].'.'.$uch['config']['domainroot'];
	} else {
		if($uch['config']['allowrewrite']) {
			$space['domainurl'] = getsiteurl().$space[uid];
		} else {
			$space['domainurl'] = getsiteurl()."?$space[uid]";
		}
	}
	return $space['domainurl'];
}

//����form��α��
function formhash() {
	$uch = &load_class('Loader', 'core')->get_var('uch');
	
	if(empty($_SGLOBAL['formhash'])) {
		$hashadd = defined('IN_ADMINCP') ? 'Only For UCenter Home AdminCP' : '';
		$_SGLOBAL['formhash'] = substr(md5(substr($_SGLOBAL['timestamp'], 0, -7).'|'.$_SGLOBAL['supe_uid'].'|'.md5($uch['config']['sitekey']).'|'.$hashadd), 8, 8);
	}
	return $_SGLOBAL['formhash'];
}

//��������Ƿ���Ч
function isemail($email) {
	return strlen($email) > 6 && preg_match("/^[\w\-\.]+@[\w\-\.]+(\.\w+)+$/", $email);
}

//��֤����
function question() {
	$uch = &load_class('Loader', 'core')->get_var('uch');

	include_once(S_ROOT.'./data/data_spam.php');
	if($_SGLOBAL['spam']['question']) {
		$count = count($_SGLOBAL['spam']['question']);
		$key = $count>1?mt_rand(0, $count-1):0;
		ssetcookie('seccode', $key);
		echo $_SGLOBAL['spam']['question'][$key];
	}
}

//���MYOP����Ϣ�ű�
function my_checkupdate() {
	$uch = &load_class('Loader', 'core')->get_var('uch');
	if($uch['config']['my_status'] && empty($uch['config']['my_closecheckupdate']) && checkperm('admin')) {
		$sid = $uch['config']['my_siteid'];
		$ts = $_SGLOBAL['timestamp'];
		$key = md5($sId.$ts.$uch['config']['my_sitekey']);
		echo '<script type="text/javascript" src="http://notice.uchome.manyou.com/notice?sId='.$sid.'&ts='.$ts.'&key='.$key.'"></script>';
	}
}

//����û���ͼ��
function g_icon($gid) {
	$uch = &load_class('Loader', 'core')->get_var('uch');
	include_once(S_ROOT.'./data/data_usergroup.php');
	if(empty($_SGLOBAL['usergroup'][$gid]['icon'])) {
		echo '';
	} else {
		echo ' <img src="'.$_SGLOBAL['usergroup'][$gid]['icon'].'" align="absmiddle"> ';
	}
}

//����û���ɫ
function g_color($gid) {
	$uch = &load_class('Loader', 'core')->get_var('uch');
	include_once(S_ROOT.'./data/data_usergroup.php');
	if(empty($_SGLOBAL['usergroup'][$gid]['color'])) {
		echo '';
	} else {
		echo ' style="color:'.$_SGLOBAL['usergroup'][$gid]['color'].';"';
	}
}

//����Ƿ������ʼ��
function ckfounder($uid) {
	$uch = &load_class('Loader', 'core')->get_var('uch');
	
	$founders = empty($_SC['founder'])?array():explode(',', $_SC['founder']);
	if($uid && $founders) {
		return in_array($uid, $founders);
	} else {
		return false;
	}
}

//��ȡĿ¼
function sreaddir($dir, $extarr=array()) {
	$dirs = array();
	if($dh = opendir($dir)) {
		while (($file = readdir($dh)) !== false) {
			if(!empty($extarr) && is_array($extarr)) {
				if(in_array(strtolower(fileext($file)), $extarr)) {
					$dirs[] = $file;
				}
			} else if($file != '.' && $file != '..') {
				$dirs[] = $file;
			}
		}
		closedir($dh);
	}
	return $dirs;
}

?>