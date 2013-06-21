




	<div id="space_page">

		<div id="ubar">
			<div id="space_avatar">
				<img src="" alt="{{ uch:space:realname }}" />
			</div>
			
			{{ if not uch:space:isfriend }}
			<div class="borderbox">
				如果您认识{{ uch:space:realname }}，可以添加为好友。这样，您就可以第一时间关注到好友的更新动态。<br />
				<img src="image/icon/friend.gif" align="absmiddle"> <a href="cp.php?ac=friend&op=add&uid=$space[uid]" id="a_friend" onclick="ajaxmenu(event, this.id, 99999, '', -1)"><strong>把{{ uch:space:realname }}加为好友</strong></a></p>
			</div>
			{{ endif }}
			
			<ul class="line_list">
		{{ if uch:space:self }}
			<li><img src="{{ url:base }}image/icon/profile.gif" align="absmiddle"><a href="cp.php?ac=profile">编辑资料</a></li>
		{{ else }}
			<li><img src="{{ url:base }}image/icon/wall.gif"><a href="#comment">给我留言</a></li>
			<li><img src="{{ url:base }}image/icon/poke.gif"><a href="cp.php?ac=poke&op=send&uid=$space[uid]" id="a_poke" onclick="ajaxmenu(event, this.id, 99999, '', -1)">打个招呼</a></li>
			<li><img src="{{ url:base }}image/icon/pm.gif"><a href="cp.php?ac=pm&uid=$space[uid]" id="a_pm" onclick="ajaxmenu(event, this.id, 99999, '', -1)">发送消息</a></li>
			{{ if uch:space:isfriend }}
			<li><img src="{{ url:base }}image/icon/friend.gif"><a href="cp.php?ac=friend&op=ignore&uid=$space[uid]" id="a_ignore" onclick="ajaxmenu(event, this.id, 99999)">解除好友</a></li>
			{{ endif }}
			<li><img src="{{ url:base }}image/icon/report.gif"><a href="cp.php?ac=common&op=report&idtype=space&id=$space[uid]" id="a_report" onclick="ajaxmenu(event, this.id, 99999,'' , -1)">违规举报</a></li>

		{{ endif }}

		
		
			</ul>

			{{ noparse }}{{ theme:partial name="icons" module="space" }}{{ /noparse }}
			
			{{ theme:partial name="space_list" module="doing" }}			
			
		</div>
		
		<div id="content">
			{{ theme:partial name="tabs" module="member" }}

		
			<div class="composer">

				<strong class="index_name"{{g_color($space[groupid]);}}>{{ if sconfig:realname && uch:space:name && uch:space:namestatus }}{{stripslashes($space['name']);}} <em>(用户名:{{echo stripslashes($space['username']);}})</em>{{ else }}{{echo stripslashes($space['username']);}}{{ if uch:space:name }} <em>(姓名:{{echo stripslashes($space['name']);}})</em>{{ endif }}{{ endif }}{{g_icon($space[groupid]);}}</strong>
				{{ if uch:space:isonline }}<span class="time">在线({{ uch:space:isonline }})</span>{{ endif }}
				<p class="gray">
					<a href="{{ uch:space:domainurl }}" onclick="javascript:setCopy('{{ uch:space:domainurl }}');return false;" class="spacelink domainurl">{{ uch:space:domainurl }}</a>
				</p>
				<p>
				
					{{ if uch:space:self }}
					已有 <a href="space.php?uid=$space[uid]&do=friend&view=visitor">{{ uch:space:viewnum }}</a> 人次访问, <a href="cp.php?ac=credit">{{ uch:space:credit }}</a>个积分 <a href="cp.php?ac=credit">{{ uch:space:creditstar }}</a>
					{{ else }}
					已有 {{ uch:space:viewnum }} 人次访问, {{ uch:space:credit }}个积分 <a href="do.php?ac=ajax&op=credit&uid=$space[uid]" id="a_space_view" onclick="ajaxmenu(event, this.id, 99999)">{{ uch:space:creditstar }}</a>
					{{ endif }}
				</p>

				<div class="current_status" id="mystate">
					{{ if uch:space:mood }}<a href="space.php?uid=$space[uid]&do=mood" title="同心情"><img src="image/face/{$space[mood]}.gif" alt="同心情" class="face" /></a> {{ endif }}
					{{ if uch:space:spacenote }}{{ uch:space:spacenote }}{{ endif }}
					{{ if uch:space:self }}
					{{ if not uch:space:mood && uch:space:spacenote }}
						您在做什么？
					{{ endif }}
					&nbsp;(<a href="javascript:;" onclick="mood_from();" title="更新状态">更新状态</a><span class="pipe">|</span><a href="space.php?uid=$space[uid]&do=mood">同心情</a>)
					{{ endif }}
				</div>
				
				{{ if uch:space:showprofile }}
				<ul class="profile">
					{{ if uch:space:sex }}
						<li>性别：{{ uch:space:sex }}</li>
					{{ endif }}
					{{ if uch:space:birthday }}
						<li>生日：{{ uch:space:birthday }}</li>
					{{ endif }}
					{{ if uch:space:blood }}
						<li>血型：{{ uch:space:blood }}</li>
					{{ endif }}
					{{ if uch:space:marry }}
						<li>婚恋：{{ uch:space:marry }}</li>
					{{ endif }}
					{{ if uch:space:reside }}
					<li>居住：{{ uch:space:reside }}</li>
					{{ endif }}
					{{ if uch:space:birth }}
					<li>家乡：{{ uch:space:birth }}</li>
					{{ endif }}
					{{ if uch:space:qq }}
					<li>QQ：{{ uch:space:qq }}</li>
					{{ endif }}
					{{ if uch:space:msn }}
					<li>MSN：{{ uch:space:msn }}</li>
					{{ endif }}
				
					
				</ul>
				{{ endif }}			
				
			</div>

	
		
			
			
		
		
	
		</div>

		<div id="obar">
		
		
		
		</div>
	</div>


<div id="mood_form" style="display:none">
	<form method="post" action="cp.php?ac=doing" id="moodform">
		<table cellpadding="0" cellspacing="0">
			<tr>
				<td width="40"><a href="###" id="face" onclick="showFace(this.id, 'message');"><img src="image/facelist.gif" align="absmiddle" /></a></td>
				<td>
					<input type="text" name="message" id="message" value="" size="30" class="t_input" />
					<input type="hidden" name="addsubmit" value="true" />
					<input type="hidden" name="spacenote" value="true" />
				</td>
				<td>&nbsp;<input type="button" id="add" name="add" value="更新" class="submit" onclick="ajaxpost('moodform', 'mystate', 'reloadMood');" /></td>
				<td>&nbsp;<a href="javascript:;" onclick="mood_form_cancel();">取消</a></td>
			</tr>
		</table>
		<input type="hidden" name="formhash" value="" />
	</form>
</div>

<script type="text/javascript">
	var old_html = '';
	function reloadMood(showid, result) {
		var x = new Ajax();
		x.get('do.php?ac=ajax&op=getmood', function(s){
			$('mystate').innerHTML = s;
		});
	}
	function mood_from() {
		old_html = $('mystate').innerHTML;
		$('mystate').innerHTML = $('mood_form').innerHTML;
	}
	function mood_form_cancel() {
		$('mystate').innerHTML = old_html;
	}
</script>
{{ if input:theme}}<div class="nn">您是否想使用这款个性风格?<br /><a href="cp.php?ac=theme&op=use&dir=<!--{$_GET['theme']}-->">[应用]</a><a href="cp.php?ac=theme">[取消]</a></div>{{ endif }}		