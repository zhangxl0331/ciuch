{{ if space:self}}
<h2 class="title"><img src="image/app/blog.gif" />{{ global:module lang="true" }}</h2>
<div class="tabs_header">
	<ul class="tabs">
		{{ if space:friendnum }}<li{{ if not input:view }} class="active"{{ endif }}><a href="{{ global:module }}?uid={{ space:uid }}"><span>好友最新{{ global:module lang="true" }}</span></a></li>{{ endif }}
		<li{{ if input:view=='me' }} class="active"{{ endif }}><a href="{{ global:module }}?uid={{ space:uid }}&view=me"><span>我的{{ global:module lang="true" }}</span></a></li>
		<li{{ if input:view=='trace' }} class="active"{{ endif }}><a href="{{ global:module }}?uid={{ space:uid }}&view=trace"><span>我踩过的{{ global:module lang="true" }}</span></a></li>
		<li{{ if input:view=='all' }} class="active"{{ endif }}><a href="{{ global:module }}?uid={{ space:uid }}&view=all"><span>大家的{{ global:module lang="true" }}</span></a></li>
		<li class="null"><a href="cp.php?ac={{ global:module }}">发表新{{ global:module lang="true" }}/a></li>
	</ul>
</div>
{{ else }}
{{ theme:partial name="space_menu"}}
{{ endif }}

<div class="h_status">
	{{ if blog:related }}<a href="javascript:;" onclick="closeSide2(this);" id="a_showSide" class="r_option">&raquo; 关闭侧边栏</a>{{ endif }}
	查看日志<span class="pipe">|</span><a href="blog?uid={{ space:uid }}&view=me">返回{{ helper:lang line="blog" }}列表</a>
</div>

