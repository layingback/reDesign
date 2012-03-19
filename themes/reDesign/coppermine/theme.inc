<?php
/***************************************************************************
   Coppermine Photo Gallery 1.3.1 for CPG-Nuke
  **************************************************************************
   Port Copyright (C) 2004-2008 Coppermine/CPG-Nuke Dev Team
   http://dragonflycms.org
  **************************************************************************
   v1.1 originally written by Grégory Demar
   Copyright (C) 2002,2003 http://coppermine.sf.net/team/
   This program is free software; you can redistribute it and/or modify
   it under the terms of the GNU General Public License as published by
   the Free Software Foundation; either version 2 of the License, or
   (at your option) any later version.
  **************************************************************************
  Last modification notes:
  $Source: /cvs/themes/reDesign/coppermine/theme.inc,v $
  $Revision: 1.19 $
  $Author: estlane $
  $Date: 2010/03/03 15:46:58 $
****************************************************************************/
if (!defined('IN_COPPERMINE')) die('Not in Coppermine...');

global $cpgtpl, $template, $template_display_picture, $template_image_comments,
 $template_add_your_comment, $modheader, $no_border;

/*Disable border for OpenTable*/
global $no_border;
$no_border = 1;
$breadcrumbDisabled = true;

$rD_path = 'themes/'.$CPG_SESS['theme'];//reDesign path
$cop_templ = $rD_path.'/template/coppermine';//coppermine templatespath
$cop_imag = $rD_path.'/images/coppermine';//coppermine imagespath

define('_ALBUMS', ALBUMS);
//define('_CATEGORY', CATEGORY); //Already defined in main.php
define('_PICTURES', PICTURES);


function tmpl_to_var($file, $load = true) {
	global $cop_templ, $CPG_M_DIR, $CPG_URL;
	if ($load) {
		$thefile = implode('', file($cop_templ.'/'.$file));
	} else {
    	$thefile = $file;
    }
	$var = eval("return '".preg_replace("/'/", "\'", $thefile)."';");
	if (!$var) {
		cpg_error('There occured an error while processing '.$cop_templ.'/'.$file);
	}
	return $var;
}

$modheader .= '<link rel="stylesheet" type="text/css" href="'.$rD_path.'/style/coppermine.css" />
<script type="text/javascript" src="'.$CPG_M_DIR.'/scripts.js"></script>
<script type="text/javascript" src="'.$CPG_M_DIR.'/jspw.js"></script>
<script type="text/javascript" src="includes/javascript/MM_funcs.js"></script>
';

// HTML template for the USER info box in the user list view
require($cop_templ.'/user_list_info_box.inc'); //$template_user_list_info_box       index.php

// HTML template for the ALBUM admin menu displayed in the album list
require($cop_templ.'/album_admin_menu.inc');   //$template_album_admin_menu         index.php

// HTML template for the image navigation bar
require($cop_templ.'/display_image/img_navbar.inc');         //$template_img_navbar               displayimage.php
// HTML template for intermediate image display
//require($cop_templ.'/display_image/display_picture.inc');    //$template_display_picture          displayimage.php
$template_display_picture = tmpl_to_var('display_image/display_picture.html');     // displayimage.php
// HTML template for the image rating box

$cpgtpl->assign_vars(array(
  'VOTES'         => '{VOTES}',
  'PID'           => '{PID}',
  'CURRENTPAGE'   => '{CURRENTPAGE}',
  'RUBBISH'       => RUBBISH,
  'POOR'          => POOR,
  'FAIR'          => FAIR,
  'GOOD'          => GOOD,
  'EXCELLENT'     => EXCELLENT,
  'GREAT'         => GREAT,
  'RATE_THIS_PIC' => RATE_THIS_PIC,
  'COP_IMAG'      => $cop_imag,
  'U_RATE_PIC'    => getlink('&amp;file=ratepic')
));
$cpgtpl->set_filenames(array(
  'image_rating' => 'coppermine/display_image/image_rating.html'
));
//Dragonfly 10 has a new method to_string for cpgtpl object, so we use it
if (version_compare(CPG_NUKE, '10.0.0', '>=')) {
  $template_image_rating = $cpgtpl->to_string('image_rating');
//We use the alternative for Dragonfly 9 (the idea is exactly the same actually)
} else {
  ob_start();
  $cpgtpl->display('image_rating');
  $template_image_rating = ob_get_clean();
}


