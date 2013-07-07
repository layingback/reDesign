<?php
global $CPG_SESS;
$template_img_navbar = '
<div style="padding-top: 20px; width: 94%; margin-left: 3%;">
	<a href="{THUMB_TGT}">'.THUMB_TITLE.'</a>
	&nbsp;|&nbsp;
	<a href="{SLIDESHOW_TGT}">'.SLIDESHOW_TITLE.'</a>
	&nbsp;|&nbsp;
	<a href="{ECARD_TGT}">'.ECARD_TITLE.'</a>
	<span class="float_right">
		<a href="{PREV_TGT}">&laquo; '.PREV_TITLE.'</a>
		&nbsp;|&nbsp;
		<a href="{ALBUM_TGT}">'.ALBUM.'</a>
		&nbsp;|&nbsp;
		<a href="{NEXT_TGT}">'.NEXT_TITLE.' &raquo;</a>
	</span>
<br style="clear:both;" />
	<a class="float_left" style="margin-top:-310px;opacity:0.8;" href="{PREV_TGT}" ><img src="images/spacer.gif" height="270" width="79" alt="" onmouseover="this.src=\'themes/'.$CPG_SESS['theme'].'/images/coppermine/img_navbar/prev_b.png\'" onmouseout="this.src=\'images/spacer.gif\'" /></a>
	<a class="float_right" style="margin-top:-310px;opacity:0.8;" href="{NEXT_TGT}"><img src="images/spacer.gif" height="270" width="79" alt="" onmouseover="this.src=\'themes/'.$CPG_SESS['theme'].'/images/coppermine/img_navbar/next_b.png\'" onmouseout="this.src=\'images/spacer.gif\'" /></a>

</div>
';
?>
