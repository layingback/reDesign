<?php
/*********************************************
  CPG Dragonfly™ CMS
  ********************************************
  Copyright © 2004 - 2009 by CPG-Nuke Dev Team
  http://dragonflycms.org

  Dragonfly is released under the terms and conditions
  of the GNU GPL version 2 or any later version

  $Source: /cvs/themes/reDesign/EXTRA/block-Current_Menu.php,v $
  $Revision: 1.5 $
  $Author: estlane $
  $Date: 2009/11/05 15:38:33 $
Encoding test: n-array summation ∑ latin ae w/ acute ǽ
********************************************************/
if (!defined('CPG_NUKE')) { exit; }
global $swctgry;

// Note: In administration set _MENUCATTITLE as block title

$show_category_name = 0;

if ($swctgry) {

	$content = '';

	if ($show_category_name) {
		$content .= '<a'.($swctgry['cat_link']?' href="'.$swctgry['cat_link'].'"':'').' class="curmenucat">'.$swctgry['cat_name'].'</a>';
	}

  $content .= '<ul id="curmenu_list">';

  /*Every link*/
  foreach ($swctgry['listing'] as $value1) {
    $curlink = isset($value1['current'])? ' class="curlink"' : '';
    $content .= '<li'.$curlink.'><a href="'.$value1['link'].'">'.$value1['title'].'</a></li>';
  }

  $content .= '</ul>';
} else {
  //Theme doesn't support this block
  $content = 'This theme does not support this block or category is unrecognized/empty';
}