// HTML template for the display of comments
$template_image_comments = tmpl_to_var('display_image/image_comments.html');     // displayimage.php

$template_add_your_comment = tmpl_to_var('display_image/add_your_comment.html'); // displayimage.php

// HTML template used by the msg_box function
$template_msg_box = tmpl_to_var('msg_box.html');                   // functions.inc

// HTML template for e-cards
$template_ecard = tmpl_to_var('ecard.html');                       // displayecard.php


// HTML template used by the cpg_die function
$template_cpg_die = 'cpg_die.html';                                  // functions.inc
// HTML template for One picture editing
$template_edit_one_pic = 'editonepic.inc';                       // editDesc.inc


// Template used for tabbed display
$template_tab_display = array(
  'left_text' => '<div style="float:left;">{LEFT_TEXT}</div>',
  'tab_header' => '<div class="pagination" style="float:right;"><ul>',
  'tab_trailer' => '</ul></div><div class="clear"></div>',
  'active_tab' => '<li><a class="currentpage">%d</a></li>&nbsp;',
  'inactive_tab' => '<li><a href="{LINK}">%d</a></li>&nbsp;'
);


// Function to start a 'standard' table
function starttable($width = '-1', $title='', $title_colspan='1') {
  global $cpgtpl;

  $cpgtpl->assign_vars(array(
   'STARTTABLE'    => 1
  ));
  if ($title!='') {
    $cpgtpl->assign_vars(array(
      'TITLE_COLSPAN' => $title_colspan,
      'ST_TITLE'      => $title
    ));
  }
  $cpgtpl->set_filenames(array('standard_table' => 'coppermine/standard_table.html'));
  $cpgtpl->display('standard_table', false);
}

function endtable() {
  global $cpgtpl;

  $cpgtpl->assign_vars(array(
    'STARTTABLE'    => 0,
    'ENDTABLE'      => 1
  ));
  $cpgtpl->set_filenames(array('standard_table' => 'coppermine/standard_table.html'));
  $cpgtpl->display('standard_table', false);
}

