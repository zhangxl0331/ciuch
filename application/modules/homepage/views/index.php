

<div id="index_page">
<div id="wide">
	<div class="start">
		<h2>欢迎您，已经有 <a href="network.php?ac=space"><span>$spacecount</span> 位成员</a> 入住家园了！</h2>
		<ul>
			<li>* 在这里，您可以用<a href="help.php?ac=doing">一句话记录</a>生活中的点点滴滴</li>
			<li>* 方便快捷地<a href="help.php?ac=blog">发布日志</a>、<a href="help.php?ac=album">上传图片</a></li>
			<li>* 与好友们一起<a href="help.php?ac=share">分享信息</a>、<a href="help.php?ac=mtag">讨论感兴趣的话题</a></li>
			<li>* 更可轻松快捷了解<a href="help.php?ac=home">好友最新动态</a>，与好友一起玩有趣的小应用</li>
		</ul>
		<p><a href="do.php?ac=$_SCONFIG[register_action]" class="reg_button">立即注册</a></p>
	</div>
	<div class="m_box">
		<div class="left_box">
		<div class="showflash">
		<script type="text/javascript">
			var interval_time = 5;
			var focus_width = 338;
			var focus_height = 268;
			var text_height = 1;
			var text_mtop = 0;
			var text_lm = 0;
			var textmargin = text_mtop+"|"+text_lm;
			var textcolor = "#ffffff|#ffffff";
			var text_align= 'center'; 
			var swf_height = focus_height; 
			var text_size = 12;
			var borderStyle="1|0xFFFFFF|100";
			
			<!--{eval $linkstr=$imgs=$imglinks=$imgtexts=''}-->
			<!--{loop $piclist $key $value}-->
				<!--{eval $title = addslashes(strip_tags($value['title']));}-->
				<!--{eval $imgs.=$linkstr.$value[pic]}-->
				<!--{eval $imglinks.=$linkstr."space.php?uid=$value[uid]%26do=album%26picid=$value[picid]"}-->
				<!--{eval $imgtexts.=$linkstr.$title}-->
				<!--{eval $linkstr='|'}-->
			<!--{/loop}-->
			
			var pics='$imgs';
			var links='$imglinks';
			var texts='$imgtexts';
			
			document.write('<object classid="clsid:d27cdb6e-ae6d-11cf-96b8-444553540000" codebase="http://fpdownload.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=6,0,0,0" width="'+ focus_width +'" height="'+ swf_height +'">');
			document.write('<param name="allowScriptAccess" value="sameDomain"><param name="movie" value="image/slide.swf"> <param name="quality" value="high"><param name="Wmode" value="transparent">');
			document.write('<param name="menu" value="false"><param name="wmode" value="opaque">');
			document.write('<param name="FlashVars" value="pics='+pics+'&links='+links+'&texts='+texts+'&borderwidth='+focus_width+'&borderheight='+focus_height+'&textheight='+text_height+'&textmargin='+textmargin+'&textcolor='+textcolor+'&borderstyle='+borderStyle+'&text_align='+text_align+'&interval_time='+interval_time+'&textsize='+text_size+'">');
			document.write('<embed src="image/slide.swf" wmode="opaque" FlashVars="pics='+pics+'&links='+links+'&texts='+texts+'&borderwidth='+focus_width+'&borderheight='+focus_height+'&textheight='+text_height+'&textmargin='+textmargin+'&textcolor='+textcolor+'&borderstyle='+borderStyle+'&text_align='+text_align+'&interval_time='+interval_time+'&textsize='+text_size+'" menu="false" quality="high" width="'+ focus_width +'" height="'+ swf_height +'" allowScriptAccess="sameDomain" type="application/x-shockwave-flash" pluginspage="http://www.macromedia.com/go/getflashplayer" />');
			document.write('</object>');
		</script>
		</div>
		</div>
		<div class="right_box">
			<ul class="news_list" style="overflow: hidden; height: 277px;">
				<!--{loop $bloglist $key $value}-->
				<li<!--{if $key==0}--> class="news_title"<!--{/if}-->><a href="space.php?uid={$value[uid]}&do=blog&id={$value[blogid]}">$value[subject]</a></li>
				<!--{/loop}-->
			</ul>
		</div>
	</div>
	
	<!--{if $albumlist}-->
	<div class="m_box">
		<div class="ye_r_t"><div class="ye_l_t"><div class="ye_r_b"><div class="ye_l_b">
			<ul class="albs">
			<!--{loop $albumlist $value}-->
				<li><a href="space.php?uid={$value[uid]}&do=album&id={$value[albumid]}" title="$value[albumname]"><img src="$value[pic]" alt="$value[albumname]"  style="width: 75px;height:75px;" /></a></li>
			<!--{/loop}-->
			</ul>
		</div></div></div></div>
	</div>
	<!--{/if}-->
	
	<div class="m_box">
		<div class="left_box">
		<!--{if $_SCONFIG['my_status']}-->
			<h3 class="title">好玩的应用</h3>
			<ul class="apps">
			<!--{loop $myapplist $key $value}-->
				<!--{if $key==0}-->
				<li class="first">
					<a href="userapp.php?id=$value[appid]"><img src="http://appicon.manyou.com/logos/$value[appid]" alt="$value[appname]" /></a>
					<h4><a href="userapp.php?id=$value[appid]">$value[appname]</a></h4>
					<p>赶快加入，与好友一起玩各种好玩的应用啦</p>
				</li>
				<!--{else}-->
				<li>
					<h4><a href="userapp.php?id=$value[appid]">$value[appname]</a></h4>
					<a href="userapp.php?id=$value[appid]"><img src="http://appicon.manyou.com/logos/$value[appid]" alt="$value[appname]" /></a>
				</li>
				<!--{/if}-->
			<!--{/loop}-->
			</ul>
		<!--{else}-->
			<h3 class="title">当前在线的朋友</h3>
			<ul class="apps">
			<!--{loop $onlinelist $key $value}-->
				<!--{if $key==0}-->
				<li class="first">
					<a href="space.php?uid=$value[uid]"><img src="<!--{avatar($value[uid],small)}-->" alt="{$_SN[$value[uid]]}" /></a>
					<h4><a href="space.php?uid=$value[uid]">{$_SN[$value[uid]]}</a></h4>
					<p>朋友们都在线，您还等什么，赶快加入吧</p>
				</li>
				<!--{else}-->
				<li>
					<h4><a href="space.php?uid=$value[uid]">{$_SN[$value[uid]]}</a></h4>
					<a href="space.php?uid=$value[uid]"><img src="<!--{avatar($value[uid],small)}-->" alt="{$_SN[$value[uid]]}" /></a>
				</li>
				<!--{/if}-->
			<!--{/loop}-->
			</ul>			
		<!--{/if}-->
		</div>
		
		<div class="right_box">
			<h3 class="title">群组话题</h3>
			<ul class="imtag">
			<!--{loop $mtaglist $value}-->
				<li>
					<div class="threadimg60">
						<a href="space.php?do=mtag&tagid={$value[tagid]}"><img style="width: 60px;height:60px;" src="{$value[pic]}"/></a>
					</div>
					<h4><a href="space.php?do=mtag&tagid={$value[tagid]}">{$value[tagname]}</a></h4>
					<ul class="news_list">
					<!--{loop $threadlist[$value[tagid]] $thread}-->
						<li><a href="space.php?uid={$thread[uid]}&do=thread&id={$thread[tid]}">{$thread[subject]}</a></li>
					<!--{/loop}-->
					</ul>
				</li>
			<!--{/loop}-->
			</ul>
		</div>
		
	</div>
