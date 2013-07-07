<?php
/*********************************************
  CPG Dragonfly™ CMS
  ********************************************
  Copyright © 2004 - 2009 by CPG-Nuke Dev Team
  http://dragonflycms.org

  Dragonfly is released under the terms and conditions
  of the GNU GPL version 2 or any later version

  Code derived from and based on
  Source: /cvs/themes/reDesign/EXTRA/block-Current_Menu.php,v
  Revision: 1.5
  Author: estlane
  Date: 2009/11/05 15:38:33

  $Revision: 1.6 $
  $Date: 2012-11-28 08:06:27 $
  Author: layingback
********************************************************/
if (!defined('CPG_NUKE')) { exit; }
global $swctgry, $CPG_SESS;

// Note: In administration set _MENUCATTITLE as block title
$show_category_name = 1;

// Note: Hide 3rd level menus initially (other than current one)
$hide_curmenu_sublist = 1;

if ($swctgry) {

	$content = '<div id="curmen">';

	if ($show_category_name) {
		$content .= '<a'.($swctgry['cat_link']?' href="'.$swctgry['cat_link'].'"':'').' class="curmenucat">'.$swctgry['cat_name'].'</a>';
	}

  $content .= '<ul id="curmenu_list">';
	$sublist = 0;
  /*Every link*/
  foreach ($swctgry['listing'] as $value1) {
    $curlink = isset($value1['current'])? ' class="curlink"' : '';
		if (isset($value1['label']) && $sublist == 1) {
			$content .= '</ul></li>';
			$sublist = 0;
		}
		$curlink .= isset($value1['label']) ? ($value1['label'] == 2 ? ' class="curlabel"' : ' class=" label"') : '';
    if (!empty($value1['link'])) {
			$content .= '<li'.$curlink.'><a href="'.$value1['link'].'">'.$value1['title'].'</a>';
	} else {
			$content .= '<li'.$curlink.'><p onclick="menu_toggle(this.parentNode);">'.$value1['title'].'</p>';
		}
		if (isset($value1['label'])) {
			$content .= $hide_curmenu_sublist && $value1['label']!=2 ? '<ul class="curmenu_sublist_hide">' : '<ul class="curmenu_sublist">';	// never hide current ul
			$sublist = 1;
	}
  }
	$content .= ($sublist == 1 ? '</ul></li>' : '');

  $content .= '<li class="label"><a>&nbsp;</a></li></ul></div>';

	$content .= "\n".'<script language="JavaScript" type="text/javascript">
<!--
function menu_toggle(x)
{ x.lastElementChild.className = (x.lastElementChild.className=="curmenu_sublist_hide") ? "curmenu_sublist" : "curmenu_sublist_hide"; }
//-->
</script>';

} else {
  //Theme doesn't support this block
  $content = '<ul id="curmenu_list"><li class="curlink"><a href="javascript:history.go(-1)">Go Back</a></li></ul>';
}
