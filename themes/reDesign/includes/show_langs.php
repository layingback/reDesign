<?php
if (!defined('CPG_NUKE')) { exit; }

function show_langs() {
  global $currentlang, $cpgtpl;

  $langsel = array(
    'afrikaans'   => 'Afrikaans',
    'albanian'    => 'Shqip',
    'arabic'      => 'عربي',
    'basque'      => 'Basque',
    'bosanski'    => 'Bosanski',
    'brazilian'   => 'Brazilian Português',
    'bulgarian'   => 'Български',
    'castellano'  => 'Castellano',
    'czech'       => 'Český',
    'danish'      => 'Dansk',
    'desi'        => 'Desi',
    'dutch'       => 'Nederlands',
    'english'     => 'English',
    'estonian'    => 'Eesti',
    'farsi'       => 'پارسى',
    'finnish'     => 'Suomi',
    'french'      => 'Français',
    'galego'      => 'Galego',
    'german'      => 'Deutsch',
    'greek'       => 'Ελληνικά',
    'hindi'       => 'हिंदी',
    'hungarian'   => 'Magyarul',
    'icelandic'   => 'Íslenska',
    'indonesian'  => 'Bahasa Indonesia',
    'italian'     => 'Italiano',
    'japanese'    => '日本語',
    'korean'      => '한국어',
    'kurdish'     => 'Kurdî',
    'latvian'     => 'Latvisks',
    'lithuanian'  => 'Lietuvių',
    'macedonian'  => 'македонски',
    'melayu'      => 'Melay',
    'norwegian'   => 'Norsk',
    'polish'      => 'Polski',
    'portuguese'  => 'Português',
    'romanian'    => 'Româneste',
    'russian'     => 'РУССКИЙ',
    'serbian'     => 'Srpski',
    'slovak'      => 'Slovenský',
    'slovenian'   => 'Slovenščina',
    'spanish'     => 'Espanõl',
    'swahili'     => 'Kiswahili',
    'swedish'     => 'Svensk',
    'thai'        => 'ไทย',
    'turkish'     => 'Türkçe',
    'uighur'      => 'Uyghurche',
    'ukrainian'   => 'Українська',
    'vietnamese'  => 'Tiếng Việt'
  );

  $langlist = lang_selectbox('', '', false, true);

  require_once('url_from_varval.php');

  for ($i=0; $i < sizeof($langlist); $i++) {
    if ($langlist[$i]!='') {

      $lang_name = isset($langsel[$langlist[$i]]) ? $langsel[$langlist[$i]] : $langlist[$i];

      $cpgtpl->assign_block_vars('sitelanguage', array(
        'B_CURRENTLANG'   => $langlist[$i]==$currentlang,
        'U_SITELANGUAGE'  => url_from_varval('newlang',$langlist[$i]),
        'S_SITELANGUAGE'  => $lang_name,
      ));

    }
  }

  unset($langsel);
}