function theme_main_menu()
{
    /*global $MAIN_CFG, $CONFIG, $CPG_M_DIR, $CPG_M_URL, $CPG_URL, $album, $meta_link, $cat, $cop_templ, $cpgtpl;

    $meta_link = META_LNK;
    $my_gallery_id = FIRST_USER_CAT + USER_ID;

    $cpgtpl->assign_block_vars('meta_links',array(
      'LASTUP_URL'      => getlink('&amp;file=thumbnails&amp;meta=lastup'.META_LNK),
      'LASTUP_NAME'     => LASTUP_LNK,
      'LASTCOM_URL'     => getlink('&amp;file=thumbnails&amp;meta=lastcom'.META_LNK),
      'LASTCOM_NAME'    => LASTCOM_LNK,
      'TOPN_URL'        => getlink('&amp;file=thumbnails&amp;meta=topn'.META_LNK),
      'TOPN_NAME'       => TOPN_LNK,
      'TOPRATED_URL'    => getlink('&amp;file=thumbnails&amp;meta=toprated'.META_LNK),
      'TOPRATED_NAME'   => TOPRATED_LNK,
      'FAV_URL'         => getlink('&amp;file=thumbnails&amp;meta=favpics'.META_LNK),
      'FAV_NAME'        => FAV_LNK,
      'SEARCH_URL'      => getlink('&amp;file=search'),
      'SEARCH_NAME'     => SEARCH_LNK,
      'HELP_URL'        => getlink('&amp;file=help'),
      'HELP_TITLE'      => HELP_TITLE,
      'HELP_NAME'       => HELP_LNK
    ));

    $cpgtpl->assign_block_vars('album_list',array(
      'URL'   => getlink(),
      'TITLE' => ALB_LIST_TITLE,
      'NAME'  => _coppermineLANG.' '._HOME
    ));

    $cpgtpl->assign_block_vars('my_profile',array(
      'URL'   => USERPROF_URL,
      'TITLE' => MY_PROF_TITLE,
      'NAME'  => MY_PROF_LNK
    ));

    $cpgtpl->assign_block_vars('my_gallery',array(
      'URL'   => getlink('&amp;cat='.$my_gallery_id),
      'TITLE' => MY_GAL_TITLE,
      'NAME'  => MY_GAL_LNK
    ));

    if (USER_ID || !USER_CAN_UPLOAD_PICTURES) {
        $cpgtpl->assign_block_vars('upload_pic',array(
          'URL'   => getlink('&amp;file=upload'),
          'TITLE' => UPLOAD_PIC_TITLE,
          'NAME'  => UPLOAD_PIC_LNK
        ));
    }

    $cpgtpl->set_filenames(array('main_menu' => 'coppermine/main_menu.html'));

    if (version_compare(CPG_NUKE, '10.0.0', '>=')) {
      return $cpgtpl->to_string('main_menu');
    } else {
      ob_start();
      $cpgtpl->display('main_menu');
      return ob_get_clean();
    }*/
}

function theme_admin_mode_menu()
{
    global $CPG_URL, $cat, $album, $cat_l, $cop_templ;

    $cat_l = is_numeric($album) ? "&album=$album" : (is_numeric($cat) ? "&cat=$cat" :'');
    //$cat_l =  (is_numeric($cat)) ? "&cat=$cat" :'';
    $html  = '';
    $userhtml ='';

    if (GALLERY_ADMIN_MODE) {
        require($cop_templ.'/admin_menu.inc');
    } elseif (USER_ADMIN_MODE) {
        require($cop_templ.'/admin_menu_user.inc');
    }
    return $html;
}


function theme_display_cat_list($breadcrumb, &$cat_data, $statistics) {
	global $cpgtpl, $Blocks;

	$cpgtpl->assign_vars(array(
		'CATEGORY'  => CATEGORY,
		'ALBUMS'    => ALBUMS,
		'PICTURES'  => PICTURES
	));

    $i=0;
    foreach($cat_data as $catID => $category) {
		$i++;

		// 609 is similar to "cop"permine
		$categoryAsBlockID = '609'.$catID;

		// If only title and description is given
		if (count($category) == 2) {
			$cpgtpl->assign_block_vars('catrow_noalb', array(
				'CAT_TITLE'		=> $category[0],
				'CAT_DESC'		=> $category[1],
				'ROW_CLASS'		=> ($i%2)?'1':'2',
				'CAT_BLOCK_ID'	=> $categoryAsBlockID,
				'S_VISIBLE'		=> $Blocks->hideblock($categoryAsBlockID) ? ' style="display:none"' : '',
			));
		// Else, if has albums info
		} elseif (isset($category['cat_albums']) && ($category['cat_albums'] != '')) {
			//Show category album
			$cpgtpl->assign_block_vars('catrow', array(
				'CAT_TITLE'		=> $category[0],
				'CAT_DESC'		=> $category[1],
				'CAT_ALBUMS'	=> $category['cat_albums'],
				'ALB_COUNT'		=> $category[2],
				'PIC_COUNT'		=> $category[3],
				'ROW_CLASS'		=> ($i%2)?'1':'2',
				'CAT_BLOCK_ID'	=> $categoryAsBlockID,
				'S_VISIBLE'		=> $Blocks->hideblock($categoryAsBlockID) ? ' style="display:none"' : '',
			));
		// If doesn't have albums info
		} else {
			//Empty category
			$cpgtpl->assign_block_vars('catrow_empty', array(
				'CAT_TITLE'		=> $category[0],
				'CAT_DESC'		=> $category[1],
				'CAT_ALBUMS'	=> '',
				'ALB_COUNT'		=> $category[2],
				'PIC_COUNT'		=> $category[3],
				'ROW_CLASS'		=> ($i%2)?'1':'2',
				'CAT_BLOCK_ID'	=> $categoryAsBlockID,
				'S_VISIBLE'		=> $Blocks->hideblock($categoryAsBlockID) ? ' style="display:none"' : '',
			));
		}

	}

	if ($statistics && count($cat_data)>0) {
		$cpgtpl->assign_vars(array(
			'STATISTICS'    => $statistics
		));
	}

	$cpgtpl->set_filenames(array('cat_list' => 'coppermine/cat_list.html'));
	$cpgtpl->display('cat_list',false);
}


