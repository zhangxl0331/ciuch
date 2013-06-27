<?php if($count):?>
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
	
				<?=partial('doing', 'comment', array('type'=>'doing', 'id'=>$value['doid']))?>
				
			</div>
		</li>
	<?php endforeach;?>
	</ol>
	<div class="page"><?=$pager?></div>
</div>

<?php else:?>
<div class="c_form">现在还没有记录。<?php if($auth && $auth['uid']==$user['uid']):?>你可以用一句话记录下这一刻在做什么。<?php endif;?></div>
<?php endif;?>