{{ if count }}
<div class="doing_list">
	<ol>
	<?php foreach($list as $value):?>
		<li id="dl$basevalue[doid]">
			<div class="avatar48"><a href="<?=site_url('member/index/'.$value['uid'])?>"><img src="<!--{avatar($basevalue[uid],small)}-->" alt="<?=$value['username']?>" /></a></div>
			<div class="doing">
				<div class="doingcontent"><a href="<?=site_url('member/index/'.$value['uid'])?>"><?=$value['username']?></a>: <span><?=$value['message']?></span> 
				<a href="cp.php?ac=doing&op=comment&doid=$basevalue[doid]" id="do_comment_{{ doid }}" onclick="ajaxmenu(event, this.id, 99999, '', -1)" class="re">回复</a>
				{{ if uch:space:self }} <a href="cp.php?ac=doing&op=delete&doid={{ doid }}&id={{ id }}" id="doing_delete_{{ doid }}_{{ id }}" onclick="ajaxmenu(event, this.id, 99999)" class="re gray">删除</a>{{ endif }}
				</div>
				<div class="doingtime">({{ helper:sgmdate dateformat="m-d H:i", timestamp=dateline format="1" timeoffset=uch:config:timeoffset }})</div>
	
				<!--{if $clist[$basevalue[doid]] || $doid}-->
				<div class="sub_doing">
				<ol>
				
				{{ theme:partial name="doing_comment" module="doing" }}
				
				<!--{loop $clist[$basevalue[doid]] $value}-->
					<li style="margin-left: {$value[layer]}em;<!--{if $value[id]==$_GET[highlight]}-->color:red;font-weight:bold;<!--{/if}-->">
					<a href="space.php?uid=$value[uid]">$value[username]</a>: $value[message] <span class="doingtime">(<!--{date('m-d H:i',$value[dateline],1)}-->)</span> 
					<a href="cp.php?ac=doing&op=comment&doid=$value[doid]&id=$value[id]" id="do_comment_{$value[doid]}_{$value[id]}" onclick="ajaxmenu(event, this.id, 99999, '', -1)" class="re">回复</a>
					<!--{if $value[uid]==$_SGLOBAL[supe_uid] || $basevalue[uid] == $_SGLOBAL[supe_uid]}--> <a href="cp.php?ac=doing&op=delete&doid=$value[doid]&id=$value[id]" id="doing_delete_{$value[doid]}_{$value[id]}" onclick="ajaxmenu(event, this.id, 99999)" class="gray">删除</a><!--{/if}-->
					</li>
				<!--{/loop}-->
				</ol>
				</div>
				<!--{/if}-->
			</div>
		</li>
	<?php endforeach;?>
	</ol>
	<div class="page">$multi</div>
</div>

{{ else }}
<div class="c_form">现在还没有记录。{{ if uch:space:self }}你可以用一句话记录下这一刻在做什么。{{ endif }}</div>
{{ endif }}