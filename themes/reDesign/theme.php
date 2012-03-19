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
$Author: estlane $
$Date: 2010/03/22 21:39:48 $
$Version: 9.2.3.0
 **********************************************/
if (!defined('CPG_NUKE')) { exit; }

//Required minimum Dragonfly version
define('THEME_VERSION', '9.2');

// You will probably want to set only one position to 1 below, except if you're using some custom multibanner system

// If banners are enabled, would you like to see them inside your header? 1 for yes, 0 for false
// Is okay for 468x60 - any bigger might not fit well
define('BANNER_HEADER', 0);

// If banners are enabled, would you like to see them after your header? 1 for yes, 0 for false
// Usually fits best for really huge banners
define('BANNER_AFTER_HEADER', 0);

// If banners are enabled, would you like to see them between side blocks in main? 1 for yes, 0 for false
// Best if only one or less block sides are enabled
define('BANNER_MAIN', 0);

// If banners are enabled, would you like to see them in footer? 1 for yes, 0 for false
// Safe bet - should always fit
define('BANNER_FOOTER', 1);


/* Text color on security image, THIS_THEME/images/code_bg.png */
$gfxcolor = '#000000';

/* Some background colours used by DF */
$bgcolor1 = '#FFFFFF'; // modules admin
$bgcolor2 = '#E8E7E1'; // topics
$bgcolor3 = '#E5E5E5'; // topics
$bgcolor4 = '#D7FFD7'; // modules admin
$textcolor1 = '#009900';
$textcolor2 = '#000000';

$hr = 1; /*deprecated? (not used in DF 10) */

/* Beginning of an usual table of untemplated modules */
function OpenTable() {
    global $no_border;

    //$no_border : coppermine/theme.inc and template/forums/images.cfg

    if (isset($no_border)) {
        echo '<div>';
    } else {
        echo '<div class="table1">';
    }
}


/* End of an usual table of untemplated modules */
function CloseTable() {
    echo '</div>';
}


/* Beginning of some subtable in untemplated modules */
function OpenTable2() {
    echo '<div class="table2">';
}


/* End of some subtable in untemplated modules */
function CloseTable2() {
    echo '</div>';
}


/* Current time, depends what df version is used */
function current_time() {
    //Dragonfly 10+
    if (version_compare(CPG_NUKE, '10.0.0', '>=')) {
        return time();
        //Older
    } else {
        return gmtime();
    }
}

