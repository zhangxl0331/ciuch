<div {{ input:inajax }}class="inpage"{{ endif }}>
<form method="post" action="cp.php?ac=blog&op=delete&blogid=$blogid">
	<h1>确定删除指定的日志吗？</h1>
	<p class="btn_line">
		<input type="hidden" name="refer" value="$_SGLOBAL[refer]" />
		<input type="hidden" name="deletesubmit" value="true" />
		<input type="submit" name="btnsubmit" value="确定" class="submit" />
		{{ input:inajax }}<input type="button" name="btnclose" value="取消" onclick="hideMenu();" class="button" />{{ endif }}
	</p>
<input type="hidden" name="formhash" value="<!--{eval echo formhash();}-->" />
</form>
</div>