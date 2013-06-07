<div class="showmessage">
	<div class="ye_r_t"><div class="ye_l_t"><div class="ye_r_b"><div class="ye_l_b">
		<caption>
			<h2>信息提示</h2>
		</caption>
		<p><?php echo $message;?></p>
		<p class="op">
		<?php if($url_forward):?>
			<a href="<?php echo $url_forward;?>">页面跳转中...</a>
		<?php else:?>
			<a href="javascript:history.go(-1);">返回上一页</a> | 
			<a href="index.php">返回首页</a>
		<?php endif;?>
		</p>
	</div></div></div></div>
</div>