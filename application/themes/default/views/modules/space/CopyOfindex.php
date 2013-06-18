{{noparse}}{{ if narrowlist || widelist }}
{{ theme:js file="script_swfobject.js" }}
{{ endif }}{{/noparse}}

	<div id="space_page">

		<div id="ubar">
			<div id="space_avatar">
				<img src="{{avatar($space[uid],big)}}" alt="{{ space:realname }}" />
			</div>
			
			{{ if not space:isfriend }}
			<div class="borderbox">
				如果您认识{{ space:realname }}，可以添加为好友。这样，您就可以第一时间关注到好友的更新动态。<br />
				<img src="image/icon/friend.gif" align="absmiddle"> <a href="cp.php?ac=friend&op=add&uid=$space[uid]" id="a_friend" onclick="ajaxmenu(event, this.id, 99999, '', -1)"><strong>把{{ space:realname }}加为好友</strong></a></p>
			</div>
			{{ endif }}
			
			<ul class="line_list">
		{{ if space:self }}
			<li><img src="image/icon/profile.gif" align="absmiddle"><a href="cp.php?ac=profile">编辑资料</a></li>
		{{ else }}
			<li><img src="image/icon/wall.gif"><a href="#comment">给我留言</a></li>
			<li><img src="image/icon/poke.gif"><a href="cp.php?ac=poke&op=send&uid=$space[uid]" id="a_poke" onclick="ajaxmenu(event, this.id, 99999, '', -1)">打个招呼</a></li>
			<li><img src="image/icon/pm.gif"><a href="cp.php?ac=pm&uid=$space[uid]" id="a_pm" onclick="ajaxmenu(event, this.id, 99999, '', -1)">发送消息</a></li>
			{{ if space:isfriend }}
			<li><img src="image/icon/friend.gif"><a href="cp.php?ac=friend&op=ignore&uid=$space[uid]" id="a_ignore" onclick="ajaxmenu(event, this.id, 99999)">解除好友</a></li>
			{{ endif }}
			<li><img src="image/icon/report.gif"><a href="cp.php?ac=common&op=report&idtype=space&id=$space[uid]" id="a_report" onclick="ajaxmenu(event, this.id, 99999,'' , -1)">违规举报</a></li>
			{{ if checkperm('managespace') }}
			<li><img src="image/icon/profile.gif"><a href="admincp.php?ac=space&op=manage&uid=$space[uid]" id="a_manage">管理用户</a></li>
			{{ endif }}
		{{ endif }}

		{{noparse}}<!--{loop $space['userapp'] $value}-->
			{{ if $value['allowprofilelink'] && $value['profilelink'] }}
			<li id="space_app_profilelink_$value[appid]">
				{{ if space:self }}
				<a href="cp.php?ac=space&op=delete&appid=$value[appid]&type=profilelink" id="user_app_profile_$value[appid]" class="r_option float_del" style="position: static;" onclick="ajaxmenu(event, this.id, 99999)" title="删除">删除</a>
				{{ endif }}
				<img src="http://appicon.manyou.com/icons/$value[appid]"><!--{($value[profilelink]);}-->
			</li>
			{{ endif }}
		<!--{/loop}-->{{/noparse}}
			</ul>

			<div id="space_appicon" class="box">
				<ul class="app_list16">
					{{ if doinglist }}<li class="app-doing"><a href="space.php?uid=$space[uid]&do=doing&view=me" title="记录"><image src="image/app/doing.gif" alt="记录" /></a></li>{{ endif }}
					{{ if bloglist }}<li class="app-blog"><a href="space.php?uid=$space[uid]&do=blog&view=me" title="日志"><image src="image/app/blog.gif" alt="日志" /></a></li>{{ endif }}
					{{ if albumlist }}<li class="app-photo"><a href="space.php?uid=$space[uid]&do=album&view=me" title="相册"><image src="image/app/album.gif" alt="相册" /></a></li>{{ endif }}
					{{ if sharelist }}<li class="app-share"><a href="space.php?uid=$space[uid]&do=share&view=me" title="分享"><image src="image/app/share.gif" alt="分享" /></a></li>{{ endif }}
					{{ if threadlist }}<li class="app-thread"><a href="space.php?uid=$space[uid]&do=thread&view=me" title="话题"><image src="image/app/thread.gif" alt="话题" /></a></li>{{ endif }}

					{{noparse}}<!--{loop $space['userapp'] $value}-->
					{{if $value['allowsidenav'] }}
					<li class="userapp-$value[appid]"><a href="userapp.php?id=$value[appid]&uid=$space[uid]" title="$value[appname]"><img src="http://appicon.manyou.com/icons/$value[appid]" alt="$value[appname]" /></a></li>
					{{ endif }}
					<!--{/loop}-->{{/noparse}}
				</ul>
			</div>
			
			{{ if doinglist }}
			<h2>
			<a href="space.php?uid=$space[uid]&do=doing&view=me" class="r_option">全部</a>
			记录
			</h2>
			<div id="space_doing" class="box">
				<ul class="post_list line_list" id="doing_ul">
				<!--{loop $doinglist $value}-->
					<li>$value[message] (<a href="space.php?uid=$value[uid]&do=doing&doid=$value[doid]&goto=yes">{{ if $value[replynum] }}$value[replynum]个{{ endif }}回复</a>)</li>
				<!--{/loop}-->
				</ul>
			</div>
			{{ elseif space:self }}
			<h2>记录</h2>
			<div class="box">
				<a href="space.php?do=doing&view=me"><img src="image/intro_doing.gif" class="noimage"></a>
				你还没有记录。<br><a href="space.php?do=doing&view=me">点击这里用一句记录自己生活的点点滴滴</a>
			</div>	
			{{ endif }}

			{{noparse}}{{ if threadlist }}
			<h2>
			<a href="space.php?uid=$space[uid]&do=thread&view=me" class="r_option">全部</a>
			话题
			</h2>
			<div id="space_thread" class="box">				
				<ul class="post_list line_list">
				<!--{loop $threadlist $value}-->
					<li><a href="space.php?uid=$value[uid]&do=thread&id=$value[tid]">$value[subject]</a> <span class="time"><!--{date('m-d H:i',$value[dateline],1)}--></span>
					</li>
				<!--{/loop}-->
				</ul>
			</div>
			{{ elseif space:self }}
			<h2>话题</h2>
			<div class="box">
				<a href="cp.php?ac=thread"><img src="image/intro_thread.gif" class="noimage"></a>
				你还没有话题。<br><a href="cp.php?ac=thread">点击这里可以在群组中与大家讨论话题</a>
			</div>	
			{{ endif }}

			{{ if sharelist }}
			<h2>
			<a href="space.php?uid=$space[uid]&do=share&view=me" class="r_option">全部</a>
			分享
			</h2>
			<div id="space_share" class="box">
				<ul class="post_list line_list">
				<!--{loop $sharelist $value}-->
				<li>
				<div class="title">
					<div class="r_option"><a href="space.php?uid=$value[uid]&do=share&id=$value[sid]">评论</a></div>
					$value[title_template]
				</div>
				<div class="feed">
				{{ if $value['image'] }}
				<a href="$value[image_link]"><img src="$value[image]" class="summaryimg image" alt="" width="70" /></a>
				{{ endif }}
				<div class="detail">
				$value[body_template]
				{{ if 'video' == $value['type'] }}
				<br />
				<a href="space.php?uid=$value[uid]&do=share&id=$value[sid]"><img src="image/vd.gif" width="80" alt="点击查看" /></a>
				{{ elseif 'music' == $value['type']}}
				<br />
				<a href="space.php?uid=$value[uid]&do=share&id=$value[sid]"><img src="image/music.gif" alt="点击查看" /></a>
				{{ elseif 'flash' == $value['type']}}
				<br />
				<a href="space.php?uid=$value[uid]&do=share&id=$value[sid]"><img src="image/flash.gif" alt="点击查看" /></a>
				{{ endif }}
				</div>
				<div class="quote"><span id="quote" class="q">$value[body_general]</span></div>
				</div>
				</li>
				<!--{/loop}-->
				</ul>
			</div>
			{{ elseif space:self }}
			<h2>分享</h2>
			<div class="box">
				<a href="space.php?do=share&view=me"><img src="image/intro_share.gif" class="noimage"></a>
				你还没有分享。<br /><a href="space.php?do=share&view=me">点击这里可以在分享网址、视频、音乐、Flash动画</a>
			</div>
			{{ endif }}
			
			<!--{loop $narrowlist $value}-->
			<div id="space_app_$value[appid]">
				<h2>
					{{ if space:self }}
					<a href="cp.php?ac=space&op=delete&appid=$value[appid]" id="user_app_$value[appid]" class="r_option float_del" onclick="ajaxmenu(event, this.id, 99999)" title="删除">删除</a>
					{{ endif }}
					<a href="$value[appurl]">$value[appname]</a>
				</h2>
				{{ if $value[myml] }}
				<div class="box">
				
				<!--{($value[myml]);}-->
				</div>
				{{ endif }}
			</div>
			<!--{/loop}-->{{/noparse}}
		</div>
		
		<div id="content">
			<div class="tabs_header">
				<a href="cp.php?ac=share&type=space&id=$space[uid]" class="a_share" id="a_share" onclick="ajaxmenu(event, this.id, 99999, '', -1)">分享</a>
				<a href="rss.php?uid=$space[uid]" id="i_rss" title="订阅 RSS">订阅</a>
				<ul class="tabs">
					<li class="active myhome"><a href="space.php?uid=$space[uid]"><span>主页</span></a></li>
					{{ if ckprivacy('doing') }}<li class="mydoing"><a href="space.php?uid=$space[uid]&do=doing&view=me"><span>记录</span></a></li>{{ endif }}
					{{ if ckprivacy('blog') }}<li class="myblog"><a href="space.php?uid=$space[uid]&do=blog&view=me"><span>日志</span></a></li>{{ endif }}
					{{ if ckprivacy('album') }}<li class="myalbum"><a href="space.php?uid=$space[uid]&do=album&view=me"><span>相册</span></a></li>{{ endif }}
					{{ if ckprivacy('share') }}<li class="myshare"><a href="space.php?uid=$space[uid]&do=share&view=me"><span>分享</span></a></li>{{ endif }}
					{{ if ckprivacy('mtag') }}<li class="mythread"><a href="space.php?uid=$space[uid]&do=thread&view=me"><span>话题</span></a></li>{{ endif }}
					{{ if ckprivacy('wall') }}<li class="mywall"><a href="space.php?uid=$space[uid]&do=wall&view=me"><span>留言</span></a></li>{{ endif }}
					{{ if ckprivacy('friend') }}<li class="myfriend"><a href="space.php?uid=$space[uid]&do=friend&view=me"><span>好友</span></a></li>{{ endif }}
				</ul>
			</div>

		
			<div class="composer">

				<strong class="index_name"<!--{g_color($space[groupid]);}-->>{{ if sconfig:realname && space:name && space:namestatus }}<!--{stripslashes($space['name']);}--> <em>(用户名:<!--{echo stripslashes($space['username']);}-->)</em>{{ else }}<!--{echo stripslashes($space['username']);}-->{{ if space:name }} <em>(姓名:<!--{echo stripslashes($space['name']);}-->)</em>{{ endif }}{{ endif }}<!--{g_icon($space[groupid]);}--></strong>
				{{ if isonline }}<span class="time">在线($isonline)</span>{{ endif }}
				<p class="gray">
					<a href="{{ space:domainurl }}" onclick="javascript:setCopy('{{ space:domainurl }}');return false;" class="spacelink domainurl">{{ space:domainurl }}</a>
				</p>
				<p>
				
					{{ if space:self }}
					已有 <a href="space.php?uid=$space[uid]&do=friend&view=visitor">{{ space:viewnum }}</a> 人次访问, <a href="cp.php?ac=credit">$space[credit]</a>个积分 <a href="cp.php?ac=credit">$space[creditstar]</a>
					{{ else }}
					已有 $space[viewnum] 人次访问, $space[credit]个积分 <a href="do.php?ac=ajax&op=credit&uid=$space[uid]" id="a_space_view" onclick="ajaxmenu(event, this.id, 99999)">$space[creditstar]</a>
					{{ endif }}
				</p>

				<div class="current_status" id="mystate">
					{{ if space:mood }}<a href="space.php?uid=$space[uid]&do=mood" title="同心情"><img src="image/face/{$space[mood]}.gif" alt="同心情" class="face" /></a> {{ endif }}
					{{ if space:spacenote }}{{ space:spacenote }}{{ endif }}
					{{ if space:self }}
					{{ if not space:mood && space:spacenote }}
						您在做什么？
					{{ endif }}
					&nbsp;(<a href="javascript:;" onclick="mood_from();" title="更新状态">更新状态</a><span class="pipe">|</span><a href="space.php?uid=$space[uid]&do=mood">同心情</a>)
					{{ endif }}
				</div>
				
				{{ if space:showprofile }}
				<ul class="profile">
					{{ if space:sex }}
						<li>性别：{{ space:sex }}</li>
					{{ endif }}
					{{ if space:birthday }}
						<li>生日：{{ space:birthday }}</li>
					{{ endif }}
					{{ if space:blood }}
						<li>血型：{{ space:blood }}</li>
					{{ endif }}
					{{ if space:marry }}
						<li>婚恋：{{ space:marry }}</li>
					{{ endif }}
					{{ if space:reside }}
					<li>居住：{{ space:reside }}</li>
					{{ endif }}
					{{ if space:birth }}
					<li>家乡：{{ space:birth }}</li>
					{{ endif }}
					{{ if space:qq }}
					<li>QQ：{{ space:qq }}</li>
					{{ endif }}
					{{ if space:msn }}
					<li>MSN：{{ space:msn }}</li>
					{{ endif }}
				
					<!--{loop $fields $fieldid $value}-->
					{{ if $space["field_$fieldid"] && empty($value['invisible']) }}
					<!--{$fieldvalue = $space["field_$fieldid"]; $urlvalue = rawurlencode($fieldvalue);}-->
					<li>$value[title]：{{ if $value[allowsearch] }}
					<a href="network.php?ac=space&field_$fieldid=$urlvalue&searchmode=1">$fieldvalue</a>
					{{ else }}$fieldvalue{{ endif }}</li>
					{{ endif }}
					<!--{/loop}-->
				</ul>
				{{ endif }}			
				
			</div>

	
		{{noparse}}{{ if feedlist }}
		<div id="space_feed" class="feed">
			<h3 class="feed_header">
				<span class="r_option">
				<a href="space.php?uid=$space[uid]&do=feed&view=me" class="action">全部</a>
				</span>
				<span class="entry-title">个人动态</span>
			</h3>
			<div class="box_content">
				<ul>
				<!--{loop $feedlist $value}-->
					<!--{template space_feed_li}-->
				<!--{/loop}-->
				</ul>
			</div>
		</div>
		{{ endif }}
			
		{{ if albumlist }}
		<div id="space_photo">
			<h3 class="feed_header">
				<a href="space.php?uid=$space[uid]&do=album&view=me" class="r_option">全部</a>
				相册			
			</h3>
			<table cellspacing="4" cellpadding="4">
			<tr>
				<!--{loop $albumlist $key $value}-->
				<td width="85" align="center"><a href="space.php?uid=$space[uid]&do=album&id=$value[albumid]"><img src="$value[pic]" alt="$value[albumname]" width="70" /></a></td>
				<td width="165">
					<h6><a href="space.php?uid=$space[uid]&do=album&id=$value[albumid]" title="$value[albumname]">$value[albumname]</a></h6>
					<p class="gray">$value[picnum] 张照片</p>
					<p class="gray">更新于 <!--{date('m-d',$value[dateline],1)}--></p>
				</td>
				{{ if $key%2==1 }}</tr><tr>{{ endif }}
				<!--{/loop}-->
			</tr>
			</table>
		</div>
		{{ elseif space:self }}
		<div>
			<h3 class="feed_header">相册</h3>
			<div style="padding:10px;">
				<a href="cp.php?ac=upload"><img src="image/intro_album.gif" align="absmiddle"></a>
				你还没有相册。<a href="cp.php?ac=upload">点击这里可以上传图片到自己的相册</a>
			</div>
		</div>
		{{ endif }}
	
		{{ if bloglist }}
		<div id="space_blog" class="feed">
			<h3 class="feed_header">
				<a href="space.php?uid=$space[uid]&do=blog&view=me" class="r_option">全部</a>
				日志
			</h3>
			<ul class="line_list">
			<!--{loop $bloglist $value}-->
				<li>
					<h4><a href="space.php?uid=$space[uid]&do=blog&id=$value[blogid]">$value[subject]</a></h4>
					<div class="detail">
						$value[message]
					</div>
					<p class="stat"><a href="space.php?uid=$space[uid]&do=blog&id=$value[blogid]">阅读全文</a><span class="pipe">|</span><span class="time"><!--{date('m-d H:i',$value[dateline],1)}--></span><span class="pipe">|</span><!--{if $value[replynum]}--><a href="space.php?uid=$space[uid]&do=blog&id=$value[blogid]#comment">$value[replynum]人评论</a><!--{else}--><span class="pipe">没有评论</span>{{ endif }}</p>
				</li>
			<!--{/loop}-->
			</ul>
		</div>
		{{ elseif space:self }}
		<div>
			<h3 class="feed_header">日志</h3>
			<div style="padding:10px;">
				<a href="cp.php?ac=blog"><img src="image/intro_blog.gif" align="absmiddle"></a>
				你还没有日志。<a href="cp.php?ac=blog">点击这里可以写下自己的第一篇日志</a>
			</div>
		</div>
		{{ endif }}
	
		{{ if mtaglist }}
		<div id="space_mtag">
			<h3 class="feed_header">群组</h3>
			<div class="mtagbox">
			<!--{loop $mtaglist $fieldid $values}-->
			<table cellspacing="0" cellpadding="0" class="infotable">
				<tr>
					<th width="100"><strong>{$_SGLOBAL[profield][$fieldid][title]}</strong></th>
					<td>
					<!--{$dot = '';}-->
					<!--{loop $values $key $value}-->
					$dot<a href="space.php?do=mtag&tagid=$value[tagid]" title="$value[membernum]个人有同样的选择">$value[tagname]</a>
					<!--{$dot = '、';}-->
					<!--{/loop}-->
					</td>
				</tr>
			</table>
			<!--{/loop}-->
			</div>
		</div>
		{{ elseif space:self }}
		<div>
			<h3 class="feed_header">群组</h3>
			<div style="padding:10px;">
				<a href="cp.php?ac=mtag"><img src="image/intro_mtag.gif" align="absmiddle"></a>
				你还没有群组。<a href="cp.php?ac=mtag">点击这里可以创建自己的群组，与志同道合朋友一起交流</a>
			</div>
		</div>
		{{ endif }}
		
		
		<!--{loop $widelist $value}-->
		{{ if $value[myml] }}
		<div id="space_app_$value[appid]" class="appbox">
		<h3 class="feed_header">
			{{ if space:self }}
			<a href="cp.php?ac=space&op=delete&appid=$value[appid]" id="user_app_$value[appid]" class="r_option float_del" onclick="ajaxmenu(event, this.id, 99999)" title="删除">删除</a>
			{{ endif }}
			<a href="$value[appurl]">$value[appname]</a>
		</h3>
		<div  class="box" style="margin: 0 0 20px;">		
		<!--{($value[myml]);}-->
		</div>
		</div>
		{{ endif }}
		<!--{/loop}-->
		
		<div id="comment" class="comments_list">
			<h3 class="feed_header">
			{{ if $wallnum }}
			<a href="space.php?uid=$space[uid]&do=wall&view=me" class="r_option">共有<strong>{$wallnum}</strong>条留言</a>
			{{ endif }}
			留言板
			</h3>
			
			{{ if not space:self }}
			<div class="box">
				<form action="cp.php?ac=comment" id="quick_commentform_{$space[uid]}" name="quick_commentform_{$space[uid]}" method="post" style="padding:0 0 0 5px;">
					<a href="###" id="editface" onclick="showFace(this.id, 'comment_message');"><img src="image/facelist.gif" align="absmiddle" /></a><br>
					<textarea name="message" id="comment_message" rows="4" cols="60" style="width:98%;" onkeydown="ctrlEnter(event, 'commentsubmit_btn');"></textarea><br>
					<input type="hidden" name="refer" value="space.php?uid=$space[uid]" />
					<input type="hidden" name="id" value="$space[uid]" />
					<input type="hidden" name="idtype" value="uid" />
					<input type="hidden" name="commentsubmit" value="true" />
					<input type="button" id="commentsubmit_btn" name="commentsubmit_btn" class="submit" value="留言" onclick="ajaxpost('quick_commentform_{$space[uid]}', 'comment_status', 'wall_add')" />
					<span id="comment_status"></span>
					<input type="hidden" name="formhash" value="<!--{echo formhash();}-->" />
				</form>
			</div>
			{{ endif }}

			<div class="box_content">
				<ul class="post_list a_list justify_list" id="comment_ul">
				<!--{loop $walllist $value}-->
					<!--{template space_comment_li}-->
				<!--{/loop}-->
				</ul>
				{{ if walllist }}
				<p class="r_option" style="padding:5px 0 10px 0;"><a href="space.php?uid=$space[uid]&do=wall&view=me">&gt;&gt; 更多留言</a></p>
				{{ endif }}
			</div>
		</div>
	
		</div>

		<div id="obar">
		{{ if visitorlist }}
		<div class="sidebox">
			<h2 class="title">
			<a href="space.php?uid=$space[uid]&do=friend&view=visitor" class="r_option">全部</a>
			最近来访
			</h2>
			<ul class="avatar_list">
				<!--{loop $visitorlist $key $value}-->
				<li>
					<div class="avatar48"><a href="space.php?uid=$value[vuid]"><img src="<!--{avatar($value[vuid],small)}-->" alt="{$_SN[$value[vuid]]}" /></a></div>
					<p{{ if $ols[$value[vuid]] }} class="online_icon_p"{{ endif }}><a href="space.php?uid=$value[vuid]" title="{$_SN[$value[vuid]]}">{$_SN[$value[vuid]]}</a></p>
					<p class="time"><!--{date('n月j日',$value[dateline],1)}--></p>
				</li>
				<!--{/loop}-->
			</ul>
		</div>
		{{ endif }}
		
		{{ if friendlist }}
		<div class="sidebox">
			<h2 class="title">
			<span class="r_option">
				<a href="space.php?uid=$space[uid]&do=friend&view=me" class="action">全部($space[friendnum])</a>
			</span>
			好友
			</h2>
			<ul class="avatar_list">
				<!--{loop $friendlist $value}-->
				<li>
				<div class="avatar48"><a href="space.php?uid=$value[fuid]"><img src="<!--{avatar($value[fuid],small)}-->" alt="{$_SN[$value[fuid]]}" class="avatar" /></a></div>
				<p{{ if $ols[$value[fuid]] }} class="online_icon_p"{{ endif }}><a href="space.php?uid=$value[fuid]">{$_SN[$value[fuid]]}</a></p>
				</li>
				<!--{/loop}-->
			</ul>
		</div>
		{{ elseif space:self }}
		<div class="sidebox">
			<h2 class="title">好友</h2>
			<div style="padding:10px;">
				<a href="cp.php?ac=friend&op=find"><img src="image/intro_friend.gif" class="noimage"></a>
				你还没有好友。<br><a href="cp.php?ac=friend&op=find">点这里寻找可能认识的人</a>
			</div>
		</div>
		{{ endif }}{{/noparse}}
		
		</div>
	</div>


