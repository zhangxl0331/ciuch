{{ if space:self }}
<div class="searchbar">
<form method="get" action="network.php">
	<input name="key" value="" size="26" class="t_input" type="text">
	<input name="searchsubmit" value="搜索日志" class="submit" type="submit">
	<input type="hidden" name="searchmode" value="1" />
	{{ if input:view=='me' }}
	<input type="hidden" name="username" value="{{ space:username }}" />
	{{ endif }}
	<input type="hidden" name="ac" value="blog" />
</form>
</div>
<h2 class="title"><img src="image/app/blog.gif" />日志</h2>
<div class="tabs_header">
	<ul class="tabs">
		{{ if space:friendnum }}<li{{ if not input:view }} class="active"{{ endif }}><a href="blog?uid=$space[uid]"><span>好友最新日志</span></a></li>{{ endif }}
		<li{{ if input:view=='me' }} class="active"{{ endif }}><a href="blog?uid=$space[uid]&view=me"><span>我的日志</span></a></li>
		<li{{ if input:view=='trace' }} class="active"{{ endif }}><a href="blog?uid=$space[uid]&view=trace"><span>我踩过的日志</span></a></li>
		<li{{ if input:view=='all' }} class="active"{{ endif }}><a href="blog?uid=$space[uid]&view=all"><span>大家的日志</span></a></li>
		<li class="null"><a href="cp.php?ac=blog">发表新日志</a></li>
	</ul>
</div>
{{ else }}
{{ theme:partial name="space_menu"}}
{{ endif}}

<div id="content" style="width:640px;">
	{{ blog:lists noloop="true"}}
	{{ if total_rows }}
	<div class="entry_list">
		<ul>		
			{{ list }}
			<li>	
				<div class="avatar48"><a href="space.php?uid={{ space:uid }}"><img src="<!--{avatar($value[uid],small)}-->" alt="{$_SN[$value[uid]]}" class="avatar" /></a></div>
				<div class="title">
					<a href="cp.php?ac=share&type=blog&id={{ blogid }}" id="a_share_{{ blogid }}" onclick="ajaxmenu(event, this.id, 99999,'' , -1)" class="a_share">分享</a>
					<h4><a href="blog?uid={{ space:uid }}&id={{ blogid }}">{{ subject }}</a></h4>
					<div>
				{{ if friend }}
					<span class="r_option locked gray">
					<a href="$theurl&friend={{ friend }}" class="gray">
					{{ if friend == 1 }}仅好友可见
					{{ elseif friend == 2 }}指定好友可见
					{{ elseif friend == 3 }}仅自己可见
					{{ elseif friend == 4 }}凭密码可见{{ endif }}
					</a>
					</span>
				{{ endif }}
					<a href="space.php?uid={{ space:uid }}">{$_SN[$value[uid]]}</a> <span class="time">{{ dateline }}</span>
					</div>
				</div>
				<div class="detail image_right l_text s_clear" id="blog_article_{{ blogid }}">
					{{ if pic }}<p class="image"><a href="space.php?uid=$value[uid]&do=blog&id=$value[blogid]"><img src="$value[pic]" alt="$value[subject]" /></a></p>{{ endif }}
					{{ message1 }}
				</div>
				<div class="status">
					{{ if classarr:classid }}分类: <a href="space.php?uid=$value[uid]&do=blog&classid=$value[classid]">{$classarr[$value[classid]]}</a><span class="pipe">|</span>{{ endif }}
					{{ if viewnum }}<a href="space.php?uid=$value[uid]&do=$do&id=$value[blogid]">{{ viewnum}} 次阅读</a><span class="pipe">|</span>{{ endif }}
					{{ if replynum }}<a href="space.php?uid=$value[uid]&do=$do&id=$value[blogid]#comment">{{ replynum }} 个评论</a>{{ else }}没有评论{{ endif }}
				</div>
			</li>
			{{ /list }}			
			{{ if pricount }}
			<li>
				<div class="title">本页有 {{ pricount }} 篇日志因作者的隐私设置而隐藏</div>
			</li>
			{{ endif }}
		
		</ul>
	</div>
	
	<div class="page">{{ helper:page base_url=base_url per_page="1" num_links="5" total_rows=total_rows cur_page=input:page }}</div>
	
	{{ else }}
	<div class="c_form">还没有相关的日志。</div>
	{{ endif }}
	{{ /blog:lists }}

</div>

<div id="sidebar" style="width:150px;">
	
	{{if input:view == 'me' }}
	{{ if blog:classes }}
	<div class="cat">
	<h3>日志分类</h3>
	<ul class="post_list line_list">
		<li{{ if not input:classid }}> class="current"{{ endif }}><a href="space.php?uid=$space[uid]&do=blog&view=me">全部日志</a></li>
		{{ if blog:classes }}
		<li{{ if input:classid==classid}} class="current"{{ endif }}>
			<a href="space.php?uid=$space[uid]&do=blog&classid=$classid">$classname</a>
			{{ if space:self }}
				<a href="cp.php?ac=class&op=edit&classid=$classid" id="c_edit_$classid" onclick="ajaxmenu(event, this.id, 99999)" class="c_edit">编辑</a>
				<a href="cp.php?ac=class&op=delete&classid=$classid" id="c_delete_$classid" onclick="ajaxmenu(event, this.id, 99999)" class="c_delete">删除</a>
			{{ endif }}
		</li>
		{{ /blog:classes }}
	</ul>
	</div>
	{{ endif }}
	{{ endif }}
	{{ if userlist }}
	<div class="cat">
	<h3>按作者查看</h3>
	<ul class="post_list line_list">
		{{ userlist }}
		<li><a href="space.php?uid=$uid&do=blog&view=me">$_SN[$uid]</a></li>
		{{ /userlist }}
	</ul>
	</div>
	{{ endif }}
	
</div>
