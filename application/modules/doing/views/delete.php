<div id="<?=$id?>" <?php if(!empty($_GET['inajax'])):?>class="inpage"<?php endif;?>>
<?=form_open('doing/delete/'.$id, array('id'=>'doingform', 'name'=>'doingform'))?>
	<h1>确定删除该记录吗？</h1>
	<p class="btn_line">
		<input type="hidden" name="refer" value="<?php if(isset($_SERVER['HTTP_REFERER'])):?><?=$_SERVER['HTTP_REFERER']?><?php endif;?>">
		<input type="submit" name="deletesubmit" value="确定" class="submit" />
		<?php if(!empty($_GET['inajax'])):?>
		<input type="button" name="btnclose" value="取消" onclick="hideMenu();" class="button" />
		<?php endif;?>
	</p>
</form>
</div>