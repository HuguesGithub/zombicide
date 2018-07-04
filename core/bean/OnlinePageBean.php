<?php
if (!defined('ABSPATH')) {
  die('Forbidden');
}
/**
 * Classe OnlinePageBean
 * @author Hugues.
 * @version 1.0.00
 * @since 1.0.00
 */
class OnlinePageBean extends PagePageBean
{
  /**
   * Class Constructor
   * @param WpPage $WpPage
   */
  public function __construct($WpPage='')
  {
    $services = array('Live', 'Mission');
    parent::__construct($WpPage, $services);
  }
  /**
   * @param WpPost $WpPage
   * @return string
   */
  public function getStaticPageContent($WpPage)
  {
    $Bean = new OnlinePageBean($WpPage);
    return $Bean->getContentPage();
  }
  /**
   * @return string
   */
  public function getContentNotLogged()
  {
    $ts = date(self::CST_FORMATDATE, time());
    $args = array();
    $str = file_get_contents(PLUGIN_PATH.'web/pages/public/fragments/fragment-online-identification.php');
    $strCanvas = vsprintf($str, $args);
    $strMsg  = '<li class="msg-technique" data-timestamp="'.$ts.'"><div><span class="timestamp">'.$ts;
    $strMsg .= '</span></div>Bienvenue sur cette interface. Inscrivez-vous et identifiez-vous pour rejoindre la discussion.</li>';
    $args = array(
      'Buttons',
      'Options',
      $strMsg,
      $strCanvas,
      'hidden',
      '<li class="nav-item"><a class="nav-link active" href="#" data-liveid="0">Général</a></li>',
    );
    $str = file_get_contents(PLUGIN_PATH.'web/pages/public/public-page-online.php');
    return vsprintf($str, $args);
  }
  /**
   * @return string
   */
  public function getContentLoggedNotLive()
  {
    $ts = date(self::CST_FORMATDATE, time());
    $strMsg  = '<li class="msg-technique" data-timestamp="'.$ts.'"><div><span class="timestamp">'.$ts;
    $strMsg .= '</span></div>Bienvenue sur cette interface.</li>';
    $args = array(
      'Buttons',
      'Options',
      $strMsg,
      '',
      '',
      '<li class="nav-item"><a class="nav-link active" href="#" data-liveid="0">Général</a></li>',
    );
    $str = file_get_contents(PLUGIN_PATH.'web/pages/public/public-page-online.php');
    return vsprintf($str, $args);
  }
  /**
   * @return string
   */
  public function getContentLoggedAndLive()
  {
    $deckKey = $_SESSION[self::CST_DECKKEY];
    $Lives = $this->LiveServices->getLivesWithFilters(__FILE__, __LINE__, array(self::CST_DECKKEY=>$deckKey));
    if (empty($Lives)) {
      unset($_SESSION[self::CST_DECKKEY]);
      return $this->getContentLoggedNotLive();
    }
    $ts = date(self::CST_FORMATDATE, time());
    $Live = array_shift($Lives);
    $missionId = 1;
    $Mission = $this->MissionServices->select(__FILE__, __LINE__, $missionId);
    $MissionBean = new MissionBean($Mission);
    $strMsg  = '<li class="msg-technique" data-timestamp="'.$ts.'"><div><span class="timestamp">'.$ts;
    $strMsg .= '</span></div>Bienvenue dans l\'espace de discussion '.$Live->getDeckKey().'.</li>';
    $args = array(
      'Buttons',
      'Options',
      $strMsg,
      $MissionBean->displayCanvas(),
      '',
      '<li class="nav-item"><a class="nav-link active" href="#" data-liveid="'.$Live->getId().'">'.$Live->getDeckKey().'</a></li>',
   );
    $str = file_get_contents(PLUGIN_PATH.'web/pages/public/public-page-online.php');
    return vsprintf($str, $args);
  }
  /**
   * {@inheritDoc}
   * @see PagePageBean::getContentPage()
   */
  public function getContentPage()
  {
    if (isset($_SESSION[self::CST_DECKKEY])) {
      return $this->getContentLoggedAndLive();
    } elseif (is_user_logged_in()) {
      return $this->getContentLoggedNotLive();
    } else {
      return $this->getContentNotLogged();
    }
  }
  
  
}
