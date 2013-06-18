<?php if( ! $this->input->is_ajax_request()):?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="content-type" content="text/html; charset={{ helper:config item='charset' }}" />
<meta http-equiv="x-ua-compatible" content="ie=7" />
<title>
{{ if template:title }}{{ template:title }} - {{ endif }}
<?php if($space):?><?=$space['username']?> - <?php endif;?>
<?=$config['sitename']?> - Powered by <?=$config['sitename']?>
</title>

<link href="application/themes/default/css/style.css" rel="stylesheet" type="text/css">

{{ theme:favicon file="favicon.ico" }}
<link rel="edituri" type="application/rsd+xml" title="rsd" href="xmlrpc.php?rsd={{ uch:space:uid }}" />
</head>
<body>

<div id="append_parent"></div>
<div id="ajaxwaitid"></div>

<div id="header">

	<div class="headerwarp">
		<h1 class="logo"><a href="index.php"><img src="<?=$config['sitelogo']?>" alt="<?=$config['sitename']?>" /></a></h1>
		<ul class="menu">
		{{ if user:uid }}
			<li><a href="space.php?do=home">首页</a></li>
			<li><a href="space.php">个人主页</a></li>
			<li><a href="space.php?do=friend">好友</a></li>
		{{ else }}
			<li><a href="index.php">首页</a></li>
		{{ endif }}
		
			<li><a href="network.php">随便看看</a></li>
		
		{{ if sglobal:supe_uid }}
			<li><a href="space.php?do=pm{{ if uch:space:newpm }}&filter=newpm{{ endif }}">消息{{ if uch:space:newpm }}(新){{ endif }}</a></li>
			{{if uch:space:notenum }}<li class="notify"><a href="space.php?do=notice">{{ uch:space:notenum }}条新通知</a></li>{{ endif }}
		{{ else }}
			<li><a href="help.php">帮助</a></li>
		{{ endif }}
		</ul>
	
		<div class="nav_account">
		<?php if($user['uid']):?>
			<a href="{{ url:base }}space/uid-{{ sglobal:supe_uid }}.html" class="login_thumb"><img src="{{avatar(sglobal[supe_uid],small)}}" alt="{{ uch:space:realname }}" width="20" height="20" /></a>
			<a href="{{ url:base }}space/uid-{{ sglobal:supe_uid }}.html" class="loginName">{{ uch:space:realname }}</a>
			{{if uch:space:realname != member:username }}({{ member:username }}){{endif}}
			<br />
			<?php if(!$config['closeinvite']):?><a href="cp.php?ac=invite">邀请</a> | <?php endif;?><a href="cp.php">设置</a> | <a href="cp.php?ac=privacy">隐私</a> | <a href="cp.php?ac=common&op=logout">退出</a>
		<?php else:?>
			<a href="<?=site_url('user/'.$config['register_action'])?>" class="login_thumb"><img src="{{avatar(sglobal:supe_uid,small)}}" width="20" height="20" /></a>
			欢迎您<br>
			<a href="<?=site_url('user/'.$config['login_action'])?>">登录</a> | 
			<a href="<?=site_url('user/'.$config['register_action'])?>">注册</a>
		<?php endif;?>
		</div>
	</div>
</div>
	
<div id="wrap">


	<div id="main">
		<div id="app_sidebar">
		<?php if($user['uid']):?>
			<ul class="app_list" id="default_userapp">
				<li><img src="image/app/doing.gif"><a href="space.php?do=doing">记录</a></li>
				<li><img src="image/app/album.gif"><a href="space.php?do=album">相册</a><em><a href="cp.php?ac=upload">上传</a></em></li>
				<li><img src="image/app/blog.gif"><a href="space.php?do=blog">日志</a><em><a href="cp.php?ac=blog">发表</a></em></li>
				<li><img src="image/app/mtag.gif"><a href="space.php?do=thread">群组</a><em><a href="cp.php?ac=thread">话题</a></em></li>
				<li><img src="image/app/share.gif"><a href="space.php?do=share">分享</a></li>		
		<?php else:?>
			<div class="bar_text">
				<form id="loginform" name="loginform" action="<?=site_url('user/'.$config['login_action'].'&ref')?>" method="post">
				<input type="hidden" name="formhash" value="" />
					<p class="title">登录站点</p>
					<p>用户名</p>
					<p><input type="text" name="username" id="username" class="t_input" size="15" value="" /></p>
					<p>密码</p>
					<p><input type="password" name="password" id="password" class="t_input" size="15" value="" /></p>
					<p><input type="checkbox" id="cookietime" name="cookietime" value="315360000" checked /><label for="cookietime">记住我</label></p>
					<p>
						<input type="submit" id="loginsubmit" name="loginsubmit" value="登录" class="submit"  />
						<a href="<?=site_url('user/'.$config['register_action'])?>" class="button">注册</a>
					</p>
				</form>
			</div>
		<?php endif;?>
		</div>

		<div id="mainarea">


<?php endif;?>