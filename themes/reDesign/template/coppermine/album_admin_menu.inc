<?php
$template_album_admin_menu = '
<form style="float:left" method="post" action="'.getlink('&amp;file=modifyalb').'">
  <input type="hidden" name="album" value="{ALBUM_ID}" />
  <input type="submit" name="submit" value="'.MODIFY.'" />
</form>

<form style="float:left" method="post" action="'.getlink('&amp;file=editpics').'">
  <input type="hidden" name="album" value="{ALBUM_ID}" />
  <input type="submit" name="submit" value="'.EDIT_PICS.'" />
</form>

<form style="float:left" method="post" action="'.getlink('&amp;file=delete').'">
  <input type="hidden" name="what" value="album" />
  <input type="hidden" name="id" value="{ALBUM_ID}" />
  <input type="submit" name="submit" value="'.DELETE.'" />
</form>
<br style="clear:both" />
';
?>