/* Would like to have this before menu actually */
function theme_display_breadcrumb($breadcrumb, &$cat_data) {

	global $cpgtpl;

	if ($breadcrumb) {

		$traces = getBreadCrumbTraces($breadcrumb);

		$tracesLastKey = count($traces)-1;
		foreach ($traces as $key => &$part) {
			$cpgtpl->assign_block_vars('trace', array(
				'TITLE'			=> $part[0],
				'HAS_URL'		=> isset($part[1]),
				'URL'			=> isset($part[1])? $part[1] : '',
				'IS_LAST'		=> $key == $tracesLastKey,
			));
		}

		/*$cpgtpl->assign_vars(array(
			'BREADCRUMB'    => $breadcrumb
		));*/
		$cpgtpl->set_filenames(array('breadcrumb' => 'coppermine/breadcrumb.html'));
		$cpgtpl->display('breadcrumb');
	}






	/*************************************************************************/
	// Include main menu inside breadcrumb, because we want to show breadcrumb first
	global $MAIN_CFG, $CONFIG, $CPG_M_DIR, $CPG_M_URL, $CPG_URL, $album, $meta_link, $cat, $cop_templ;

	$meta_link = META_LNK;
	$my_gallery_id = FIRST_USER_CAT + USER_ID;

	$cpgtpl->assign_block_vars('meta_links',array(
      'LASTUP_URL'      => getlink('&amp;file=thumbnails&amp;meta=lastup'.META_LNK),
      'LASTUP_NAME'     => LASTUP_LNK,
      'LASTCOM_URL'     => getlink('&amp;file=thumbnails&amp;meta=lastcom'.META_LNK),
      'LASTCOM_NAME'    => LASTCOM_LNK,
      'TOPN_URL'        => getlink('&amp;file=thumbnails&amp;meta=topn'.META_LNK),
      'TOPN_NAME'       => TOPN_LNK,
      'TOPRATED_URL'    => getlink('&amp;file=thumbnails&amp;meta=toprated'.META_LNK),
      'TOPRATED_NAME'   => TOPRATED_LNK,
      'FAV_URL'         => getlink('&amp;file=thumbnails&amp;meta=favpics'.META_LNK),
      'FAV_NAME'        => FAV_LNK,
      'SEARCH_URL'      => getlink('&amp;file=search'),
      'SEARCH_NAME'     => SEARCH_LNK,
      'HELP_URL'        => getlink('&amp;file=help'),
      'HELP_TITLE'      => HELP_TITLE,
      'HELP_NAME'       => HELP_LNK
    ));

    $cpgtpl->assign_block_vars('album_list',array(
      'URL'   => getlink(),
      'TITLE' => ALB_LIST_TITLE,
      'NAME'  => _coppermineLANG.' '._HOME
    ));

    $cpgtpl->assign_block_vars('my_profile',array(
      'URL'   => USERPROF_URL,
      'TITLE' => MY_PROF_TITLE,
      'NAME'  => MY_PROF_LNK
    ));

    $cpgtpl->assign_block_vars('my_gallery',array(
      'URL'   => getlink('&amp;cat='.$my_gallery_id),
      'TITLE' => MY_GAL_TITLE,
      'NAME'  => MY_GAL_LNK
    ));

    if (USER_ID || !USER_CAN_UPLOAD_PICTURES) {
        $cpgtpl->assign_block_vars('upload_pic',array(
          'URL'   => getlink('&amp;file=upload'),
          'TITLE' => UPLOAD_PIC_TITLE,
          'NAME'  => UPLOAD_PIC_LNK
        ));
    }


    $cpgtpl->set_filenames(array('main_menu' => 'coppermine/main_menu.html'));
	$cpgtpl->display('main_menu');
}


