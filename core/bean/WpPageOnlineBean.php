<?php
if (!defined('ABSPATH')) {
  die('Forbidden');
}
/**
 * Classe WpPageOnlineBean
 * @author Hugues.
 * @version 1.0.00
 * @since 1.0.00
 */
class WpPageOnlineBean extends WpPageBean
{
  /**
   * Class Constructor
   * @param WpPage $WpPage
   */
  public function __construct($WpPage='')
  {
    parent::__construct($WpPage);
    $this->LiveServices    = new LiveServices();
    $this->MissionServices = new MissionServices();
  }
  /**
   * @param WpPost $WpPage
   * @return string
   */
  public function getStaticPageContent($WpPage)
  {
    $Bean = new WpPageOnlineBean($WpPage);
    return $Bean->getContentPage();
  }
  /**
   * @return string
   */
  public function getContentNotLogged()
  {
    $ts = date(self::CST_FORMATDATE, time());
    $strMsg  = '<li class="msg-technique" data-timestamp="'.$ts.'"><div><span class="timestamp">'.$ts;
    $strMsg .= '</span></div>Bienvenue sur cette interface. Inscrivez-vous et identifiez-vous pour rejoindre la discussion.</li>';
    $args = array(
      'Buttons',
      'Options',
      $strMsg,
      '',
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
    $args = array();
    $str = file_get_contents(PLUGIN_PATH.'web/pages/public/fragments/fragment-online-identification.php');
    $strCanvas = vsprintf($str, $args);
    $strMsg  = '<li class="msg-technique" data-timestamp="'.$ts.'"><div><span class="timestamp">'.$ts;
    $strMsg .= '</span></div>Bienvenue sur cette interface.</li>';
    $args = array(
      'Buttons',
      'Options',
      $strMsg,
      $strCanvas,
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
    //$ts = date(self::CST_FORMATDATE, time());
    $Live = array_shift($Lives);
    
    $args = array(self::CST_LIVEID=>$Live->getId());
    $Missions = $this->MissionServices->getMissionsWithFilters(__FILE__, __LINE__, $args);
    if (empty($Missions)) {
      return 'Display Mission Choice';
      // On doit choisir une Mission au hasard.
    } else {
      return 'Check if Survivors are selected';
      // On doit choisir les Survivants joués. Sauf si c'est déjà fait.
    }
    /*
    $missionId = 1;
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
    */
  }
  /**
   * {@inheritDoc}
   * @see PagePageBean::getContentPage()
   */
  public function getContentPage()
  {
    if (isset($_POST[self::CST_KEYACCESS])) {
      $args = array(self::CST_DECKKEY=>$_POST[self::CST_KEYACCESS]);
      $Lives = $this->LiveServices->getLivesWithFilters(__FILE__, __LINE__, $args);
      $Live = array_shift($Lives);
      if (empty($Live)) {
        $args['dateUpdate'] = date('Y-m-d H:i:s');
        $Live = new Live($args);
        $this->LiveServices->insert(__FILE__, __LINE__, $Live);
        $Live->setId(MySQL::getLastInsertId());
      }
      $_SESSION[self::CST_DECKKEY] = $_POST[self::CST_KEYACCESS];
    }
    if (isset($_SESSION[self::CST_DECKKEY])) {
      return $this->getContentLoggedAndLive();
    } elseif (is_user_logged_in()) {
      return $this->getContentLoggedNotLive();
    } else {
      return $this->getContentNotLogged();
    }
  }
  
  
}
