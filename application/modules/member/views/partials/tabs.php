<div class="tabs_header">
	{{ if router:module == 'space' }}
	<a href="cp.php?ac=share&type=space&id=$space[uid]" class="a_share" id="a_share" onclick="ajaxmenu(event, this.id, 99999, '', -1)">分享</a>
	<a href="rss.php?uid=$space[uid]" id="i_rss" title="订阅 RSS">订阅</a>
	{{ endif }}
	<ul class="tabs">
		{{ member:tabs uid=user:uid }}
	</ul>
</div>