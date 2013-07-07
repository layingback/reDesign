<?php
/*********************************************
  CPG Dragonfly™ CMS
  ********************************************
  Copyright © 2004 - 2009 by CPG-Nuke Dev Team
  http://dragonflycms.org

  Dragonfly is released under the terms and conditions
  of the GNU GPL version 2 or any later version

  Modified Copyright © 2012 by layingback
    for rD4 nbbcode text width independent of img

  $Source: /cvs/themes/reDesign/bbcode.inc,v $
  $Revision: 1.8 $
  $Author: estlane $
  $Date: 2009/10/29 12:39:53 $
**********************************************/
if (!defined('CPG_NUKE')) { exit; }

global $module_name;
$para_start = '';
$para_end = '';

// This restriction to Forums only can be relaxed or removed, but XHTML errors will occur
// if older modules or templates still use tables for formatting (in place of divs)
// as <p>...</p> is not allowed within tables.
if ($module_name == 'Forums') {
	$para_start = '<p>';
	$para_end = '<!-- --></p>';		/* HTML comment is to prevent Tidy Trimming Empty Tag warning */
}

/* Usual quote */
	$bb_codes['quote'] = $para_end.'
	<blockquote>
	  <div><p>';

	$bb_codes['quote_name'] = $para_end.'
	<blockquote>
	  <div class="name">- \\1</div>
	  <div>'.$para_start;

	$bb_codes['quote_close'] = $para_end.'</div>
	</blockquote>'.$para_start;


/* Usual code */
	$bb_codes['code_start'] = $para_end.'<div class="bbcode">
	  <div class="bbchead">'.$bbcode_common['code'][0].'</div>
	  <div class="bbccontent">';
	$bb_codes['code_end'] =  '</div></div>'.$para_start;

/* PHP Code */
	$bb_codes['php_start'] = $para_end.'<div class="bbphp">
	  <div class="bbphphead">'.$bbcode_common['php'][0].'</div>
	  <div class="bbphpcontent">';
	$bb_codes['php_end'] = '</div></div>'.$para_start;



/* New text wrap code - lb */
	$bb_codes['text_start'] = $para_start;
	$bb_codes['text_end'] = $para_end;

/* Align */
	if ($module_name == 'Forums') {
		$bb_codes['align_start'] = $para_end;
		$bb_codes['align_end'] = $para_start;
	} else {
		$bb_codes['align_start'] = '';
		$bb_codes['align_end'] = '';
	}
