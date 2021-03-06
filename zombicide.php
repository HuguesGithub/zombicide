<?php
/**
 * Plugin Name: HJ - Zombicide
 * Description : News Zombicide & Zombicide online
 * @version 1.0.00
 * @author Hugues
 */
define('PLUGIN_PATH', plugin_dir_path(__FILE__));
define('PLUGIN_PACKAGE', 'zombicide');
date_default_timezone_set('Europe/Paris');
session_start([]);

class Zombicide
{
  public function __construct()
  {
    add_filter('template_include', array($this,'template_loader'));
  }

  public function template_loader()
  {
    wp_enqueue_script('jquery');
    return PLUGIN_PATH.'web/pages/public/public-main-page.php';
  }
}
$Zombicide = new Zombicide();

/**
#######################################################################################
###  Autoload des classes utilis?es
### Description: Gestion de l'inclusion des classes
#######################################################################################
*/
spl_autoload_register(PLUGIN_PACKAGE.'_autoloader');
function zombicide_autoloader($classname)
{
  $pattern = "/(Bean|DaoImpl|Dao|Services|Actions|Utils|Interface)/";
  preg_match($pattern, $classname, $matches);
  if (isset($matches[1])) {
    switch ($matches[1]) {
      case 'Actions' :
      case 'Bean' :
      case 'Dao' :
      case 'DaoImpl' :
      case 'Interface' :
      case 'Services' :
      case 'Utils' :
        if (file_exists(PLUGIN_PATH.'core/'.strtolower($matches[1]).'/'.$classname.'.php')) {
          include_once(PLUGIN_PATH.'core/'.strtolower($matches[1]).'/'.$classname.'.php');
        } elseif (file_exists(PLUGIN_PATH.'../mycommon/core/'.strtolower($matches[1]).'/'.$classname.'.php')) {
          include_once(PLUGIN_PATH.'../mycommon/core/'.strtolower($matches[1]).'/'.$classname.'.php');
        }
      break;
      default :
        // On est dans un cas o? on a match? mais pas pr?vu le traitement...
        break;
    }
  } else {
    $classfile = sprintf('%score/domain/%s.class.php', PLUGIN_PATH, str_replace('_', '-', $classname));
    if (!file_exists($classfile)) {
      $classfile = sprintf('%s../mycommon/core/domain/%s.class.php', PLUGIN_PATH, str_replace('_', '-', $classname));
    }
    if (file_exists($classfile)) {
      include_once($classfile);
    }
  }
}
/**
#######################################################################################
###  Ajout d'une entrée dans le menu d'administration.
#######################################################################################
**/
function zombicide_menu()
{
  $urlRoot = 'zombicide/admin_zombicide.php';
  if (function_exists('add_menu_page')) {
    $uploadFiles = 'upload_files';
    add_menu_page('Zombicide', 'Zombicide', $uploadFiles, $urlRoot, '', plugins_url('/zombicide/web/rsc/img/icons/icon_s1.png'));
    if (function_exists('add_submenu_page')) {
      $urlSubMenu = $urlRoot.'&amp;onglet=skill';
      add_submenu_page($urlRoot, 'Compétences', 'Compétences', $uploadFiles, $urlSubMenu, 'skills');
      $urlSubMenu = $urlRoot.'&amp;onglet=mission';
      add_submenu_page($urlRoot, 'Missions', 'Missions', $uploadFiles, $urlSubMenu, 'missions');
      $urlSubMenu = $urlRoot.'&amp;onglet=survivor';
      add_submenu_page($urlRoot, 'Survivants', 'Survivants', $uploadFiles, $urlSubMenu, 'survivors');
      $urlSubMenu = $urlRoot.'&amp;onglet=parametre';
      add_submenu_page($urlRoot, 'Paramètres', 'Paramètres', $uploadFiles, $urlSubMenu, 'parametres');
    }
  }
}
add_action('admin_menu', 'zombicide_menu');
/**
#######################################################################################
###  Ajout d'une action Ajax
### Description: Entrance point for Ajax Interaction.
#######################################################################################
*/
add_action('wp_ajax_dealWithAjax', 'dealWithAjax_callback');
add_action('wp_ajax_nopriv_dealWithAjax', 'dealWithAjax_callback');
function dealWithAjax_callback()
{
  echo AjaxActions::dealWithAjax();
  die();
}
