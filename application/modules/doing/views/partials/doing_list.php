{{ if count }}
<div class="doing_list">
	<ol>
	<?php foreach($list as $value):?>
		<li id="dl<?=$value['doid']?>">
			<div class="avatar48"><a href="<?=site_url('member/index/'.$value['uid'])?>"><img src="<!--{avatar($basevalue[uid],small)}-->" alt="<?=$value['username']?>" /></a></div>
			<div class="doing">
				<div class="doingcontent"><a href="<?=site_url('member/index/'.$value['uid'])?>"><?=$value['username']?></a>: <span><?=$value['message']?></span> 
				<a href="<?=site_url('comment/add/doing/'.$value['doid'])?>" id="do_comment_<?=$value['doid']?>" onclick="ajaxmenu(event, this.id, 99999, '', -1)" class="re">回复</a>
				<?php if($auth && $auth['uid']==$user['uid']):?> <a href="<?=site_url('doing/delete/'.$value['doid'])?>" id="doing_delete_{{ doid }}_{{ id }}" onclick="ajaxmenu(event, this.id, 99999)" class="re gray">删除</a><?php endif;?>
				</div>
				<div class="doingtime">(<?=sgmdate('m-d H:i', $value['dateline'], 1, $config['timeoffset'])?>)</div>
	
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
	<div class="page"><?=$pager?></div>
</div>

{{ else }}
<div class="c_form">现在还没有记录。<?php if($auth && $auth['uid']==$user['uid']):?>你可以用一句话记录下这一刻在做什么。<?php endif;?></div>
{{ endif }}