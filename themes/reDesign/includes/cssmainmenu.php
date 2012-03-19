<?php
/*********************************************
  CPG Dragonfly™ CMS
  ********************************************
  Copyright © 2004 - 2007 by CPG-Nuke Dev Team
  http://dragonflycms.org

  Dragonfly is released under the terms and conditions
  of the GNU GPL version 2 or any later version

  $Source: /cvs/themes/reDesign/includes/cssmainmenu.php,v $
  $Revision: 1.19 $
  $Author: estlane $
  $Date: 2010/03/22 17:32:09 $
**********************************************/
if (!defined('CPG_NUKE')) { exit; }
global $prefix, $db, $module_name, $language, $currentlang, $mainindex, $userinfo, $adminindex, $MAIN_CFG, $CPG_SESS, $swctgry;
if (!($MAIN_CFG['global']['admingraphic'] & 4)) return '';

/* Special URLs */

//Usage _MYBLOGURL
define('_MYBLOGURL_SPECIAL','Blogs&amp;file=user&amp;nick='.$userinfo['username']);

//User Attachment Control Panel
//Usage _MYATTACHMENTS
define('_MYATTACHMENTS_SPECIAL','Forums&amp;file=uacp&amp;u='.$userinfo['user_id']);

define('_MYGALLERY_SPECIAL','coppermine&amp;cat='.(10000+$userinfo['user_id']));

$curcat = false;
$menucats = array();
$modquery = $lnkquery = '';
$setstatus = 1;
$catonly = 0;


$swctgry = array();
$proov = array();

if (!is_admin()) {
  $modquery = 'WHERE m.active=1 AND m.inmenu=1';
  $lnkquery = 'WHERE l.active=1';
  $view = array();
  $view[] = 0;

  if (is_user()) {
    $view[] = 1;
    foreach($userinfo['_mem_of_groups'] AS $key => $value) {
      $view[] = $key+3;
    }
  } else {
    $view[] = 3;
  }
  $modquery .= ' AND m.view IN ('.implode(',', $view).')';
  $lnkquery .= ' AND l.view IN ('.implode(',', $view).')';
}

// Load active modules from database
$sql = 'SELECT m.title as link, m.custom_title as title, m.view, m.active, m.inmenu, m.cat_id AS category, m.pos AS linkpos, c.name, c.image, c.pos AS catpos, c.link AS catlnk, c.link_type AS cattype FROM '.$prefix.'_modules AS m LEFT JOIN '.$prefix.'_modules_cat c ON (c.cid = m.cat_id) '.$modquery;
$result = $db->sql_query($sql);

while ($row = $db->sql_fetchrow($result)) {
  if ($row['title'] == '') {
    $row['title'] = (defined('_'.$row['link'].'LANG')) ? constant('_'.$row['link'].'LANG') : ereg_replace('_', ' ', $row['link']);
  } else {
    if (defined($row['title'])) {
      $row['title'] = constant($row['title']);
    }
  }
  $row['link_type'] = -1;
  if (!isset($row['catpos'])) {
    $row['catpos'] = -1;
  }
  $menucats[$row['catpos']][$row['linkpos']] = $row;
}
$db->sql_freeresult($result);

// Load custom links from database
$sql = "SELECT l.title, l.link, l.link_type, l.view, l.active, l.cat_id AS category, l.pos AS linkpos, c.name, c.image, c.pos AS catpos, c.link AS catlnk, c.link_type AS cattype FROM ".$prefix."_modules_links AS l LEFT JOIN ".$prefix."_modules_cat c ON (c.cid = l.cat_id) $lnkquery";
$result = $db->sql_query($sql);