/**
* Returns theme-ified image if needed
*/
function getThumbnailImg($existingImage) {
	global $module_name, $rD_path;

	// There might be some more special images than nopic.jpg to be added in here in future

	// Use themespecific nopic image instead (quite ugly and slow hack)
	if (strpos($existingImage, 'modules/'.$module_name.'/images/nopic.jpg')) {
		//$thumbImage = $cop_imag.'/nopic.jpg';
		//Use the non standard image path, because coppermine DOES sometimes search for nopic.jpg in there by itself (but not here)
		$thumbImage = $rD_path.'/coppermine/images/nopic.jpg';
		$thumbImage = '<img src="'.$thumbImage.'" alt="'.NO_IMG_TO_DISPLAY.'" title="'.NO_IMG_TO_DISPLAY.'" class="image" />';
	} else {
		$thumbImage = $existingImage;
	}

	return $thumbImage;
}


// Function to display Albums
function theme_display_album_list(&$alb_list,$nbAlb, $cat, $page, $total_pages)
{
    global $CONFIG, $STATS_IN_ALB_LIST, $CPG_URL, $CPG_M_URL, $statistics, $template_tab_display, $cpgtpl, $module_name, $cop_imag;

    $theme_alb_list_tab_tmpl = $template_tab_display;
    $theme_alb_list_tab_tmpl['left_text'] = strtr($theme_alb_list_tab_tmpl['left_text'],array('{LEFT_TEXT}' => ALBUM_ON_PAGE));
    $theme_alb_list_tab_tmpl['inactive_tab'] = strtr($theme_alb_list_tab_tmpl['inactive_tab'],array('{LINK}' => $CPG_M_URL.'&cat='.$cat.'&page=%d'));
    $tabs = create_tabs($nbAlb, $page, $total_pages, $theme_alb_list_tab_tmpl);

    $count = 0;

    $columns = $CONFIG['album_list_cols'];
    $column_width = floor(100/$columns);
    $thumb_cell_width = $CONFIG['alb_list_thumb_size']+2;

    if ($STATS_IN_ALB_LIST) {
      $cpgtpl->assign_block_vars('stat_row', array(
        'STATISTICS'  => $statistics,
        'COLUMNS'     => $columns
      ));
    }

    if (is_array($alb_list)) {
      foreach($alb_list as $album) {
        $count++;

		$thumbImage = getThumbnailImg($album['thumb_pic']);

        $cpgtpl->assign_block_vars('album_cell', array(
          'COL_WIDTH'         => $column_width,
          'ALBUM_TITLE'       => $album['album_title'],
          'THUMB_CELL_WIDTH'  => $thumb_cell_width,
          'ALB_LINK_TGT'      => getlink('&amp;file=thumbnails&amp;album='.$album['aid']),
          'ALB_LINK_PIC'      => $thumbImage,
          'ADMIN_MENU'        => $album['album_adm_menu'],
          'ALB_DESC'          => $album['album_desc'],
          'ALB_INFOS'         => $album['album_info'],
          'NEW_LINE'          => ($count>1 && (($count-1) % $columns) == 0)
        ));
      }
    }

    while ($count++ % $columns != 0) {
      $cpgtpl->assign_block_vars('empty_cell', array(
        'COL_WIDTH'         => $column_width
      ));
    }


    //Tab display
    $cpgtpl->assign_block_vars('tabs', array(
        'COLUMNS' => $columns,
        'TABS' => $tabs,
    ));

    $cpgtpl->assign_block_vars('spacer',array());

    $cpgtpl->set_filenames(array('album_list' => 'coppermine/album_list.html'));
    $cpgtpl->display('album_list',false);
    $cpgtpl->unset_block('album_cell');//So that the albums wont start repeating
}

