{{ if uch:mood:list }}
<div class="sidebox">
	<h2 class="title">
		<p class="r_option"><a href="{{ url:base }}mood?uid={{ uch:space:uid }}">全部</a></p>
		跟{{ if uch:space:self }}我{{ else }}{{ uch:space:username }}{{ endif }}同心情的朋友
	</h2>
	<ul class="avatar_list">
		{{ uch:mood:list }}
		<li>
			<div class="avatar48"><a href="{{ url:base }}doing?uid={{ uid }}"><img src="<!--{avatar($value[uid],small)}-->" alt="{{ username }}" /></a></div>
			<p><a href="{{ url:base }}space?uid={{ uid }}" title="{{ username }}">{{ username }}</a></p>
			<p class="time">{{ helper:sgmdate dateformat="n-j", timestamp=updatetime format="1" timeoffset=uch:config:timeoffset }}</p>
		</li>
		{{ /uch:mood:list }}
	</ul>
</div>
{{ endif }}