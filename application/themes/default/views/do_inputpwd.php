{{ theme:partial name="header" }}
<form method="post" action="{{ url:base }}admin/inputpwd" class="c_form">
	<table cellpadding="0" cellspacing="0" class="formtable">
		<caption>
			<h2>密码验证</h2>
			<p>您需要正确输入密码后才能继续查看</p>
		</caption>
		<tr>
			<th width="100">输入密码</th>
			<td><input type="password" name="viewpwd" value="" class="t_input" /></td>
		</tr>
		<tr>
			<th>&nbsp;</th>
			<td>
			<input type="hidden" name="refer" value="{{ input:REQUEST_URI }}" />
			{{ if router:module == 'blog' }}
			<input type="hidden" name="blogid" value="<?php echo $invalue['blogid'];?>" />
			{{ elseif router:modume == 'album' }}
			<input type="hidden" name="albumid" value="<?php echo $invalue['albumid'];?>" />
			{{ endif }}
			<input type="hidden" name="pwdsubmit" value="true" />
			<input type="submit" name="submit" value="提交" class="submit" />
			</td>
		</tr>
</table>
<input type="hidden" name="formhash" value="{{ helper:formhash }}" />
</form>
{{ theme:partial name="footer" }}