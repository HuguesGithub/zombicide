<?php
if (!defined('ABSPATH')) {
  die('Forbidden');
}
/**
 * Classe WpPageLiveSpawnBean
 * @author Hugues.
 * @version 1.0.00
 * @since 1.0.00
 */
class WpPageLiveSpawnBean extends PagePageBean
{
  /**
   * Class Constructor
   */
  public function __construct($WpPage='')
  {
    parent::__construct($WpPage);
//    $services = array('Expansion', 'LiveDeck', 'Spawn', 'SpawnLiveDeck');
    $this->ExpansionServices = FactoryServices::getExpansionServices();
    $this->LiveServices = FactoryServices::getLiveServices();
    $this->SpawnServices = FactoryServices::getSpawnServices();
  }
  /**
   * @param WpPage $WpPage
   * @return string
   */
  public static function getStaticPageContent($WpPage='')
  {
    $Bean = new WpPageLiveSpawnBean($WpPage);
    return $Bean->getSpawnDeckContent();
  }
  /**
   * @return string
   */
  public function getSpawnDeckContent()
  {
    $blocExpansions = '';
    $showSelection = '';
    $strSpawns = '';
    $invasionSpanSelection = $this->initVar('invasionSpanSelection');
    $deckKey = $this->initVar('keyAccess');
    if ($deckKey == '' && isset($_SESSION[self::CST_DECKKEY]) && $_SESSION[self::CST_DECKKEY]!='') {
      $deckKey = $_SESSION[self::CST_DECKKEY];
    }
    if ($deckKey=='') {
      $blocExpansions = $this->nonLoggedInterface();
    } else {
      // Sinon, on va devoir créer la pioche complète
      $pattern = "/([0-9]*-[0-9]*)/";
      preg_match_all($pattern, $invasionSpanSelection, $matches);
      if (!empty($matches) && !empty($matches[0])) {
        //$_SESSION[self::CST_DECKKEY] = $deckKey;
        $args = array('deckKey'=>$deckKey, 'dateUpdate'=>date(self::CST_FORMATDATE));
        $Live = new Live($args);
        $this->LiveServices->insert(__FILE__, __LINE__, $Live);
      } else {
        unset($_SESSION[self::CST_DECKKEY]);
      }
    }
    $args = array(
      $blocExpansions,
      $showSelection,
      $strSpawns
    );
    $str = file_get_contents(PLUGIN_PATH.'web/pages/public/public-page-live-spawn-deck.php');
    return vsprintf($str, $args);
  }
  /**
   * @return string
   */
  private function nonLoggedInterface()
  {
    $Expansions = $this->ExpansionServices->getExpansionsWithFilters(__FILE__, __LINE__, array(), array('displayRank'), array('ASC'));
    $str = '';
    if (!empty($Expansions)) {
      $str .= '<div class="btn-group-vertical live-spawn-selection" role="group">';
      foreach ($Expansions as $Expansion) {
        $id = $Expansion->getId();
        $Spawns = $this->SpawnServices->getSpawnsWithFilters(__FILE__, __LINE__, array('expansionId'=>$id), 'spawnNumber', 'ASC');
        if (!empty($Spawns)) {
          $FirstSpawn = array_shift($Spawns);
          $LastSpawn = array_pop($Spawns);
          $spawnSpan = ' ['.$FirstSpawn->getSpawnNumber().'-'.$LastSpawn->getSpawnNumber().']';
          $str .= '<div type="button" class="btn btn-dark btn-expansion" data-expansion-id="'.$id.'"><span data-spawnspan="'.$spawnSpan;
          $str .= '"><i class="far fa-square"></i></span> '.$Expansion->getName().$spawnSpan.'</div>';
        }
      }
      $str .= '</div>';
    }
    return $str;
  }
}