// Function to display Albums with Categories
function theme_display_album_list_cat(&$alb_list, $nbAlb, $cat, $page, $total_pages)
{
    global $CONFIG, $STATS_IN_ALB_LIST, $CPG_URL, $CPG_M_URL, $statistics, $template_tab_display, $cpgtpl, $module_name, $cop_imag;
    if (!$CONFIG['first_level']) {
        return;
    }

    $theme_alb_list_tab_tmpl = $template_tab_display;
    $theme_alb_list_tab_tmpl['left_text'] = strtr($theme_alb_list_tab_tmpl['left_text'], array('{LEFT_TEXT}' => ALBUM_ON_PAGE));
    $theme_alb_list_tab_tmpl['inactive_tab'] = strtr($theme_alb_list_tab_tmpl['inactive_tab'], array('{LINK}' => $CPG_M_URL.'&cat=' . $cat . '&page=%d'));
    $tabs = create_tabs($nbAlb, $page, $total_pages, $theme_alb_list_tab_tmpl);

    $count = 0;

    $columns = $CONFIG['album_list_cols'];
    $column_width = floor(100 / $columns);
    $thumb_cell_width = $CONFIG['alb_list_thumb_size'] + 2;

    if ($STATS_IN_ALB_LIST) {
      $cpgtpl->assign_vars(array(
        'STATISTICS' => $statistics
      ));
    }

    if (is_array($alb_list)) {
      foreach($alb_list as $album) {
        $count++;

		$thumbImage = getThumbnailImg($album['thumb_pic']);

        $cpgtpl->assign_block_vars('c_album_cell', array(
          'COL_WIDTH' => $column_width,
          'ALBUM_TITLE' => $album['album_title'],
          'THUMB_CELL_WIDTH' => $thumb_cell_width,
          'ALB_LINK_TGT' => getlink('&amp;file=thumbnails&amp;album='.$album['aid']),
          'ALB_LINK_PIC' => $thumbImage,
          'ADMIN_MENU' => $album['album_adm_menu'],
          'ALB_DESC' => $album['album_desc'],
          'ALB_INFOS' => $album['album_info'],
          'NEW_LINE' => ($count>1 && (($count-1) % $columns) == 0)
        ));
      }
    }

    while ($count++ % $columns != 0) {
      $cpgtpl->assign_block_vars('c_empty_cell', array(
        'COL_WIDTH' => $column_width
      ));
    }

    //Tab display
    $cpgtpl->assign_block_vars('c_tabs', array(
      'COLUMNS' => $columns,
      'TABS'    => $tabs,
    ));

    $cpgtpl->set_filenames(array('album_list_cat' => 'coppermine/album_list_cat.html'));
    $cpgtpl->display('album_list_cat',false);

    $cpgtpl->unset_block('c_album_cell');//So that albums wont start repeating
    $cpgtpl->unset_block('c_tabs');//So that category stats wont start repeating
}

function sort_selected($method) {
  return (isset($_GET['sort']) && $_GET['sort']==$method)? ' selected="selected"':'';
}