while ($row = $db->sql_fetchrow($result)) {
  if (defined($row['title'])) {
    $row['title'] = constant($row['title']);
  }
  if (defined($row['link'].'_SPECIAL')) $row['link'] = constant($row['link'].'_SPECIAL');

  //Active
  $catlink = eregi_replace('&amp;', '&', $row['catlnk']);
  $leocatlink = getlink($row['catlnk']);

  $link = eregi_replace('&amp;', '&', $row['link']);
  $leolink = getlink($row['link']);
  $categoryLink = getlink($row['category']);


  //echo '$row[\'link\'] = '.$row['link'].'   $setstatus = '.$setstatus.'   $catonly = '.$catonly."\n";
  //LINK is more important that category
  if (((get_uri() != '') && ((ereg($leolink, get_uri()) && ($link != $mainindex) && !defined('ADMIN_PAGES')) || (($link == $mainindex) && $home) || (($link == $adminindex) && defined('ADMIN_PAGES')))) || ((get_uri() == '') && (($link == $mainindex) && $home))) {
    $row['item_status'] = 'active';
    $setstatus = 0;
    $curcat = $row['category'];
    $row['current_link'] = 1;    //$catonly = 0;
  } else if (((get_uri() != '') && $catlink != '' && ((ereg($leocatlink, get_uri()) && ($catlink != $mainindex) && !defined('ADMIN_PAGES')) || (($catlink == $mainindex) && $home) || (($catlink == $adminindex) && defined('ADMIN_PAGES')))) || ((get_uri() == '') && (($catlink == $mainindex) && $home))) {
    $curcat = $row['category'];
    //$setstatus = 0;//Only one category can be active
    //$catonly = 1;
  //Check category only
  } else if (ereg($categoryLink, get_uri())) {
    //echo 'test';
  }

  $row['link'] = eregi_replace('&', '&amp;', $link);
  $row['catlnk'] = eregi_replace('&', '&amp;', $row['catlnk']);
  $row['inmenu'] = 1;
  if (!isset($row['catpos'])) {
    $row['catpos'] = -1;
  }
  $menucats[$row['catpos']][$row['linkpos']] = $row;
}
$db->sql_freeresult($result);

$nocatcontent = '';
$content = '<ul id="menuList">';
if (defined('ADMIN_PAGES')) {
  $content .= '<li><a href="'.$mainindex.'">'._HOME.'</a><ul>';
} elseif (!defined('ADMIN_PAGES') && is_admin()) {
  global $CLASS;
  require_once(CORE_PATH.'classes/cpg_adminmenu.php');
  $content .= '<li><a href="'.$adminindex.'">'._ADMIN.'</a>';
  $content .= $CLASS['adminmenu']->display('all', 'cssmenu');
  $content .='</li>';
}