function themeheader() {
    global $slogan, $sitename, $banners, $mainindex, $adminindex, $cpgtpl, $site_logo, $BASEHREF, $CPG_SESS,
    $MAIN_CFG, $userinfo, $module_name, $home, $Blocks, $multilingual, $currentlang, $show_banner_in_header, $file;

    // Show following page if site is maintenance mode and non-admin is viewing it
    if ($MAIN_CFG['global']['maintenance'] && !is_admin()) {
        echo '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
      "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
    <html xmlns="http://www.w3.org/1999/xhtml" dir="'._TEXT_DIR.'" xml:lang="'._BROWSER_LANGCODE.'" lang="'._BROWSER_LANGCODE.'">
      <head>
        <title>'.$sitename.'</title>
      </head>
      <body>
        <strong>'._SYS_MESSAGE.'</strong><br />'._SYS_MAINTENANCE.'
      </body>
    </html>';
        return;
    }



    /* CSS Menu */
    if ($MAIN_CFG['global']['admingraphic'] & 4) {
        include('themes/'.$CPG_SESS['theme'].'/includes/cssmainmenu.php');
    }

    //include file to have proper language selection URL (or prevtheme)
    require_once('themes/'.$CPG_SESS['theme'].'/includes/show_langs.php');
    if ($multilingual) show_langs();//Make languageselection array if site is multilingual

    $user_agent = isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : '';
    $pngfix = 0;
    $specific = 0;
    if (preg_match('#MSIE ([0-6].[0-9]{1,2})#', $user_agent)) {
        $specific = 'ie6.css';
        $pngfix = 1;
    } else if (preg_match('#Opera/([0-9].[0-2][0-9])#', $user_agent)) {
        $specific = 'opera_merlin.css';
    } else if (isset($_SESSION['SECURITY']['UA']) && $_SESSION['SECURITY']['UA'] == 'Safari') {
        $specific = 'safari.css';
    }


    //We want to use some strings, which exists only in forums.php languagefile, it may cause a small downtime though
    if (!isset($lang['Back_to_top'])) {
        get_lang('forums');
        global $lang;
    }
    if (!defined('_PASSWORDLOST')) { get_lang('your_account'); }

    $modspecific_css = '';

    if ($module_name == 'Members_List') {
        $modspecific_css = 'memberslist.css';
    }

    if ($modspecific_css) {
        $modspecific_css = '<link rel="stylesheet" type="text/css" media="screen" href="themes/'.$CPG_SESS['theme'].'/style/modules/'.$modspecific_css.'" />';
    }

    /*Add left blocks to header.html*/
    $Blocks->display('l');
    /*Add right blocks to header.html*/
    $Blocks->display('r');


    $imageLeft = $imageRight = '';
    // left blocks ?
    if ($Blocks->l && ($Blocks->showblocks & 1)) {
        $img = $Blocks->hideblock('600') ? 'plus.png' : 'minus.png';
        $imageLeft = '<img alt="+/-" title="'._TOGGLE.'" id="pic600" src="themes/'.$CPG_SESS['theme'].'/images/'.$img.'" onclick="blockswitch(\'600\');" style="cursor:pointer; float:left; padding:2px 0 2px 0;" />';
    }
    // right blocks ?
    if ($Blocks->r && ($Blocks->showblocks & 2)) {
        $img = $Blocks->hideblock('601') ? 'plus.png' : 'minus.png';
        $imageRight = '<img alt="+/-" title="'._TOGGLE.'" id="pic601" src="themes/'.$CPG_SESS['theme'].'/images/'.$img.'" onclick="blockswitch(\'601\');" style="cursor:pointer; float:right; padding:2px 0 2px 0;" />';
    }

    $cpgtpl->assign_vars(array(
        'BROWSER_CSS'		=> $specific ? "\n".'<link rel="stylesheet" type="text/css" href="themes/'.$CPG_SESS['theme'].'/style/browsers/'.$specific.'" />'."\n" : "\n",
        'PNG_FIX'			=> $pngfix,
        'PUBLIC_HEADER'		=> !defined('ADMIN_PAGES'),
        'CURRENT_URL'		=> ereg_replace('&','&amp;',get_uri()),
        'ADM_L_PLUSMINUS'	=> defined('ADMIN_PAGES')? $imageLeft : '',
        'ADM_R_PLUSMINUS'	=> defined('ADMIN_PAGES')? $imageRight : '',
        'ADM_L_VISIBILITY'	=> (defined('ADMIN_PAGES') && $Blocks->hideblock('600')) ? ' style=" display: none"' : '',
        'ADM_R_VISIBILITY'	=> (defined('ADMIN_PAGES') && $Blocks->hideblock('601')) ? ' style=" display: none"' : '',
        'S_MAIN_MENU'		=> isset($mmcontent) ? $mmcontent : false,
        'S_IS_ADMIN'		=> is_admin(),
        'S_CAN_ADMIN'		=> can_admin(),
        'S_IS_USER'			=> is_user(),
        'B_ANONYMOUS'		=> !is_user(),
        'S_NEW_PM'			=> is_user() && is_active('Private_Messages') && ($userinfo['user_new_privmsg'] > 0)?(($userinfo['user_new_privmsg']>1)?$lang['You_new_pms']:$lang['You_new_pm']):false,
        'S_LOGO'			=> $site_logo,
        'S_SITENAME'		=> $sitename,
        'S_USER_NAME'		=> $userinfo['username'],
        'S_LOGINRDRT'		=> (isset($CPG_SESS['user']['redirect']) ? $CPG_SESS['user']['redirect'] : getlink()),
        'S_REG_ALLOWED'		=> $MAIN_CFG['member']['allowuserreg'],
        'S_NOT_NEWS'		=> ($module_name != 'News') || $home,
        'CUR_LANGUAGE'		=> $currentlang,
        'S_FORUMS'			=> _ForumsLANG,
        'S_DOWNLOADS'		=> is_active('Downloads') ? _DownloadsLANG : false,
        'S_MY_ACCOUNT'		=> is_user() ? _Your_AccountLANG : _BREG,
        'S_ADMINISTR'		=> _ADMINISTRATION,
        'BC_DELIM'			=> _BC_DELIM,
        'S_BANNER_ACT'		=> $banners,
        'S_BANNER'			=> ($banners) ? viewbanner() : '',
        'S_EDIT'			=> _EDIT,
        'S_MULTILANG'		=> $multilingual,
        'S_SEARCH_ACT'		=> is_active('Search'),
        'U_SEARCH'			=> getlink('Search'),
        'U_MAININDEX'		=> $mainindex,
        'U_LOGOUT'			=> getlink('Your_Account&amp;op=logout&amp;redirect'),
        'U_REGISTER'		=> getlink('Your_Account&amp;file=register'),
        'U_PASSLOSS'		=> getlink('Your_Account&amp;op=pass_lost'),
        'U_DOWNLOADS'		=> getlink('Downloads'),
        'U_FORUMS'			=> getlink('Forums'),
        'U_LOG_IN'			=> getlink('Your_Account'),
        'U_MY_ACCOUNT'		=> getlink(is_user() ? 'Your_Account' : 'Your_Account&amp;file=register'),
        'U_PM'				=> getlink('Private_Messages'),
        'U_ADMININDEX'		=> $adminindex,
        'U_NEWSADMIN'		=> adminlink('News&amp;edit='),
        'S_LASTVISIT'		=> is_user() ? sprintf($lang['You_last_visit'], formatDateTime($userinfo['user_lastvisit'], _DATESTRING)) : '',
        'S_TIMENOW'			=> sprintf($lang['Current_time'], formatDateTime(current_time(), _DATESTRING)),
        'S_NEW_POSTS'		=> $lang['New_posts'],
        'S_CLOSE_WIN'		=> $lang['Close_window'],
        'BACK_TO_TOP'		=> $lang['Back_to_top'],
        'PM_IMAGE'			=> 'themes/'.$CPG_SESS['theme'].'/images/forums/lang_'.$currentlang.'/icon_contact_pm.gif',
        'WWW_IMAGE'			=> 'themes/'.$CPG_SESS['theme'].'/images/forums/lang_'.$currentlang.'/icon_contact_www.gif',
        'BASE_URL'			=> $BASEHREF,
        'HEADVARS_OK'		=> 1, //makes possible to check if we need to reassign some vars in footer if this is false
        'B_DRAGON_10'		=> version_compare(CPG_NUKE, '10.0.0', '>='), //If Dragonfly version is 10+
        'S_MODULE_SPECIFIC_CSS' => $modspecific_css,
        'IS_RTL'			=> (_TEXT_DIR == 'rtl'),
        'BANNER_HEADER'		=> (BANNER_HEADER && $banners),
        'BANNER_AFTER_HEADER'	=> (BANNER_AFTER_HEADER && $banners),
        'BANNER_MAIN'		=> (BANNER_MAIN && $banners),
        'BANNER_FOOTER'		=> (BANNER_FOOTER && $banners),
    ));


    makeLinkableBreadcrumb();
}