function theme_display_thumbnails(&$thumb_list, $nbThumb, $album_name, $page_link, $page, $total_pages, $sort_options, $display_tabs, $mode='thumb')
{
    global $CONFIG, $CPG_URL, $CPG_M_URL, $template_tab_display, $cop_templ, $cpgtpl, $module_name, $file;

    $theme_thumb_tab_tmpl = $template_tab_display;

    if ($mode == 'thumb') {
      $theme_thumb_tab_tmpl['left_text'] = strtr($theme_thumb_tab_tmpl['left_text'],array('{LEFT_TEXT}' => PIC_ON_PAGE));
    } else {
      $theme_thumb_tab_tmpl['left_text'] = strtr($theme_thumb_tab_tmpl['left_text'],array('{LEFT_TEXT}' => USER_ON_PAGE));
    }

    $theme_thumb_tab_tmpl['inactive_tab'] = strtr($theme_thumb_tab_tmpl['inactive_tab'],array('{LINK}' => $page_link));

    $thumbcols = $CONFIG['thumbcols'];
    $cell_width = floor(100/$CONFIG['thumbcols']).'%';

    $tabs_html = $display_tabs ? create_tabs($nbThumb, $page, $total_pages, $theme_thumb_tab_tmpl) : '';


    // The sort order options are not available for meta albums
    if ($mode == 'thumb') {
      $cpgtpl->assign_vars(array(
        'TITLE'         => $album_name,
        'THUMBCOLS'     => $thumbcols,
        'SORT_OPTIONS'  => 0,
      ));
    } else {
      $cpgtpl->assign_vars(array(
        'TITLE'         => '',
        'THUMBCOLS'     => '',
        'SORT_OPTIONS'  => 0,
      ));
    }

    if ($sort_options) {
      $hidden_input = '<input type="hidden" name="name" value="'.$module_name.'" />
        <input type="hidden" name="file" value="thumbnails" />
        <input type="hidden" name="album" value="'.intval($_GET['album']).'" />
        <input type="hidden" name="page" value="'.(isset($_GET['page']) ? intval($_GET['page']) : '1').'" />';

      $cpgtpl->assign_vars(array(
        'SORT_OPTIONS'  => 1,
        'HIDDEN_INPUT'  => $hidden_input,
        'TA_SELECTED'   => sort_selected('ta'),
        'NA_SELECTED'   => sort_selected('na'),
        'ND_SELECTED'   => sort_selected('nd'),
        'DA_SELECTED'   => sort_selected('da'),
        'DD_SELECTED'   => sort_selected('dd'),
        'RA_SELECTED'   => sort_selected('ra'),
        'RD_SELECTED'   => sort_selected('rd'),
        'SORT_TA'       => SORT_TA,
        'SORT_NA'       => SORT_NA,
        'SORT_ND'       => SORT_ND,
        'SORT_DA'       => SORT_DA,
        'SORT_DD'       => SORT_DD,
        'SORT_RA'       => SORT_RA,
        'SORT_RD'       => SORT_RD
      ));
    }

    $i = 0;
    foreach($thumb_list as $thumb) {
      $i++;
      $cpgtpl->assign_block_vars('thumb_cell', array(
        'CELL_WIDTH'    => $cell_width,
        'LINK_TGT'      => $thumb['url'],
        'THUMB'         => $thumb['image'],
        'CAPTION'       => $thumb['caption'],
        'ADMIN_MENU'    => ($mode == 'thumb') ? $thumb['admin_menu'] : '',
        'NEW_LINE'      => ($i>1 && ((($i-1) % $thumbcols) == 0))
      ));

    }

    for (;($i % $thumbcols); $i++) {
      $cpgtpl->assign_block_vars('empty_cell',array());
    }

    $cpgtpl->assign_vars(array(
    	'IS_TABS'       => $display_tabs
    ));

    if ($display_tabs) {
      $cpgtpl->assign_vars(array(
        'TABS_THUMBS'       => $tabs_html
      ));
    }

    $cpgtpl->set_filenames(array('thumbnail_view' => 'coppermine/thumbnail_view.html'));
    $cpgtpl->display('thumbnail_view',false);

    $cpgtpl->unset_block('thumb_cell');//So that the images wont start repeating
}


