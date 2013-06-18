{{ if space:self }}
<div class="searchbar">
<form method="get" action="network.php">
	<input name="key" value="" size="26" class="t_input" type="text">
	<input name="searchsubmit" value="{{ helper:lang line="{{ router:module }}:searchvalue"}}" class="submit" type="submit">
	<input type="hidden" name="searchmode" value="1" />
	{{ if input:view=='me' }}
	<input type="hidden" name="username" value="{{ space:username }}" />
	{{ endif }}
	<input type="hidden" name="ac" value="{{ router:module }}" />
</form>
</div>
<h2 class="title"><img src="image/app/{{ router:module }}.gif" />{{ helper:lang line="blog"}}</h2>
<div class="tabs_header">
	<ul class="tabs">
		{{ if space:friendnum }}
		<li{{ if not input:view }} class="active"{{ endif }}><a href="{{ url:base }}{{ router:module }}/{{ space:uid }}"><span>{{ helper:lang line="{{ router:module }}:view"}}</span></a></li>
		{{ endif }}
		<li{{ if input:view=='me' }} class="active"{{ endif }}><a href="{{ url:base }}{{ router:module }}/uid-{{ space:uid }}-view-me.html"><span>{{ helper:lang line="{{ router:module }}:view_me"}}</span></a></li>
		<li{{ if input:view=='all' }} class="active"{{ endif }}><a href="{{ url:base }}{{ router:module }}/uid-{{ space:uid }}-view-all.html"><span>{{ helper:lang line="{{ router:module }}:view_all"}}</span></a></li>
		<li class="null"><a href="cp.php?ac={{ router:module }}">{{ helper:lang line="{{ router:module }}:cp"}}</a></li>
	</ul>
</div>
{{ else }}
<div class="c_header a_header">
	<div class="avatar48"><a href="{{ url:base }}space/uid-{{ space:uid }}.html"><img src="<!--{avatar($space[uid],small)}-->" alt="{{ space:realname }}" /></a></div>
	<p style="font-size:14px">{{ space:realname }}{{ helper:lang line="{{ router:module }}:whose" }}</p>
	<a href="{{ url:base }}space/uid-{{ space:uid }}.html" class="spacelink">{{ space:realname }}{{ helper:lang line="global:whosehomepage" }}</a>
	<span class="pipe">|</span> <a href="cp.php?ac=poke&op=send&uid=$space[uid]" id="a_poke" onclick="ajaxmenu(event, this.id, 99999, '', -1)">{{ helper:lang line="global:poke" }}</a>
</div>
<div class="tabs_header">
	<ul class="tabs">
		{{ if privacy:index }}
		<li{{ if not router:module }} class="active"{{ endif }}><a href="{{ url:base }}space/uid-{{ space:uid }}.html"><span>{{ helper:lang line="global:homepage" }}</span></a></li>
		{{ endif }}
		{{ if privacy:doing }}
		<li{{ if router:module=='doing' }} class="active"{{ endif }}><a href="{{ url:base }}doing/{{ space:uid }}/view/me"><span>记录</span></a></li>
		{{ endif }}
		{{ if privacy:blog }}
		<li{{ if router:module=='blog' }} class="active"{{ endif }}><a href="{{ url:base }}blog/uid-{{ space:uid }}-view-me.html"><span>{{ helper:lang line="blog" }}</span></a></li>
		{{ endif }}
		{{ if privacy:album }}
		<li{{ if router:module=='album' }} class="active"{{ endif }}><a href="{{ url:base }}album/{{ space:uid }}/view/me"><span>相册</span></a></li>
		{{ endif }}
		{{ if privacy:share }}
		<li{{ if router:module=='share' }} class="active"{{ endif }}><a href="{{ url:base }}share/{{ space:uid }}/view/me"><span>分享</span></a></li>
		{{ endif }}
		{{ if privacy:mtag }}
		<li{{ if router:module=='mtag' || get:do=='thread' }} class="active"{{ endif }}><a href="{{ url:base }}thread/{{ space:uid }}/view/me"><span>话题</span></a></li>
		{{ endif }}
		{{ if privacy:wall }}
		<li{{ if router:module=='wall' }} class="active"{{ endif }}><a href="{{ url:base }}wall/{{ space:uid }}/view/me"><span>留言</span></a></li>
		{{ endif }}
		{{ if privacy:friend }}
		<li{{ if router:module=='friend' }} class="active"{{ endif }}><a href="{{ url:base }}friend/{{ space:uid }}/view/me"><span>好友</span></a></li>
		{{ endif }}
	</ul>
</div>
{{ endif }}