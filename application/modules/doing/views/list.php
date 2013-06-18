{{ theme:partial name="space_menu"}}

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
	<div class="cat">
	<h3>日志分类</h3>
	<ul class="post_list line_list">
		<li><a href="space.php?uid=$space[uid]&do=blog&view=me">全部日志</a></li>
	</ul>
	</div>
</div>