$i = 0;
ksort($menucats);
while (list($ccat, $items) = each($menucats)) {
  ksort($items);
  $catcontent = $offcontent = $hidcontent = '';
  $firstincategory = 1;
  while (list($dummy, $item) = each($items)) {
    $current_link = 0;
    //$status = '<span class="sblack">&#8226;</span>';
    $item_status = 'normal';

    $categoryLink = getlink($item['category']);
    /*echo '<!--
    module_name:'.$module_name.';
    setstatus:'.$setstatus.';
    item[link]:'.$item['link'].';
    home:'.$home.';
    curcat:'.$curcat.($curcat? 'jah':'ei').';
    item[catlnk]'.$item['catlnk'].';
    -->';*/
    //MODULE is more important than category
    //echo '$item[\'link\'] = '.$item['link'].'   $setstatus = '.$setstatus.'   $catonly = '.$catonly."\n";
    if ($setstatus && ($item['link'] == $module_name) && !$home) {
      $item_status = 'active';
      $current_link = 1;
      $curcat = $item['category'];
    } else if ($setstatus && ($item['catlnk'] == $module_name) && !$curcat) {
      $curcat = $item['category'];
    } else if ($setstatus && $home && ($item['catlnk'] == $mainindex)) {
      if (($mainindex != '') xor ($item['name'] == '_HOME')) {
        $curcat = $item['category'];
      }
    } else if (ereg($categoryLink, get_uri())) {
      //echo 'test2';
    }
    //Disabled
    if (!$item['active']) {
      $item_status = 'disabled';
    //Enabled but hidden from menu
    } elseif ($item['active'] && !$item['inmenu']) {
      $item_status = 'hidden';
    }
    $item_status = isset($item['item_status']) ? $item['item_status'] : $item_status;

    $current_link = isset($item['current_link']) ? $item['current_link'] : $current_link;
    if ($item['link_type'] <= 0) {
      $item['link'] = getlink($item['link']);
    } elseif ($item['link_type'] == 2) {
      $item['link'] .= '" target="_blank';
    }

    $proov[$item['category']][$i]['link'] = $item['link'];
    $proov[$item['category']][$i]['title'] = $item['title'];
    $proov[$item['category']][$i]['category'] = $item['category'];

    //kui link on aktiivne
    if ($current_link) {
      $proov[$item['category']][$i]['current'] = 1;
    }
    $i++;

    $itemclass = $firstincategory? ($current_link?' first current':' first') : ($current_link?' current':'');
    //normal, active, disabled, hidden
    //black, green, red, gray

    if($item_status == 'active') {
      $tmpcontent = '<li class="'.$itemclass.'"><a href="'.$item['link'].'">'.$item['title'].'</a></li>';
    //Disabled module
    } else if ($item_status == 'disabled') {
      $tmpcontent = '<li class="disabled'.$itemclass.'"><a href="'.$item['link'].'">('.$item['title'].')</a></li>';
    //Not disabled but hidden
    } else if ($item_status == 'hidden') {
      $tmpcontent = '<li class="hidden'.$itemclass.'"><a href="'.$item['link'].'">'.$item['title'].'</a></li>';
    } else {
      $tmpcontent = '<li class="normal'.$itemclass.'"><a href="'.$item['link'].'">'.$item['title'].'</a></li>';
    }

    if (!$item['active'] && !$item['inmenu']) {
      $offcontent .= $tmpcontent;
    } elseif (!$item['active']) {
      /*hidcontent*/
      $catcontent .= $tmpcontent;
    } else {
      $catcontent .= $tmpcontent;
    }

    $cattitle = $item['name'];
    $catlnk  = $item['catlnk'];
    $cattype = $item['cattype'];
    $thiscat = $item['category'];

    $firstincategory = 0;
  }

  $cattitle = (defined($cattitle) ? constant($cattitle) : $cattitle);

  if (!empty($catlnk) || (($mainindex == '') && ($cattitle == _HOME))) {
    if ($cattype <= 0) {
      $catlnk = getlink($catlnk);
    } elseif ($cattype == 2) {
      $catlnk .= '" target="_blank';
    }
    $cattitlel = defined('ADMIN_PAGES') ? '<a href="'.$catlnk.'">'.$cattitle.'</a>' : '<a href="'.$catlnk.'">'.$cattitle.'</a>';
  } else {
    $cattitlel = defined('ADMIN_PAGES') ? '<a>'.$cattitle.'</a>': '<a>'.$cattitle.'</a>';
  }

  $iscurrentcat = (($thiscat === $curcat) && !defined('ADMIN_PAGES'))? ' class="currentcategory"' : (defined('ADMIN_PAGES')? ' class="normal"' : '');


 /* $swctgry = $proov[$curcat];*/

  if ($thiscat === $curcat) {
    $swctgry['listing'] = $proov[$curcat];
    $swctgry['cat_name'] = $cattitle? $cattitle : _NONE;
    $swctgry['cat_link'] = $catlnk;
  }

  if ($ccat >= 0) {
    $content .= '<li'.$iscurrentcat.'>'.(defined('ADMIN_PAGES')?'':'').$cattitlel.'<ul>'.$catcontent.'</ul></li>';
  } else {
    $nocatcontent = '<li'.$iscurrentcat.'>'.(defined('ADMIN_PAGES')?'':'').'<a>'._NONE.'</a><ul>'.$catcontent.'</ul></li>';
  }
}
$content .= $nocatcontent;

//echo $curcat;
//echo '<!--';
//print_r($proov);
//echo '-->';

if (defined('ADMIN_PAGES') && is_admin()) {
  $content .= '</ul></li>';
  global $CLASS;
  //require_once(CORE_PATH.'classes/cpg_adminmenu.php');
  $content .= $CLASS['adminmenu']->display($ccat, 'cssmenu');
  //Siin v�ib ka active olla?
}

$content .= '</ul>';
$mmcontent = $content;


//Let's define current category
//Now we can use it for a dynamic block title for example
if ($swctgry['cat_name']) {
	$menu_cat_title = $swctgry['cat_name'];
	if ($swctgry['cat_link']) {
		$menu_cat_title = '<a href="'.$swctgry['cat_link'].'">'.$menu_cat_title.'</a>';
	}
} else {
	$menu_cat_title = _MENU;
}
define('_MENUCATTITLE', $menu_cat_title);
