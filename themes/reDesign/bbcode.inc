<?php
/*********************************************
  CPG Dragonfly™ CMS
  ********************************************
  Copyright © 2004 - 2009 by CPG-Nuke Dev Team
  http://dragonflycms.org

  Dragonfly is released under the terms and conditions
  of the GNU GPL version 2 or any later version

  $Source: /cvs/themes/reDesign/bbcode.inc,v $
  $Revision: 1.8 $
  $Author: estlane $
  $Date: 2009/10/29 12:39:53 $
**********************************************/
if (!defined('CPG_NUKE')) { exit; }

/* Usual quote */
$bb_codes['quote'] = '
<blockquote>
  <div>';

$bb_codes['quote_name'] = '
<blockquote>
  <div class="name">- \\1</div>
  <div>';
  
$bb_codes['quote_close'] = '</div>
</blockquote>';


/* Usual code */
$bb_codes['code_start'] = '<div class="bbcode">
  <div class="bbchead">'.$bbcode_common['code'][0].'</div>
  <div class="bbccontent">';
$bb_codes['code_end'] =  '</div></div>';

/* PHP Code */
$bb_codes['php_start'] = '<div class="bbphp">
  <div class="bbphphead">'.$bbcode_common['php'][0].'</div>
  <div class="bbphpcontent">';
$bb_codes['php_end'] = '</div></div>';