// Calculate if the global breadcrumb will be disabled or not
function setBreadcrumbState() {
    global $breadcrumbDisabled, $module_name;

    // Modules that are not allowed to have global page title (true)
    //  false means that the module MUST have the global page title and thus overrides previous $breadcrumbDisabled (i.e Groups included this via Forum images file)
    $disallowedModules = array(
        'Wiki'				=> true,
        'Groups'			=> false,
        'Private_Messages'	=> false,
    );

    // Is the module not allowed to have global page title?
    if (isset($disallowedModules[$module_name])) {
        if ($disallowedModules[$module_name]) {
            $breadcrumbDisabled = true;
            // Overrides previous $breadcrumbDisabled (i.e for Groups)
        } else {
            $breadcrumbDisabled = false;
        }
    }
}


// Calculate if the global pagetitle will be disabled or not
function setPagetitleState() {
    global $pagetitleDisabled, $module_name;

    // Modules that are not allowed to have global page title (true)
    //  false means that the module MUST have the global page title and thus overrides previous $breadcrumbDisabled (i.e Groups included this via Forum images file)
    $disallowedModules = array(
        'Wiki'				=> true,
        'Groups'			=> false,
        'Private_Messages'	=> false,
    );

    // Is the module not allowed to have global page title?
    if (isset($disallowedModules[$module_name])) {
        if ($disallowedModules[$module_name]) {
            $pagetitleDisabled = true;
            // Overrides previous $breadcrumbDisabled (i.e for Groups)
        } else {
            $pagetitleDisabled = false;
        }
    }
}


/**
 * Can even be used in templates
 */
