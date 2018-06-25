<?php
if ( !defined( 'ABSPATH') ) { die( 'Forbidden' ); }
/**
 * Classe OnlinePageBean
 * @author Hugues.
 * @version 1.0.00
 * @since 1.0.00
 */
class OnlinePageBean extends PagePageBean {

  public function __construct($WpPage='') {
    $services = array('Live', 'Mission');
    parent::__construct($WpPage, $services);
  }
  /**
   * @param WpPost $WpPage
   * @return string
   */
  public function getStaticPageContent($WpPage) {
    $Bean = new OnlinePageBean($WpPage);
    return $Bean->getContentPage();
  }
  /**
   * @return string
   */
  public function getContentNotLogged() {
    $time = time();
    $args = array();
    $str = file_get_contents( PLUGIN_PATH.'web/pages/public/fragments/fragment-online-identification.php' );
    $strCanvas = vsprintf($str, $args);
    $args = array(
      'Buttons',
      'Options',
      '<li class="msg-technique" data-timestamp="'.date('Y-m-d H:i:s', $time).'"><div><span class="timestamp">'.date('d/m H:i', $time).'</span></div>Bienvenue sur cette interface. Inscrivez-vous et identifiez-vous pour rejoindre la discussion.</li>',
      $strCanvas,
      'hidden',
      '<li class="nav-item"><a class="nav-link active" href="#" data-liveid="0">Général</a></li>',
    );
    $str = file_get_contents( PLUGIN_PATH.'web/pages/public/public-page-online.php' );
    return vsprintf($str, $args);
  }
  /**
   * @return string
   */
  public function getContentLoggedNotLive() {
    $time = time();
    $args = array(
      'Buttons',
      'Options',
      '<li class="msg-technique" data-timestamp="'.date('Y-m-d H:i:s', $time).'"><div><span class="timestamp">'.date('d/m H:i', $time).'</span></div>Bienvenue sur cette interface.</li>',
      '',
      '',
      '<li class="nav-item"><a class="nav-link active" href="#" data-liveid="0">Général</a></li>',
    );
    $str = file_get_contents( PLUGIN_PATH.'web/pages/public/public-page-online.php' );
    return vsprintf($str, $args);
  }
  /**
   * @return string
   */
  public function getContentLoggedAndLive() {
    $deckKey = $_SESSION[CST_DECKKEY];
    $Lives = $this->LiveServices->getLivesWithFilters(__FILE__, __LINE__, array(self::CST_DECKKEY=>$deckKey));
    if ( empty($Lives) ) {
      unset($_SESSION[CST_DECKKEY]);
      return $this->getContentLoggedNotLive();
    }
    $time = time();
    $Live = array_shift($Lives);
    $missionId = 1;
    $Mission = $this->MissionServices->select(__FILE__, __LINE__, $missionId);
    $MissionBean = new MissionBean($Mission);
    $args = array(
      'Buttons',
      'Options',
      '<li class="msg-technique" data-timestamp="'.date('Y-m-d H:i:s', $time).'"><div><span class="timestamp">'.date('d/m H:i', $time).'</span></div>Bienvenue dans l\'espace de discussion '.$Live->getDeckKey().'.</li>',
      $MissionBean->displayCanvas(),
      '',
      '<li class="nav-item"><a class="nav-link active" href="#" data-liveid="'.$Live->getId().'">'.$Live->getDeckKey().'</a></li>',
    );
    $str = file_get_contents( PLUGIN_PATH.'web/pages/public/public-page-online.php' );
    return vsprintf($str, $args);
  }
  /**
   * {@inheritDoc}
   * @see PagePageBean::getContentPage()
   */
  public function getContentPage() {
    if ( isset($_SESSION[CST_DECKKEY]) ) {
      return $this->getContentLoggedAndLive();
    } elseif ( is_user_logged_in() ) {
      return $this->getContentLoggedNotLive();
    } else {
      return $this->getContentNotLogged();
    }
  }
  
  
}
?>
