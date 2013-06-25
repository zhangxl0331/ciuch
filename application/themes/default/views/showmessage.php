<?php if(!empty($_GET['inajax'])):?>
<?php 
ob_end_clean();
if (function_exists('ob_gzhandler')) {
	ob_start('ob_gzhandler');
} else {
	ob_start();
}
@header("Expires: -1");
@header("Cache-Control: no-store, private, post-check=0, pre-check=0, max-age=0", FALSE);
@header("Pragma: no-cache");
@header("Content-type: application/xml; charset=UC_CHARSET");
echo '<'."?xml version=\"1.0\" encoding=\"$_SC[charset]\"?>\n";
?>
<root><![CDATA[
<?php if(isset($_GET['popupmenu_box'])):?>
	<h1>&nbsp;</h1><a href="javascript:;" onclick="hideMenu();" class="float_del">X</a><div class="popupmenu_inner">
	<?php if(!empty($url_forward)):?>
	<a href="<?=$url_forward?>"><?=$message?></a><ajaxok>
	<?php else:?>
	<?=$message?>
	<?php endif;?>
	</div>
<?php else:?>
	<?php if(!empty($url_forward)):?>
	<a href="<?=$url_forward?>"><?=$message?></a><ajaxok>
	<?php else:?>
	<?=$message?>
	<?php endif;?>
<?php endif?>
]]></root>;
<?php else:?>
<div class="showmessage">
	<div class="ye_r_t"><div class="ye_l_t"><div class="ye_r_b"><div class="ye_l_b">
		<caption>
			<h2>信息提示</h2>
		</caption>
		<p>
		<?php if(!empty($url_forward)):?>
		<a href="<?=$url_forward?>"><?=$message?></a><script>setTimeout("window.location.href ='<?=$url_forward?>';", <?=$second?>*1000);</script>
		<?php else:?>
		<?=$message;?>
		<?php endif;?>
		</p>
		<p class="op">
		<?php if(!empty($url_forward)):?>
			<a href="<?=$url_forward;?>">页面跳转中...</a>
		<?php else:?>
			<a href="javascript:history.go(-1);">返回上一页</a> | 
			<a href="<?=site_url('page/index')?>">返回首页</a>
		<?php endif;?>
		</p>
	</div></div></div></div>
</div>
<?php endif;?>