function getBreadCrumbTraces($input) {
    global $mainindex, $breadcrumbDisabled, $cpgtpl, $home;

    // Before anything uses $breadcrumbDisabled
    setPagetitleState();
    setBreadcrumbState();

    // Traces[index]: [0]=title [1]=url(optional)
    $traces = array();

    // If modules uses it's own breadcrumb instead (e.g forums)

    // We can give home trace even if no pagetitle is given
    //$traces[] = array(_HOME, $mainindex);
    if ($home) {
        //$traces[] = array(_HOME);
    } else {
        $traces[] = array(_HOME, $mainindex);
    }

    // If there is a pagetitle
    if (!$home && $input) {
        // Now we need to make existing $pagetitle into pieces by breadcrumb separator
        $titleParts = explode(_BC_DELIM, $input);

        foreach ($titleParts as $part) {
            // part could be link or not

            //Strip all tags except <a>
            $part = strip_tags($part, '<a>');
            $part = trim($part);

            $title = strip_tags($part);

            //Separate URL (if there is one) and title
            preg_match('/href="([^"]+)"/i', $part, $partAnchor);
            if ($partAnchor && $partAnchor[1]) {
                $url = $partAnchor[1];
                $traces[] = array($title, $url);
            } else {
                $traces[] = array($title);
            }
        }
    }

    return $traces;
}


/**
 * Makes pagetitle into parts and makes well templatable breadcrumb out of it.
 * Should be called before or in themeheader.
 */
function makeLinkableBreadcrumb() {
    global $pagetitle, $mainindex, $breadcrumbDisabled, $pagetitleDisabled, $cpgtpl, $home;

    // Before anything uses $pagetitleDisabled and $breadcrumbDisabled
    setPagetitleState();
    setBreadcrumbState();

    // If there is a pagetitle
    if (isset($pagetitle) && !empty($pagetitle)) {
        $traces = getBreadCrumbTraces($pagetitle);

        $tracesLastKey = count($traces)-1;
        foreach ($traces as $key => &$part) {
            // If global breadcrumb is not disabled for current module
            if (!isset($breadcrumbDisabled) || !$breadcrumbDisabled) {
                $cpgtpl->assign_block_vars('trace', array(
                    'TITLE'			=> $part[0],
                    'HAS_URL'		=> isset($part[1]),
                    'URL'			=> isset($part[1]) ? $part[1] : '',
                    'IS_LAST'		=> $key == $tracesLastKey,
                ));
            }
        }
    }

    // If pagetitle is disabled, then we also MUST disable breadrumb, or a custom pagetitle would be written after breadcrumb
    if (isset($pagetitleDisabled) && $pagetitleDisabled) {
        $cpgtpl->assign_vars(array(
            'SHORT_PAGETITLE'	=> false,
            'IS_BREADCRUMB'		=> false,
        ));
    } else {
        $cpgtpl->assign_vars(array(
            'SHORT_PAGETITLE'	=> (isset($part) && !$home && $pagetitle)? $part[0] : '',
            'IS_BREADCRUMB'		=> (!isset($breadcrumbDisabled) || !$breadcrumbDisabled) && isset($part),
        ));
    }
}


function themefooter() {
    global $MAIN_CFG, $cpgtpl, $banners, $Blocks, $db;

    if ($MAIN_CFG['global']['maintenance'] && !is_admin()) {
        echo '</body></html>';
        return;
    }


    global $foot1, $foot2, $foot3;

    //dummy require of the global, or else $footer_1... will be empty later
    // TODO: need to test a bit more if this line is needed
    Fix_Quotes($foot1, 1);


    // Let's save it before footmsg() adds other pieces into it
    $footer_1 = $foot1;
    // Let's also save others
    $footer_2 = $foot2;
    $footer_3 = $foot3;

    // Now let's clean all foot globals, so footmsg() would add minimum info to $foot1
    $foot1 = '';
    $foot2 = '';
    $foot3 = '';

    $debug_sql_id = '31881';
    $debug_php_id = '31949';

    $cpgtpl->assign_vars(array(
        'S_BANNER_ACT'			=> $banners,
        'S_BANNER'				=> ($banners) ? viewbanner() : '',
        'S_FOOTER'				=> footmsg(),
        'FOOTER_1'				=> $footer_1,
        'FOOTER_2'				=> $footer_2,
        'FOOTER_3'				=> $footer_3,
        'DSQL_ID'				=> $debug_sql_id,
        'DPHP_ID'				=> $debug_php_id,
        'S_DEBUG_SQL_COLLAPSED'	=> $Blocks->hideblock($debug_sql_id),
        'S_DEBUG_PHP_COLLAPSED'	=> $Blocks->hideblock($debug_php_id),
        'U_EDIT_FOOTER'			=> is_admin() ? adminlink('settings&amp;s=3') : '',
    ));



    /*
      In case the template array gets emptied, i.e during forums admin messages
      We assign some again, that are also used in footer
    */

    //Dragonfly 10 or load only if needed
    //if (version_compare(CPG_NUKE, '10.0.0', '>=') || !isset($cpgtpl->_tpldata['.'][0]['HEADVARS_OK'])) {
    if (version_compare(CPG_NUKE, '10.0.0', '<') && !isset($cpgtpl->_tpldata['.'][0]['HEADVARS_OK'])) {

        global $CPG_SESS, $mainindex, $adminindex, $lang, $userinfo;

        /* CSS Menu */
        if ($MAIN_CFG['global']['admingraphic'] & 4) {
            include('themes/'.$CPG_SESS['theme'].'/includes/cssmainmenu.php');
        }

        $cpgtpl->assign_vars(array(
            'CURRENT_URL'		=> ereg_replace('&','&amp;',get_uri()),
            'S_TIMENOW'		=> sprintf($lang['Current_time'], formatDateTime(current_time(), _DATESTRING)),
            'S_IS_USER'		=> is_user(),
            'S_LASTVISIT'		=> is_user() ? sprintf($lang['You_last_visit'], formatDateTime($userinfo['user_lastvisit'], _DATESTRING)) : '',
            'BACK_TO_TOP'		=> $lang['Back_to_top'],
            'S_MAIN_MENU'		=> isset($mmcontent) ? $mmcontent : false,
            'U_MAININDEX'		=> $mainindex,
            'U_DOWNLOADS'		=> getlink('Downloads'),
            'U_FORUMS'		=> getlink('Forums'),
            'U_MY_ACCOUNT'	=> getlink(is_user() ? 'Your_Account' : 'Your_Account&amp;file=register'),
            'U_ADMININDEX'	=> $adminindex,
            'S_DOWNLOADS'		=> is_active('Downloads') ? _DownloadsLANG : false,
            'S_FORUMS'		=> _ForumsLANG,
            'S_MY_ACCOUNT'	=> is_user() ? _Your_AccountLANG : _BREG,
            'S_ADMINISTR'		=> _ADMINISTRATION
        ));
    }


    $cpgtpl->set_filenames(array('footer' => 'footer.html'));
    $cpgtpl->display('footer');
}




