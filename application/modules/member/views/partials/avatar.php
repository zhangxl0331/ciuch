<div class="c_header a_header">
	<div class="avatar48"><a href="space.php?uid=$space[uid]"><img src="<!--{avatar($space[uid],small)}-->" alt="{{ uch:space:username }}" /></a></div>
	<p style="font-size:14px">{{ uch:space:username }}的{{ lang:{{ router:module }} }}</p>
	<a href="space.php?uid=$space[uid]" class="spacelink">{{ uch:space:username }}的主页</a>
	{{ if uch:tpl:spacemenus }}
		{{ uch:tpl:spacemenus }}<span class="pipe">|</span> $value{{ uch:tpl:spacemenus }}
	{{ else }}
		<span class="pipe">|</span> <a href="cp.php?ac=poke&op=send&uid=$space[uid]" id="a_poke" onclick="ajaxmenu(event, this.id, 99999, '', -1)">打个招呼</a>
	{{ endif }}
</div>