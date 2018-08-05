<?php
if (!defined('ABSPATH')) {
  die('Forbidden');
}
/**
 * Classe MainPageBean
 * @author Hugues.
 * @version 1.0.00
 * @since 1.0.00
 */
class MainPageBean implements ConstantsInterface
{
  /**
   * Template pour afficher le header principal
   * @var $tplMainHeaderContent
   */
  public static $tplMainHeaderContent  = 'web/pages/public/public-main-header.php';
  /**
   * Template pour afficher le footer principal
   * @var $tplMainFooterContent
   */
  public static $tplMainFooterContent  = 'web/pages/public/public-main-footer.php';
  /**
   * Option pour cacher le Header et le footer.
   * @var $showHeaderAndFooter
   */
  public $showHeaderAndFooter  = true;
  /**
   * La classe du shell pour montrer plus ou moins le haut de l'image de fond.
   * @var $shellClass
   */
  protected $shellClass;

  /**
   */
  public function __construct()
  {
    $this->WpPostServices = GlobalFactoryServices::getWpPostServices();
  }
  /**
   * @return string
   */
  public function displayPublicFooter()
  {
    $args = array(admin_url('admin-ajax.php'));
    $str = file_get_contents(PLUGIN_PATH.'web/pages/public/public-main-footer.php');
    return vsprintf($str, $args);
  }
  /**
   * @return string
   */
  public function displayPublicHeader()
  {
    if ($this->showHeaderAndFooter) {
      $strPages  = '<a href="http://zombicide.jhugues.fr"><span>Accueil</span></a>';
      $strPages .= '<a href="http://zombicide.jhugues.fr/page-competences/"><span>Compétences</span></a>';
      $strPages .= '<a href="http://zombicide.jhugues.fr/page-missions/"><span>Missions</span></a>';
      $strPages .= '<a href="http://zombicide.jhugues.fr/page-survivants/"><span>Survivants</span></a>';
      $strPages .= '<a href="http://zombicide.jhugues.fr/page-spawncards/"><span>Invasion</span></a>';
      $strPages .= '<a href="http://zombicide.jhugues.fr/page-equipmentcards/"><span>Equipement</span></a>';
      $strPages .= '<span class="hasDropDown">';
      $strPages .= '<a href="#"><span>Outils</span></a>';
      $strPages .= '<ul>';
      $strPages .= '<li><a href="http://zombicide.jhugues.fr/page-selection-survivants/"><span>Génération équipe</span></a></li>';
      $strPages .= '<li><a href="http://zombicide.jhugues.fr/page-live-pioche-equipment/"><span>Pioche Equipement</span></a></li>';
      $strPages .= '<li><a href="http://zombicide.jhugues.fr/page-live-pioche-invasion/"><span>Pioche Zombie</span></a></li>';
      $strPages .= '<li><a href="http://zombicide.jhugues.fr/page-piste-de-des/"><span>Piste de dés</span></a></li>';
      $strPages .= '</ul>';
      $strPages .= '</span>';
      $args = array(
          '',
          '',
          $strPages
    );
    } else {
      $args = array('', '', '');
    }
    $str = file_get_contents(PLUGIN_PATH.'web/pages/public/public-main-header.php');
    return vsprintf($str, $args);
  }
  /**
   * @return Bean
   */
  public static function getPageBean()
  {
    if (is_front_page()) {
      $returned = new WpPageHomeBean();
    } else {
      $post = get_post();
      if (empty($post)) {
        // On a un problème (ou pas). On pourrait être sur une page avec des variables, mais qui n'est pas prise en compte.
        $slug = str_replace('/', '', $_SERVER['REDIRECT_SCRIPT_URL']);
        $args = array(
            'name'=>$slug,
            'post_type'=>'page',
            'numberposts'=>1
      );
        $my_posts = get_posts($args);
        $post = array_shift($my_posts);
      }
      if ($post->post_type == 'page') {
        $returned = new WpPageBean($post);
      } elseif ($post->post_type == 'post') {
        $returned = new WpPostBean($post);
      } else {
        $returned = new WpPageError404Bean();
      }
    }
    return $returned;
  }
  /**
   * @param array $addArg
   * @param array $remArg
   * @return string
   */
  public function getQueryArg($addArg, $remArg=array())
  {
    $addArg['page'] = 'zombicide/admin_zombicide.php';
    $remArg[] = 'form';
    $remArg[] = 'id';
    return add_query_arg($addArg, remove_query_arg($remArg, 'http://zombicide.jhugues.fr/wp-admin/admin.php'));
  }
  /**
   * @return bool
   */
  public static function isAdmin()
  { return current_user_can('manage_options'); }
  /**
   * @return string
   */
  public function getShellClass()
  { return $this->shellClass; }
  /**
   * @param string $id
   * @param string $default
   * @return mixed
   */
  public function initVar($id, $default='')
  {
    if (isset($_POST[$id])) {
      return $_POST[$id];
    }
    if (isset($_GET[$id])) {
      return $_GET[$id];
    }
    return $default;
  }
  
}