/***********************************************************************************

string theme_open_form

Creates start tag for form
$get_link : link for action default blank
$form_name : useful for styling and nbbcode
$legend: optional string value is used in form lagend tag
$border: optional use 1 to not show border on fieldset from stylesheet
 ************************************************************************************/
function theme_open_form($link, $form_name=false, $legend=false,$tborder=false) {
    $leg = $legend ? '<legend>'.$legend.'</legend>' : '';
    $bord = $tborder ? $tborder : '';
    $form_name  = $form_name ? ' id="'.$form_name.'"' :'';
    return '<form method="post" action="'.$link.'"'.$form_name.' enctype="multipart/form-data" accept-charset="utf-8"><fieldset '.$bord.'>'.$leg;
}

function theme_close_form() {
    return '</fieldset></form>';
}



/***********************************************************************************

string theme_yesno_option

Creates 2 radio buttons with a Yes and No option
$name : name for the <input>
$value: current value, 1 = yes, 0 = no

 ************************************************************************************/
function theme_yesno_option($name, $value=0) {

    $sel = array('','');
    $sel[$value] = ' checked="checked"';

    $select = '<input type="radio" name="'.$name.'" id="'.$name.'_yes" value="1"'.$sel[1].' />
    <label class="rdr" for="'.$name.'_yes">'._YES.'</label>
    <input type="radio" name="'.$name.'" id="'.$name.'_no" value="0" '.$sel[0].' />
    <label class="rd" for="'.$name.'_no">'._NO.'</label> ';
    return $select;
}




/***********************************************************************************

string theme_select_option

Creates a selection dropdown box of all given variables in the array
$name : name for the <select>
$value: current/default value
$array: array like array("value1","value2")

 ************************************************************************************/
function theme_select_option($name, $value, $array) {
    $sel[$value] = ' selected="selected"';
    $select = '<select class="set" name="'.$name.'" id="'.$name."\">\n";
    foreach($array as $var) {
        $select .= '<option'.(isset($sel[$var])?$sel[$var]:'').'>'.$var."</option>\n";
    }
    return $select.'</select>';
}



/***********************************************************************************

string theme_select_box

Creates a selection dropdown box of all given variables in the multi array
$name : name for the <select>
$value: current/default value
$array: array like array("value1 => title1","value2 => title2")

 ************************************************************************************/
function theme_select_box($name, $value, $array) {
    $select = '<select class="set" name="'.$name.'" id="'.$name."\">\n";
    foreach($array as $val => $title) {
        $select .= '<option value="'.$val.'"'.(($val==$value)? ' selected="selected"' : '').'>'.$title."</option>\n";
    }
    return $select.'</select>';
}