</div>

<div id="narrow">
	<div class="login_box">
		<h3>请登录</h3>
		<form id="loginform" name="loginform" class="login_form" action="do.php?ac=$_SCONFIG[login_action]&$url_plus&ref" method="post">
			<p><label for="username">用户名</label>	<input type="text" name="username" id="username" class="t_input" value="$membername" /></p>
			<p><label for="password">密　码</label>	<input type="password" name="password" id="password" class="t_input" value="$password" /></p>
			<p><input type="checkbox" id="cookietime" name="cookietime" value="315360000" $cookiecheck style="margin-bottom: -2px;"><label for="cookietime">下次自动登录</label></p>
			<p>
				<input type="hidden" name="refer" value="space.php?do=home" />
				<input type="submit" id="loginsubmit" name="loginsubmit" value="登录" class="submit" />
				<a href="do.php?ac=lostpasswd">忘记密码?</a>
				<input type="hidden" name="formhash" value="<!--{eval echo formhash();}-->" />
			</p>
		</form>
	</div>
	
	<div class="searchfirend">
		<div class="ye_r_t"><div class="ye_l_t"><div class="ye_r_b"><div class="ye_l_b">
			<h3>寻找好友</h3>
			<form method="get" action="network.php">
			<p>
				<input type="radio" name="sex" value="0"> 不限性别
				<input type="radio" name="sex" value="1"> 帅哥
				<input type="radio" name="sex" value="2" checked> 美女
			</p>
			<p>
				<script type="text/javascript" src="source/script_city.js"></script>
				<script type="text/javascript">
				<!--
				showprovince('resideprovince', 'residecity', '');
				showcity('residecity', '');
				//-->
				</script>	
			
				<input type="hidden" name="ac" value="space" />
				<input type="hidden" name="searchmode" value="1" />
				<input type="submit" name="findsubmit" value="找人" class="submit" />
			</p>
			</form>
		</div></div></div></div>

	</div>
	
	<div class="ifeed">
		<h3 class="title">看看大家现在正在做什么…</h3>

		<ul class="line_list" id="scrollbody" style="height: 250px; overflow:hidden;">
		<!--{loop $feedlist $value}-->
			<li>$value[title_template]</li>
		<!--{/loop}-->
		</ul>
		<script>startMarquee(250, 60, 0, 'scrollbody');</script>
	</div>



	<div class="sidebox">
		<h3 class="title">热门用户</h3>
		<ul class="avatar_list">
		<!--{loop $spacelist $value}-->
			<li>
				<div class="avatar48"><a href="space.php?uid=$value[uid]"><img src="<!--{avatar($value[uid],small)}-->" alt="{$_SN[$value[uid]]}" /></a></div>
				<p><a href="space.php?uid=$value[uid]" title="{$_SN[$value[uid]]}">{$_SN[$value[uid]]}</a></p>
			</li>
		<!--{/loop}-->
		</ul>
	</div>
</div>
</div>

<!--{template footer}-->
