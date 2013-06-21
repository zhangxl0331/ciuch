{{ theme:partial name="space_menu" }}
<div id="content" style="width:640px;">
	{{ blog:lists noloop="true"}}
	{{ if total_rows }}
	<div class="entry_list">
		<ul>		
			{{ list }}
			<li>	
				<div class="avatar48"><a href="{{ url:base }}space/uid-{{ space:uid }}.html"><img src="{{ member:avatar uid="{{ uid }}" size="small" }}" alt="{{ space:realname uid="{{ uid }}" }}" class="avatar" /></a></div>
				<div class="title">
					<a href="cp.php?ac=share&type=blog&id={{ blogid }}" id="a_share_{{ blogid }}" onclick="ajaxmenu(event, this.id, 99999,'' , -1)" class="a_share">分享</a>
					<h4><a href="{{ url:base }}blog/uid-{{ space:uid }}-id-{{ blogid }}.html">{{ subject }}</a></h4>
					<div>
				{{ if friend }}
					<span class="r_option locked gray">
					<a href="{{ url:base }}blog/{{ router:rewrite key="friend" value="{{ friend }}" }}.html" class="gray">
					{{ if friend == 1 }}仅好友可见
					{{ elseif friend == 2 }}指定好友可见
					{{ elseif friend == 3 }}仅自己可见
					{{ elseif friend == 4 }}凭密码可见{{ endif }}
					</a>
					</span>
				{{ endif }}
					<a href="{{ url:base }}space/uid-{{ space:uid }}.html">{{ space:realname uid="{{ uid }}" }}</a> <span class="time">{{ dateline }}</span>
					</div>
				</div>
				<div class="detail image_right l_text s_clear" id="blog_article_{{ blogid }}">
					{{ if pic }}<p class="image"><a href="{{ url:base }}blog/uid-{{ uid }}-id-{{ blogid }}.html"><img src="{{ pic }}" alt="{{ subject }}" /></a></p>{{ endif }}
					{{ message1 }}
				</div>
				<div class="status">
					{{ if classid }}
					分类: <a href="{{ url:base }}blog/uid-{{ uid }}-classid-{{ classid }}.html">{{ blog:classname classid="{{ classid }}" }}</a><span class="pipe">|</span>
					{{ endif }}
					{{ if viewnum }}
					<a href="{{ url:base }}blog/uid-{{ uid }}-id-{{ blogid }}.html">{{ viewnum}} 次阅读</a>
					{{ endif }}
					{{ if replynum }}
					<span class="pipe">|</span><a href="{{ url:base }}blog/uid-{{ uid }}-id-{{ blogid }}.html#comment">{{ replynum }} 个评论</a>
					{{ endif }}
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
	{{ blog:lists noloop="true"}}
	{{ if classarr AND input:view == 'me' }}
	<div class="cat">
	<h3>日志分类</h3>
	<ul class="post_list line_list">
		<li{{if input:classid }} class="current"{{ endif }}><a href="{{ url:base }}blog/uid-{{ uid }}-view-me.html">全部日志</a></li>
		{{ classarr }}
		<li{{ if input:view==classid }} class="current"{{ endif }}>
			<a href="{{ url:base }}blog/uid-{{ uid }}-classid-{{ classid }}.html">{{ classname }}</a>
			{{ if space:self }}
				<a href="cp.php?ac=class&op=edit&classid=$classid" id="c_edit_$classid" onclick="ajaxmenu(event, this.id, 99999)" class="c_edit">编辑</a>
				<a href="cp.php?ac=class&op=delete&classid=$classid" id="c_delete_$classid" onclick="ajaxmenu(event, this.id, 99999)" class="c_delete">删除</a>
			{{ endif }}
		</li>
		{{ /classarr }}		
	</ul>
	</div>
	{{ elseif userarr AND input:view != 'me'}}
	<div class="cat">
	<h3>按作者查看</h3>
	<ul class="post_list line_list">
		{{ userarr }}
		<li><a href="{{ url:base }}blog/uid-{{ uid }}-view-me.html">{{ username }}</a></li>
		{{ /userarr }}
	</ul>
	</div>
	{{ endif }}
	{{ /blog:lists }}
</div>