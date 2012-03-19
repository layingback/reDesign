<?php
if (!defined('CPG_NUKE')) { exit; }

/*
Gives proper link without changing current url.
Can be used for language or theme etc selection, i.e:
  url_from_varval('prevtheme','dragonfly');
  url_from_varval('newlang','english');
*/
function url_from_varval($variable,$value) {
  global $mainindex, $adminindex, $home, $op;

  $equalling = '';

  foreach($_GET as $var => $val) {
    if ($var != 'newlang' && $var != 'name' && !($var == 'file' && $val == 'index')) {
      $equalling .= htmlspecialchars($var).'='.htmlspecialchars($val).'&amp;';
    }
  }

  $equalling .= $variable.'='.$value;

  //If under admin pages
  if (defined('ADMIN_PAGES')) {
    //If on admin index page
    if ($op == 'index') {
      $variable_url = $adminindex.'?'.$equalling;
    //If not on admin index page, but still under admin pages
    } else {
      $variable_url = adminlink('&amp;'.$equalling);
    }
  //If on site home page
  } else if ($home) {
    $variable_url = $mainindex.'?'.$equalling;
  //If on some module etc
  } else {
    $variable_url = getlink('&amp;'.$equalling);
  }

  return $variable_url;
}
