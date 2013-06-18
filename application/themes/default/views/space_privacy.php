{{ theme:partial name="header" }}

<div class="c_form">

<h2 class="l_status title">由于 {{ space:realname }} 的隐私设置，你不能访问当前内容</h2>

<div class="thumb_list">
	<table cellpadding="0" cellspacing="0">
		<tr>
			<td class="image">
				<div class="ar_r_t"><div class="ar_l_t"><div class="ar_r_b"><div class="ar_l_b">
				<a href="space.php?uid=$space[uid]"><img src="<!--{avatar($space[uid],big)}-->" alt="{{ space:realname }}" /></a>
				</div></div></div></div>
			</td>
			<td>
				<h6><a href="space.php?uid=$space[uid]">{{ space:realname }}</a></h6>
				<p class="l_status">
					<a href="space.php?uid=$space[uid]&do=friend">查看好友列表</a>
					{{ if space:isfriend }}
					<span class="pipe">|</span><a href="cp.php?ac=friend&op=ignore&uid=$space[uid]" id="a_ignore" onclick="ajaxmenu(event, this.id, 99999)">解除好友关系</a>
					{{ else }}
					<span class="pipe">|</span><a href="cp.php?ac=friend&op=add&uid=$space[uid]" id="a_friend" onclick="ajaxmenu(event, this.id, 99999, '', -1)">加为好友</a>
					{{ endif }}
					<span class="pipe">|</span><a href="cp.php?ac=poke&op=send&uid=$space[uid]" id="a_poke" onclick="ajaxmenu(event, this.id, 99999, '', -1)">打招呼</a>
					<span class="pipe">|</span><a href="cp.php?ac=pm&uid=$space[uid]" id="a_pm" onclick="ajaxmenu(event, this.id, 99999, '', -1)">发短消息</a>
				</p>
				{{ if space:note }}
				<p>{{ space:note }}</p>
				{{ endif }}
				{{ if space:resideprovince OR space:residecity }}
				<p><span class="gray">地区：</span>{{ space:resideprovince }} {{ space:residecity }}</p>
				{{ endif }}
				
				{{ if space:isfriend }}
				<p style="padding:20px 0 0 0;font-weight:bold;">{{ space:realname }} 有 {{ space:friendnum }} 名好友, {{ space:credit }} 个积分, {{ space:viewnum }} 个浏览量</p>
				<p>与{$_SN[$space[uid]]}成为好友后，您可以第一时间关注到{$_SN[$space[uid]]}的更新信息。</p>
				<p style="padding:10px 0 0 0;"><a href="cp.php?ac=friend&op=add&uid=$space[uid]" id="a_friend" onclick="ajaxmenu(event, this.id, 99999, '', -1)" class="submit">加为好友</a></p>
				{{ endif }}
			</td>
		</tr>
	</table>
</div>

</div>
{{ theme:partial name="footer" }}