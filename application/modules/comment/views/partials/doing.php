<?php foreach(comment_list($type, $id) as $value):?>
<div class="sub_doing">
<ol>
	<li style="margin-left: {$value[layer]}em;<!--{if $value[id]==$_GET[highlight]}-->color:red;font-weight:bold;<!--{/if}-->">
	<a href="space.php?uid=$value[uid]"><?=$value['author']?></a>: <?=$value['message']?> <span class="doingtime">(<?=sgmdate('m-d H:i', $value['dateline'], 1, $config['timeoffset'])?>)</span> 
	<a href="<?=site_url('comment/add/comment/'.$value['cid'])?>" id="do_comment_{$value[doid]}_{$value[id]}" onclick="ajaxmenu(event, this.id, 99999, '', -1)" class="re">回复</a>
	<?php if($value['authorid']==$auth['uid']):?> <a href="<?site_url('comment/delete/'.$value['cid'])?>" id="doing_delete_{$value[doid]}_{$value[id]}" onclick="ajaxmenu(event, this.id, 99999)" class="gray">删除</a><?php endif;?>
	</li>
</ol>
</div>
<?php endforeach;?>