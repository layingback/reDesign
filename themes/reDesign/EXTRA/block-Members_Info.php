<?php
/*********************************************
  CPG Dragonfly™ CMS
  ********************************************
  Copyright © 2004 - 2008 by CPG-Nuke Dev Team
  http://dragonflycms.org

  Based on "All Info Block" by Alex Hession
   http://www.gnaunited.com
  Block heavily modified by DJMaze
   http://www.cpgnuke.com
  Then reedited by Madis aka Eestlane (works right only with reDesign based themes)

  Dragonfly is released under the terms and conditions
  of the GNU GPL version 2 or any later version

  $Source: /cvs/themes/reDesign/EXTRA/block-Members_Info.php,v $
  $Revision: 1.1 $
  $Author: estlane $
  $Date: 2008/11/19 14:58:42 $
Encoding test: n-array summation ∑ latin ae w/ acute ǽ
********************************************************/
if (!defined('CPG_NUKE')) { exit; }

global $prefix, $user_prefix, $db, $userinfo;
$content = '';

$memres = $db->sql_query('SELECT s.uname, s.module, s.url, u.user_allow_viewonline FROM '.$prefix.'_session AS s LEFT JOIN '.$user_prefix.'_users AS u ON u.username=s.uname WHERE guest=0 OR guest=2 ORDER BY s.uname');
$anonres = $db->sql_query('SELECT module, url FROM '.$prefix.'_session WHERE guest=1');
$online_num[0] = $db->sql_numrows($memres);
$online_num[1] = $db->sql_numrows($anonres);
$online_num[2] = $online_num[0] + $online_num[1];
$who_where = array('', '');
$hidden = 0;

$who_where[0] = '<ul>';
for ($i = 1; $i <= $online_num[0]; $i++) {
  $onluser = $db->sql_fetchrow($memres);
  if ($onluser['user_allow_viewonline'] || is_admin()) {
    $ttt = '<li><a href="'.getlink('Your_Account&amp;profile='.$onluser['uname']).'">';
    $ttt .= $onluser['user_allow_viewonline']? $onluser['uname'] : '<i>'.$onluser['uname'].'</i>';
    $who_where[0] .= $ttt.'</a> &gt; <a href="'.$onluser['url'].'">'.(defined($onluser['module'])?constant($onluser['module']):$onluser['module']).'</a></li>'."\n";
  } else {
    $hidden++;
  }
}
$db->sql_freeresult($memres);
$who_where[0] .= "</ul>\n";

$who_where[1] = '<ul>';
for ($i = 1; $i <= $online_num[1]; $i++) {
  $onluser = $db->sql_fetchrow($anonres);
  $who_where[1] .= '<li><a href="'.$onluser['url'].'">'.(defined($onluser['module'])?constant($onluser['module']):$onluser['module']).'</a></li>'."\n";
}
$db->sql_freeresult($anonres);
$who_where[1] .= "</ul>\n";

$day = L10NTime::tolocal((mktime(0,0,0,date('n'),date('j'),date('Y'))-date('Z')), $userinfo['user_dst'], $userinfo['user_timezone']);

list($last[0]) = $db->sql_ufetchrow("SELECT COUNT(*) FROM ".$user_prefix."_users WHERE user_regdate>='".$day."'", SQL_NUM);
list($last[1]) = $db->sql_ufetchrow("SELECT COUNT(*) FROM ".$user_prefix."_users WHERE user_regdate<'".$day."' AND user_regdate>='".($day-86400)."'", SQL_NUM);
list($lastuser) = $db->sql_ufetchrow("SELECT username FROM ".$user_prefix."_users WHERE user_active = 1 AND user_level > 0 ORDER BY user_id DESC LIMIT 1", SQL_NUM);
list($numusers) = $db->sql_ufetchrow("SELECT COUNT(*) FROM ".$user_prefix."_users WHERE user_id > 1 AND user_level > 0", SQL_NUM);

$content .= '<dl class="compact_userinfo">
<dt>'._BMEMP.':</dt>
<dd>'._BLATEST.': <b><a href="'.getlink('Your_Account&amp;profile='.$lastuser).'">'.$lastuser.'</a></b></dd>
<dd>'._BTD.': <b>'.$last[0].'</b></dd>
<dd>'._BYD.': <b>'.$last[1].'</b></dd>
<dd>'._BOVER.': <b>'.$numusers.'</b></dd>
<dt>'._BVISIT.':</dt>
<dd>'._BMEM.': <b>'.$online_num[0].'</b></dd>
<dd>'._BVIS.': <b>'.$online_num[1].'</b></dd>
<dd>'._BTT.': <b>'.$online_num[2].'</b></dd>
<dt>'._WHOWHERE.":</dt>\n";
if($online_num[0] > 0) { $content .= '<dd>'._BMEM.":\n".$who_where[0]."</dd>\n"; }
if($online_num[1] > 0) { $content .= '<dd>'._BVIS.":\n".$who_where[1]."</dd>\n"; }
if ($hidden > 0) { $content .= '<dd>'._BHID.': '.$hidden."</dd>\n"; }

if (is_admin()) { $content .= '<dt class="admin_logout"><a href="'.adminlink('logout').'">'._LOGOUTADMINACCT."</a></dt>\n"; }
$content .= "</dl>\n";
