{{ if uch:space:self }}
<form method="post" action="{{ url:base }}doing/cp/add?view=$_GET[view]" class="post_doing">
	<ul style="margin-bottom: 1em;">
		<li>
			<p>		
			<table cellspacing="0" cellpadding="0" width="100%">
			<tr>
			<td><a href="###" id="face" onclick="showFace(this.id, 'message');"><img src="image/facelist.gif" align="absmiddle" /></a></td>
			<!--{if checkperm('seccode')}-->
			<td>
				<!--{if $_SCONFIG['questionmode']}-->
				请先回答提问：<!--{eval question();}--> 
				<!--{else}-->
				请先输入验证码：<script>seccode();</script> 
				<!--{/if}-->
				<input type="text" id="seccode" name="seccode" value="" size="10" class="t_input">
			</td>
			<!--{/if}-->
			<td align="right">还可输入 <strong id="maxlimit">200</strong> 个字符</td>
			</tr>
			</table>
			<textarea id="message" name="message" onkeyup="textCounter(this, 'maxlimit', 200)" onkeydown="ctrlEnter(event, 'add');" rows="4" style="width:438px; height: 72px;"></textarea>
			<input type="hidden" name="addsubmit" value="true" />
			<button type="submit" id="add" name="add" class="post_button">发布</button>
			</p>
		</li>
	</ul>
<input type="hidden" name="refer" value="$theurl" />
<input type="hidden" name="formhash" value="<!--{formhash();}-->" />
</form>
{{ endif }}