<div id="mood_form" style="display:none">
	<form method="post" action="cp.php?ac=doing" id="moodform">
		<table cellpadding="0" cellspacing="0">
			<tr>
				<td width="40"><a href="###" id="face" onclick="showFace(this.id, 'message');"><img src="image/facelist.gif" align="absmiddle" /></a></td>
				<td>
					<input type="text" name="message" id="message" value="" size="30" class="t_input" />
					<input type="hidden" name="addsubmit" value="true" />
					<input type="hidden" name="spacenote" value="true" />
				</td>
				<td>&nbsp;<input type="button" id="add" name="add" value="更新" class="submit" onclick="ajaxpost('moodform', 'mystate', 'reloadMood');" /></td>
				<td>&nbsp;<a href="javascript:;" onclick="mood_form_cancel();">取消</a></td>
			</tr>
		</table>
		<input type="hidden" name="formhash" value="<!--{echo formhash();}-->" />
	</form>
</div>

<script type="text/javascript">
	var old_html = '';
	function reloadMood(showid, result) {
		var x = new Ajax();
		x.get('do.php?ac=ajax&op=getmood', function(s){
			$('mystate').innerHTML = s;
		});
	}
	function mood_from() {
		old_html = $('mystate').innerHTML;
		$('mystate').innerHTML = $('mood_form').innerHTML;
	}
	function mood_form_cancel() {
		$('mystate').innerHTML = old_html;
	}
</script>
{{ if input:theme}}<div class="nn">您是否想使用这款个性风格?<br /><a href="cp.php?ac=theme&op=use&dir=<!--{$_GET['theme']}-->">[应用]</a><a href="cp.php?ac=theme">[取消]</a></div>{{ endif }}		