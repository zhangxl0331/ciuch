{{ if doing:ckprivacy }}
{{ if doing:getcount }}
<h2>
<a href="{{ url:base }}doing?uid={{ uch:space:uid }}&view=me" class="r_option">全部</a>
记录
</h2>
<div id="space_doing" class="box">
	<ul class="post_list line_list" id="doing_ul">
	{{ doing:getlist }}
		<li>{{ message }} (<a href="{{ url:base }}doing?uid={{ uid }}&doid={{ doid }}&goto=yes">{{ if replynum }}{{ replynum }}个{{ endif }}回复</a>)</li>
	{{ /doing:getlist }}
	</ul>
</div>
{{ elseif uch:space:self }}
<h2>记录</h2>
<div class="box">
	<a href="{{ url:base }}doing?uid={{ uch:space:uid }}&view=me"><img src="{{ url:base }}image/intro_doing.gif" class="noimage"></a>
	你还没有记录。<br><a href="{{ url:base }}doing?uid={{ uch:space:uid }}&view=me">点击这里用一句记录自己生活的点点滴滴</a>
</div>	
{{ endif }}
{{ endif }}