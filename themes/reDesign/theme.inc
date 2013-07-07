<?php
/*********************************************
CPG Dragonfly™ CMS
 ********************************************
Copyright © 2004 - 2009 by CPG-Nuke Dev Team
http://dragonflycms.org

Dragonfly is released under the terms and conditions
of the GNU GPL version 2 or any later version

$Source: /cvs/themes/reDesign/theme.php,v $
$Revision: 1.78 $
$Author: layingback $
$Date: 2010/03/22 21:39:48 $
$Version: 9.2.3.0
 **********************************************/
if (!defined('CPG_NUKE')) { exit; }

function get_user_avatar($username) {
	global $cpgtpl, $MAIN_CFG, $CPG_SESS;
	$commenterinfo = getusrdata($username,'user_avatar_type,user_avatar');
	if ($commenterinfo['user_avatar_type'] == 1) {
		$avatar = $MAIN_CFG['avatar']['path'].'/'.$commenterinfo['user_avatar'];
	} else if ($commenterinfo['user_avatar_type'] == 2) {
		$avatar = $commenterinfo['user_avatar'];
	} else if ($commenterinfo['user_avatar_type'] == 3 && !empty($commenterinfo['user_avatar']) && $commenterinfo['user_avatar'] != 'gallery/blank.gif') {
		$avatar = $MAIN_CFG['avatar']['gallery_path'].'/'.$commenterinfo['user_avatar'];
	} else if (file_exists('themes/'.$CPG_SESS['theme'].'/'.$MAIN_CFG['avatar']['gallery_path'].'/'.$MAIN_CFG['avatar']['default'])) {
		$avatar = 'themes/'.$CPG_SESS['theme'].'/'.$MAIN_CFG['avatar']['gallery_path'].'/'.$MAIN_CFG['avatar']['default'];
	} else {
		$avatar = $MAIN_CFG['avatar']['gallery_path'].'/'.$MAIN_CFG['avatar']['default'];
	}
	$avatar_dimn = getimagesize($avatar);
	$cpgtpl->assign_vars(array(
		  'S_AVATAR_IMG' => $avatar,
		  'G_LRG_AVATAR' => ($avatar_dimn[1] >= '80' ? 'lrg_avatar' : 'sml_avatar')
	));
	return;
}
