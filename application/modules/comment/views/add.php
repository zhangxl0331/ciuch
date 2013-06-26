	<li>
		<div class="doingre">
		<?=form_open('comment/add/'.$module.'/'.$id, array('id'=>'doingform', 'name'=>'doingform'))?>
		<h1>评论两句</h1>
		<p class="btn_line">
			<a href="###" id="face_<?=$id?>" onclick="showFace(this.id, 'message_<?=$id?>');"><img src="image/facelist.gif" align="absmiddle" /></a>
			<input type="text" name="message" id="message_<?=$id?>" value="" class="t_input" size="35">
			<input type="hidden" name="refer" value="<?php if(isset($_SERVER['HTTP_REFERER'])):?><?=$_SERVER['HTTP_REFERER']?><?php endif;?>">
			<input type="submit" name="commentsubmit_submit" value="确定" class="submit" />
		</p>
		</form>
		</div>
	</li>