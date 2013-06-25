{{ if not input:inajax }}
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="content-type" content="text/html; charset={{ helper:config item='charset' }}" />
<meta http-equiv="x-ua-compatible" content="ie=7" />
<title>
{{ if template:title }}{{ template:title }} - {{ endif }}
{{ if global:space:realname }}{{ global:space:username }} - {{ endif }}
{{ config:sitename }} - Powered by {{ config:sitename }}
</title>
{{ theme:js file="jquery-1.4.2.min.js" }}

{{ if global:space:theme }}
{{ theme:css file="style.css" theme=default }}
{{ theme:css file="style.css" theme=global:space:theme }}
{{ else }}
{{ theme:css file="style.css" theme=config:template }}
{{ endif }}
<style type="text/css">
{{ if global:space:css }}
{{ global:space:css }}
{{ endif }}
</style>

{{ theme:favicon file="favicon.ico" }}
<link rel="edituri" type="application/rsd+xml" title="rsd" href="xmlrpc.php?rsd={{ global:space:uid }}" />
</head>
<body>

<div id="append_parent"></div>
<div id="ajaxwaitid"></div>

<div id="header">
	{{ if ad:header }}<div id="ad_header">{{ad/header}}</div>{{ endif }}
	<div class="headerwarp">
		<h1 class="logo"><a href="index.php"><img src="{{ config:sitelogo }}" alt="{{ config:sitename }}" /></a></h1>
		<ul class="menu">
		{{ if auth:uid }}
			<li><a href="<?=site_url('member/home')?>">首页</a></li>
			<li><a href="<?=site_url('member/index/'.$auth['uid'])?>">个人主页</a></li>
			<li><a href="space.php?do=friend">好友</a></li>
		{{ else }}
			<li><a href="index.php">首页</a></li>
		{{ endif }}
		
			<li><a href="network.php">随便看看</a></li>
		
		{{ if auth:uid }}
			<li><a href="space.php?do=pm{{ if global:space:newpm }}&filter=newpm{{ endif }}">消息{{ if global:space:newpm }}(新){{ endif }}</a></li>
			{{if global:space:notenum }}<li class="notify"><a href="space.php?do=notice">{{ global:space:notenum }}条新通知</a></li>{{ endif }}
		{{ else }}
			<li><a href="help.php">帮助</a></li>
		{{ endif }}
		</ul>
	
		<div class="nav_account">
		{{ if auth:uid }}
			<a href="<?=site_url('member/index/'.$auth['uid'])?>" class="login_thumb"><img src="{{avatar(sglobal[supe_uid],small)}}" alt="{{ global:space:realname }}" width="20" height="20" /></a>
			<a href="<?=site_url('member/index/'.$auth['uid'])?>" class="loginName">{{ auth:username }}</a>
			
			<br />
			{{if not config:closeinvite }}<a href="cp.php?ac=invite">邀请</a> | {{ endif }}<a href="cp.php">设置</a> | <a href="cp.php?ac=privacy">隐私</a> | <a href="<?=site_url('member/logout')?>">退出</a>
		{{ else }}
			<a href="<?=site_url('member/'.$config['register_action'])?>" class="login_thumb"><img src="{{avatar(auth:uid,small)}}" width="20" height="20" /></a>
			欢迎您<br>
			<a href="<?=site_url('member/'.$config['login_action'])?>">登录</a> | 
			<a href="<?=site_url('member/'.$config['register_action'])?>">注册</a>
		{{ endif }}
		</div>
	</div>
</div>
	
<div id="wrap">

	{{if not nosidebar }}
	<div id="main">
		<div id="app_sidebar">
		{{ if auth:uid }}
			<ul class="app_list" id="default_userapp">
				<li><img src="image/app/doing.gif"><a href="<?=site_url('doing/index/'.$auth['uid'])?>">记录</a></li>
				<li><img src="image/app/album.gif"><a href="<?=site_url('album/index/'.$auth['uid'])?>">相册</a><em><a href="cp.php?ac=upload">上传</a></em></li>
				<li><img src="image/app/blog.gif"><a href="<?=site_url('blog/index/'.$auth['uid'])?>">日志</a><em><a href="cp.php?ac=blog">发表</a></em></li>
				<li><img src="image/app/mtag.gif"><a href="<?=site_url('thread/index/'.$auth['uid'])?>">群组</a><em><a href="cp.php?ac=thread">话题</a></em></li>
				<li><img src="image/app/share.gif"><a href="<?=site_url('share/index/'.$auth['uid'])?>">分享</a></li>
				
			{{ if config:my_status }}
				{{ userapp:default_menu }}
				<li><img src="http://appicon.manyou.com/icons/{{ appid }}"><a href="userapp.php?id={{ appid }}">{{ appname }}</a></li>
				{{ /userapp:default_menu }}
			{{ endif }}
			</ul>
			
			{{ if config:my_status }}
			{{ userapp:my_menu uid=global:space:uid limit=global:space:menunum }}
			<ul class="app_list" id="my_userapp">
				{{ my_menu }}
				<li id="userapp_li_{{ appid }}"><img src="http://appicon.manyou.com/icons/{{ appid }}"><a href="userapp.php?id={{ appid }}" title="{{ appname }}">{{ appname }}</a></li>
				{{ /my_menu }}
			</ul>			
			
			{{if my_menu_more }}
			<p class="app_more"><a href="javascript:;" id="a_app_more" onclick="userapp_open();" class="off">展开</a></p>
			{{ endif }}
			{{ /userapp:my_menu}}
			
			<div class="app_m">
				<ul>
					<li><img src="image/app_add.gif"><a href="cp.php?ac=userapp&my_suffix=%2Fapp%2Flist" class="addApp">添加应用</a></li>
					<li><img src="image/app_set.gif"><a href="cp.php?ac=userapp&op=menu" class="myApp">管理应用</a></li>
				</ul>
			</div>
			{{ endif }}
		
		{{ else }}
			<div class="bar_text">
				<form id="loginform" name="loginform" action="<?=site_url('member/'.$config['login_action'])?>" method="post">
				<input type="hidden" name="formhash" value="" />
					<p class="title">登录站点</p>
					<p>用户名</p>
					<p><input type="text" name="username" id="username" class="t_input" size="15" value="" /></p>
					<p>密码</p>
					<p><input type="password" name="password" id="password" class="t_input" size="15" value="" /></p>
					<p><input type="checkbox" id="cookietime" name="cookietime" value="315360000" checked /><label for="cookietime">记住我</label></p>
					<p>
						<input type="submit" id="loginsubmit" name="loginsubmit" value="登录" class="submit"  />
						<a href="<?=site_url('member/'.$config['register_action'])?>" class="button">注册</a>
					</p>
				</form>
			</div>
		{{ endif }}
		</div>

		<div id="mainarea">
		
		{{ if ad:contenttop }}<div id="ad_contenttop">{{ad/contenttop}}</div>{{ endif }}
	{{ endif }}

{{ endif }}