<div{{if blog:related}} id="content"{{ endif }}>
	<div class="entry">
		<div class="title">
			<h1>{{ blog:subject }}</h1>
			{{ if blog:friend }}
			<span class="r_option locked gray">
			<a href="blog?uid={{ space:uid }}&view=me&friend={{ blog:friend }}" class="gray">
			{{ if blog:friend == 1 }}仅好友可见
			{{ elseif blog:friend == 2 }}指定好友可见
			{{ elseif blog:friend == 3 }}仅自己可见
			{{ elseif blog:friend == 4 }}凭密码可见
			{{ endif }}
			</a>
			</span>
			{{ endif }}
			{{ if blog:tag }}
				<a href="tag?uid={{ space:uid }}">标签</a>:&nbsp;
				{{ blog:tag }}
				<a href="tag?uid={{ space:uid }}&id=$tagid">$tagname</a>&nbsp;
				{{ /blog:tag }}
			{{ endif }}
			<span class="time">{{ helper:sgmdate dateformat="Y-m-d H:i" time=blog:dateline format="1" }}</span>
		</div>

		<div id="blog_article" class="article">
			{{ if ad:rightside }}
			<div style="float: right; padding:5px;"><!--{ad/rightside}--></div>
			{{ endif }}
			<div class="resizeimg">{{ blog:message }}</div>
		</div>
		
		<div class="status">
			<a href="cp.php?ac=share&type=blog&id=$blog[blogid]" id="a_share" onclick="ajaxmenu(event, this.id, 99999,'' , -1)" class="a_share">分享</a>
			<div class="r_option">
				{{ if member:uid == blog:uid || usergroup:manageblog }}
				<a href="cp.php?ac=blog&blogid=$blog[blogid]&op=edit">编辑</a><span class="pipe">|</span>
				<a href="cp.php?ac=blog&blogid=$blog[blogid]&op=delete" id="blog_delete_$blog[blogid]" onclick="ajaxmenu(event, this.id, 99999)">删除</a><span class="pipe">|</span>
				{{ endif }}
				<a href="cp.php?ac=common&op=report&idtype=blog&id=$blog[blogid]" id="a_report" onclick="ajaxmenu(event, this.id, 99999,'' , -1)">举报</a><span class="pipe">|</span>
			</div>

			{{if blog:viewnum }}{{ blog:viewnum }} 次阅读 | {{ endif }}
			<span id="comment_replynum">{{ blog:replynum }}</span> 个评论
		</div>
	</div>

	{{ if blog:trace || ( not blog:trace && not space:self) }}
	<div class="trace" style="padding-bottom: 10px;">
		<div class="title">
			{{ if blog:tracenum }}--><a href="javascript:;" onclick="show_trace_all();" class="r_option">全部($blog[tracenum])</a>{{ endif }}
			<h2>留下脚印</h2>
		</div>
		<div id="trace_div">
		<ul class="avatar_list" id="trace_ul">
		{{ if blog:trace }}
			{{ blog:trace }}
			<li>
				<div class="avatar48"><a href="space.php?uid=$value[uid]" title="{$_SN[$value[uid]]}" target="_blank"><img src="<!--{avatar($value[uid],small)}-->" alt="{$_SN[$value[uid]]}" class="avatar" /></a></div>
				<p><a href="space.php?uid=$value[uid]" title="{$_SN[$value[uid]]}" target="_blank">{$_SN[$value[uid]]}</a></p>
			</li>
			{{ /blog:trace }}
			{{ if not space:self }}
			<li style="padding-top: 16px;"><a href="cp.php?ac=blog&blogid=$blog[blogid]&op=trace" id="trace_$blog[blogid]" onclick="ajaxmenu(event, this.id, 2000, 'show_trace')" class="tracebutton">踩一脚</a></li>
			{{ endif }}
		{{ else }}
			<li><a href="cp.php?ac=blog&blogid=$blog[blogid]&op=trace" id="trace_$blog[blogid]" onclick="ajaxmenu(event, this.id, 2000, 'show_trace')" class="tracebutton">踩一脚</a></li>
			<li style="padding-left: 8px; width: auto; line-height: 28px;" class="gray">您的头像会显示在这里</li>
		{{ endif }}
		</ul>
		</div>
	</div>
	{{ endif }}
	<div class="comments" id="div_main_content">
	
		<h2>评论</h2>

		<div class="comments_list" id="comment">
			{{ if get:cid }}
			<div class="notice">
				当前只显示与你操作相关的单个评论，<a href="blog?uid={{ blog:uid }}&id={{ blog:blogid }}">点击此处查看全部评论</a>
			</div>
			{{ endif }}
			<ul id="comment_ul">
			{{ blog:comment}}
				{{ theme:partial name="space_comment_li"}}
			{{ /blog:comment}}
			</ul>
		</div>
		<div class="page">{{helper:page total_rows=blog:replynum per_page=30 per_page=get:page base_url=server:REQUEST_URI}}</div>
		
		{{ if not blog:noreply}}
		<form id="quickcommentform_{$id}" name="quickcommentform_{$id}" action="cp.php?ac=comment" method="post" class="quickpost">
			
			<table cellpadding="0" cellspacing="0">
				<tr>
					<td>
						<a href="###" id="face" title="插入表情" onclick="showFace(this.id, 'comment_message');"><img src="image/facelist.gif" align="absmiddle" /></a><br>
						<textarea id="comment_message" onkeydown="ctrlEnter(event, 'commentsubmit_btn');" name="message" rows="5" style="width:500px;"></textarea>
					</td>
				</tr>
				<tr>
					<td>
						<input type="hidden" name="refer" value="blog?uid={{ blog:uid }}&id=$id" />
						<input type="hidden" name="id" value="$id">
						<input type="hidden" name="idtype" value="blogid">
						<input type="hidden" name="commentsubmit" value="true" />
						<input type="button" id="commentsubmit_btn" name="commentsubmit_btn" class="submit" value="评论" onclick="ajaxpost('quickcommentform_{$id}', 'comment_status', 'comment_add')" />
						<span id="comment_status"></span>
					</td>
				</tr>
			</table>
		<input type="hidden" name="formhash" value="{{ global:formhash }}" /></form>
		<br />
		{{ endif }}
	</div>
	
</div>

{{ if blog:related }}
<div id="sidebar">
		{{ loop blog:related }}
		<div class="sidebox">
		<h2 class="title">相关阅读</h2>
			<ul class="news_list">
				{{ values:data }}
				<li style="height:auto;">{{ value:html }}</li>
				{{ /values:data }}
			</ul>
		</div>
		{{ /loop blog:related }}	
</div>
{{ endif }}

<script type="text/javascript">
<!--
function closeSide2(oo) {
	if($('sidebar').style.display == 'none'){
		$('content').style.cssText = '';
		$('sidebar').style.display = 'block';
		oo.innerHTML = '&raquo; 关闭侧边栏';
	}
	else{
		$('content').style.cssText = 'margin: 0pt; width: 810px;';
		$('sidebar').style.display = 'none';		
		oo.innerHTML = '&laquo; 打开侧边栏';
	}
}
function show_trace(id) {
	ajaxget('do.php?ac=ajax&op=trace&blogid='+$id, 'trace_ul');
	return false;
}
function show_trace_all() {
	ajaxget('do.php?ac=ajax&op=traceall&blogid='+$id+'&ajaxdiv=trace_div', 'trace_div');
	return false;
}
resizeImg('blog_article','500');
resizeImg('div_main_content','450');
-->
</script>