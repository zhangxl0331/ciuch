{{ if uch:space:self }}
<h2 class="title"><img src="{{ url:base }}image/app/doing.gif">记录</h2>
<div class="tabs_header">
	<ul class="tabs">
		{{ if uch:space:friendnum }}<li{{ if input:view != 'me' AND input:view != 'all' }} class="active"{{ endif }}><a href="{{ url:base }}doing?uid={{ uch:space:uid }}"><span>最新好友记录</span></a></li>{{ endif }}
		<li{{ if input:view == 'me' }} class="active"{{ endif }}><a href="{{ url:base }}doing?uid={{ uch:space:uid }}&view=me"><span>我的记录</span></a></li>
		<li{{ if input:view == 'all' }} class="active"{{ endif }}><a href="{{ url:base }}doing?uid={{ uch:space:uid }}&view=all"><span>大家的记录</span></a></li>
		<li{{ if router:module == 'mood' }} class="active"{{ endif }}><a href="{{ url:base }}mood?uid={{ uch:space:uid }}"><span>同心情的朋友</span></a></li>
	</ul>
</div>
{{ else }}
	{{ theme:partial name="avatar" module="space" }}
	{{ theme:partial name="tabs" module="space" }}
{{ endif }}