// Added to display film_strip
function theme_display_film_strip(&$thumb_list) {
  global $CONFIG, $CPG_URL, $CPG_M_URL, $cop_imag, $cpgtpl, $pos, $pic_count;


  $thumbcols = $CONFIG['thumbcols'];
  $cell_width = floor(100 / $CONFIG['max_film_strip_items']) . '%';
  $i = 0;
  $thumb_strip = '';

  foreach($thumb_list as $thumb) {
    $i++;
    $cpgtpl->assign_block_vars('thumb_cell',array(
      'CELL_WIDTH'  => $cell_width,
      'LINK_TGT'    => $thumb['url'],
      'THUMB'       => $thumb['image'],
      'CAPTION'     => $thumb['caption'],
      'ADMIN_MENU'  => $thumb['admin_menu'],
      'CURRENT'     => ($thumb['pos']==$pos)?' class="currentpic"':'',
      'PIC_POSITION'=> sprintf(PIC_POS, $thumb['pos']+1, $pic_count)
    ));
  }

  $cpgtpl->assign_vars(array(
    //'COLS'        => $i,
    'TILE_IMAGE'  => $cop_imag.'/tile.gif'
  ));

  $cpgtpl->set_filenames(array('film_strip' => 'coppermine/display_image/film_strip.html'));

  if (version_compare(CPG_NUKE, '10.0.0', '>=')) {
    return $cpgtpl->to_string('film_strip');
  } else {
    ob_start();
    $cpgtpl->display('film_strip');
    return ob_get_clean();
  }
}

function theme_no_img_to_display($album_name) {
  global $cpgtpl;

  $cpgtpl->assign_vars(array(
    'ALBUM_NAME'        => $album_name,
    'NO_IMG_TO_DISPLAY' => NO_IMG_TO_DISPLAY
  ));

  $cpgtpl->set_filenames(array('no_img_to_display' => 'coppermine/no_img_to_display.html'));
  $cpgtpl->display('no_img_to_display');
}

function theme_display_image($nav_menu, $picture, $votes, $pic_info, $comments, $film_strip) {
  global $CONFIG, $cpgtpl;

  $cpgtpl->assign_vars(array(
    'NAV_MENU'    => $nav_menu,
    'PICTURE'     => $picture,
    'FILM_STRIP'  => ($CONFIG['display_film_strip'] == 1)? $film_strip : '',
    'VOTES'       => $votes,
    'PIC_INFO'    => $pic_info,
    'COMMENTS'    => $comments,
  ));

  $cpgtpl->set_filenames(array('display_image' => 'coppermine/display_image.html'));
  $cpgtpl->display('display_image');
}

function theme_html_picinfo(&$info) {
  global $HTTP_COOKIE_VARS, $CONFIG, $cpgtpl;

  $infovisible = isset($HTTP_COOKIE_VARS['picinfo']) ? $HTTP_COOKIE_VARS['picinfo'] : ($CONFIG['display_pic_info'] ? 'block' : 'none');

  $cpgtpl->assign_vars(array(
    'PIC_INF_TITLE'   => PIC_INF_TITLE,
    'INFOVISIBLE'     => $infovisible
  ));

  foreach ($info as $key => $value) {
    $cpgtpl->assign_block_vars('info',array(
      'KEY'       => $key,
      'VALUE'     => $value
    ));
  }

  $cpgtpl->set_filenames(array('pic_info' => 'coppermine/display_image/pic_info.html'));

  if (version_compare(CPG_NUKE, '10.0.0', '>=')) {
    return $cpgtpl->to_string('pic_info');
  } else {
    ob_start();
    $cpgtpl->display('pic_info');
    return ob_get_clean();
  }
}
