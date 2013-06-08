<?php if (validation_errors()): ?>
<div class="error-box">
	<?php echo validation_errors();?>
</div>
<?php endif; ?>
<?=form_open('member/login', array('id'=>'loginform', 'name'=>'loginform', 'class'=>'c_form'));?>
<table cellpadding="0" cellspacing="0" class="formtable">
	<caption>
		<h2><?=lang('please_login')?></h2>
		<p><?=lang('if_have_an_account')?></p>
	</caption>
	<tbody>
	<tr><th width="100"><?=lang('username', 'username');?></th><td><?=form_input(array('name'=>'username', 'id'=>'username', 'class'=>'t_input', 'value'=>set_value('username'), 'tabindex'=>2));?></td></tr>
	<tr><th width="100"><?=lang('password', 'password');?></th><td><?=form_password(array('name'=>'password', 'id'=>'password', 'class'=>'t_input', 'value'=>set_value('password'), 'tabindex'=>3));?></td></tr>
	<tr>
		<th width="100">&nbsp;</th>
		<td>
			<?=form_checkbox(array('id'=>'cookietime', 'name'=>'cookietime', 'value'=>315360000, 'checked'=>set_checkbox('cookietime'), 'style'=>'margin-bottom: -2px'))?><?=lang('auto_login_next_time', 'cookietime');?>
		</td>
	</tr>
	</tbody>
	<tr><th width="100">&nbsp;</th><td>
		<?=form_hidden('refer', '');?>
		<?=form_submit(array('id'=>'loginsubmit', 'name'=>'loginsubmit', 'value'=>lang('login'), 'class'=>'submit', 'tabindex'=>5));?>
		<a href="do.php?ac=lostpasswd"><?=lang('lost_passwd')?></a>
	</td></tr>
</table>
<?=form_hidden('formhash', formhash());?></form>

<script type="text/javascript">
	var lastSecCode = '';
	function checkSeccode() {
		var seccodeVerify = $('seccode').value;
		if(seccodeVerify == lastSecCode) {
			return;
		} else {
			lastSecCode = seccodeVerify;
		}
		ajaxresponse('checkseccode', 'op=checkseccode&seccode=' + (is_ie && document.charset == 'utf-8' ? encodeURIComponent(seccodeVerify) : seccodeVerify));
	}
	function ajaxresponse(objname, data) {
		var x = new Ajax('XML', objname);
		x.get('do.php?ac=$_SCONFIG[register_action]&' + data, function(s){
			var obj = $(objname);
			s = trim(s);
			if(s.indexOf('succeed') > -1) {
				obj.style.display = '';
				obj.innerHTML = '<img src="image/check_right.gif" width="13" height="13">';
				obj.className = "warning";
			} else {
				warning(obj, s);
			}
		});
	}
	function warning(obj, msg) {
		if((ton = obj.id.substr(5, obj.id.length)) != 'password2') {
			$(ton).select();
		}
		obj.style.display = '';
		obj.innerHTML = '<img src="image/check_error.gif" width="13" height="13"> &nbsp; ' + msg;
		obj.className = "warning";
	}

</script>

<!--{if $_SGLOBAL['input_seccode']}-->
<script>
$('seccode').style.background = '#FFFFCC';
$('seccode').focus();
</script>
<!--{/if}-->


<div class="c_form">
<table cellpadding="0" cellspacing="0" class="formtable">
	<caption>
		<h2><?=lang('have_not_yet_registered')?></h2>
		<p><?=lang('if_have_not_register')?></p>
	</caption>
	<tr>
		<td>
		<a href="do.php?ac=$_SCONFIG[register_action]" style="display: block; margin: 0 110px 2em; width: 100px; border: 1px solid #486B26; background: #76A14F; line-height: 30px; font-size: 14px; text-align: center; text-decoration: none;"><strong style="display: block; border-top: 1px solid #9EBC84; color: #FFF; padding: 0 0.5em;"><?=lang('register_now')?></strong></a>
		</td>
	</tr>
</table>
</div>