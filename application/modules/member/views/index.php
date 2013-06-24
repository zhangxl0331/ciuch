	<div id="space_page">

		<div id="ubar">
			<div id="space_avatar">
				<img src="" alt="{{ user:realname }}" />
			</div>
			
			{{ if not user:isfriend }}
			<div class="borderbox">
				如果您认识{{ user:realname }}，可以添加为好友。这样，您就可以第一时间关注到好友的更新动态。<br />
				<img src="image/icon/friend.gif" align="absmiddle"> <a href="cp.php?ac=friend&op=add&uid=$space[uid]" id="a_friend" onclick="ajaxmenu(event, this.id, 99999, '', -1)"><strong>把{{ user:realname }}加为好友</strong></a></p>
			</div>
			{{ endif }}
			
			<ul class="line_list">
		{{ if user:self }}
			<li><img src="{{ url:base }}image/icon/profile.gif" align="absmiddle"><a href="cp.php?ac=profile">编辑资料</a></li>
		{{ else }}
			<li><img src="{{ url:base }}image/icon/wall.gif"><a href="#comment">给我留言</a></li>
			<li><img src="{{ url:base }}image/icon/poke.gif"><a href="cp.php?ac=poke&op=send&uid=$space[uid]" id="a_poke" onclick="ajaxmenu(event, this.id, 99999, '', -1)">打个招呼</a></li>
			<li><img src="{{ url:base }}image/icon/pm.gif"><a href="cp.php?ac=pm&uid=$space[uid]" id="a_pm" onclick="ajaxmenu(event, this.id, 99999, '', -1)">发送消息</a></li>
			{{ if user:isfriend }}
			<li><img src="{{ url:base }}image/icon/friend.gif"><a href="cp.php?ac=friend&op=ignore&uid=$space[uid]" id="a_ignore" onclick="ajaxmenu(event, this.id, 99999)">解除好友</a></li>
			{{ endif }}
			<li><img src="{{ url:base }}image/icon/report.gif"><a href="cp.php?ac=common&op=report&idtype=space&id=$space[uid]" id="a_report" onclick="ajaxmenu(event, this.id, 99999,'' , -1)">违规举报</a></li>

		{{ endif }}

		
		
			</ul>		
			
		</div>
		
		<div id="content">
			{{ theme:partial name="tabs" module="member" }}

		
			<div class="composer">

				<strong class="index_name"{{g_color($space[groupid]);}}>{{ if sconfig:realname && user:name && user:namestatus }}{{stripslashes($space['name']);}} <em>(用户名:{{echo stripslashes($space['username']);}})</em>{{ else }}{{echo stripslashes($space['username']);}}{{ if user:name }} <em>(姓名:{{echo stripslashes($space['name']);}})</em>{{ endif }}{{ endif }}{{g_icon($space[groupid]);}}</strong>
				{{ if user:isonline }}<span class="time">在线({{ user:isonline }})</span>{{ endif }}
				<p class="gray">
					<a href="{{ user:domainurl }}" onclick="javascript:setCopy('{{ user:domainurl }}');return false;" class="spacelink domainurl">{{ user:domainurl }}</a>
				</p>
				<p>
				
					{{ if auth && auth:uid == user:uid }}
					已有 <a href="space.php?uid=$space[uid]&do=friend&view=visitor">{{ user:viewnum }}</a> 人次访问, <a href="cp.php?ac=credit">{{ user:credit }}</a>个积分 <a href="cp.php?ac=credit">{{ user:creditstar }}</a>
					{{ else }}
					已有 {{ user:viewnum }} 人次访问, {{ user:credit }}个积分 <a href="do.php?ac=ajax&op=credit&uid=$space[uid]" id="a_space_view" onclick="ajaxmenu(event, this.id, 99999)">{{ user:creditstar }}</a>
					{{ endif }}
				</p>

				<div class="current_status" id="mystate">
					{{ if user:mood }}<a href="space.php?uid=$space[uid]&do=mood" title="同心情"><img src="image/face/{$space[mood]}.gif" alt="同心情" class="face" /></a> {{ endif }}
					<?php if($user['spacenote']):?><?=getstr($user['spacenote'], 50)?><?php endif;?>
					{{ if user:self }}
					{{ if not user:mood && user:spacenote }}
						您在做什么？
					{{ endif }}
					&nbsp;(<a href="javascript:;" onclick="mood_from();" title="更新状态">更新状态</a><span class="pipe">|</span><a href="space.php?uid=$space[uid]&do=mood">同心情</a>)
					{{ endif }}
				</div>
				
				<?php if(empty($user['privacy']['view']['profile'])):?>
				<ul class="profile">
					<?php if($user['sex']):?>
						<li>性别：<?php if($user['sex']=='1'):?><a href="network.php?ac=space&sex=1&searchmode=1"><?=lang('man')?></a><?php elseif($user['sex']=='2'):?><a href="network.php?ac=space&sex=2&searchmode=1"><?=lang('woman')?></a><?php endif;?></li>
					<?php endif;?>
					<?php if($user['birthyear'] || $user['birthmonth'] || $user['birthday']):?>
						<li>生日：<?=($user['birthyear']?$user['birthyear'].lang('year'):'').($user['birthmonth']?$user['birthmonth'].lang('month'):'').($user['birthday']?$user['birthday'].lang('day'):'');?></li>
					<?php endif;?>
					<?php if($user['blood']):?>
						<li>血型：<?=$user['blood']?></li>
					<?php endif;?>
					<?php if($user['marry']):?>
						<li>婚恋：<?php if($user['marry']=='1'):?><a href="network.php?ac=space&marry=1&searchmode=1"><?=lang('unmarried')?></a><?php elseif($user['marry']=='2'):?><a href="network.php?ac=space&marry=2&searchmode=1"><?=lang('married')?></a><?php endif;?></li>
					<?php endif;?>
					<?php if($user['resideprovince'] || $user['residecity']):?>
					<li>居住：<?=trim(($user['resideprovince']?"<a href=\"network.php?ac=space&resideprovince=".rawurlencode($user['resideprovince'])."&searchmode=1\">$uch[space][resideprovince]</a>":'').($user['residecity']?" <a href=\"network.php?ac=space&residecity=".rawurlencode($user['residecity'])."&searchmode=1\">$uch[space][residecity]</a>":''))?></li>
					<?php endif;?>
					<?php if($user['birthprovince'] || $user['birthcity']):?>
					<li>家乡：<?=trim(($user['birthprovince']?"<a href=\"network.php?ac=space&birthprovince=".rawurlencode($user['birthprovince'])."&searchmode=1\">$uch[space][birthprovince]</a>":'').($user['birthcity']?" <a href=\"network.php?ac=space&birthcity=".rawurlencode($user['birthcity'])."&searchmode=1\">$uch[space][birthcity]</a>":''))?></li>
					<?php endif;?>
					<?php if($user['qq']):?>
					<li>QQ：<a target="_blank" href="http://wpa.qq.com/msgrd?V=1&Uin=<?=$user['qq']?>&Site=<?=$user['username']?>&Menu=yes"><?=$user['qq']?></a></li>
					<?php endif;?>
					<?php if($user['msn']):?>
					<li>MSN：<?=$user['msn']?></li>
					<?php endif;?>
				
					
				</ul>
				<?php endif;?>		
				
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