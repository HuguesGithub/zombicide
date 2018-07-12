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
    $this->SpawnLiveDeckServices = FactoryServices::getSpawnLiveDeckServices();
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
  private function createSpawnLiveDeck($invasionSpanSelection, $matches)
  {
    // Aucune référence, on créé une nouvelle pioche
    $pattern = "/([0-9]*-[0-9]*)/";
    preg_match_all($pattern, $invasionSpanSelection, $matches);
    if (!empty($matches) && !empty($matches[0])) {
      $args['dateUpdate'] = date(self::CST_FORMATDATE);
      $Live = new Live($args);
      $this->LiveServices->insert(__FILE__, __LINE__, $Live);
      $Live->setId(MySQL::getLastInsertId());
      // Maintenant que le Live est créé, on va créer les cartes associées.
      // On étudie la première entrée de $matches.
      $arrToParse = $matches[1];
      // On va devoir stocker les identifiants des cartes.
      $arrNumbers = array();
      while (!empty($arrToParse)) {
        list($min, $max) = explode('-', array_shift($arrToParse));
        for ($i=$min; $i<=$max; $i++) {
          array_push($arrNumbers, $i);
        }
      }
      // On mélange ces cartes
      shuffle($arrNumbers);
      // On prépare l'insertion en base
      $SpawnLiveDeck = new SpawnLiveDeck(array('liveId'=>$Live->getId(), 'status'=>'P'));
      foreach ($arrNumbers as $k => $value) {
        $Spawns = $this->SpawnServices->getSpawnsWithFilters(__FILE__, __LINE__, array('spawnNumber'=>$value));
        $Spawn = array_shift($Spawns);
        $SpawnLiveDeck->setSpawnCardId($Spawn->getId());
        $SpawnLiveDeck->setRank($k+1);
        $this->SpawnLiveDeckServices->insert(__FILE__, __LINE__, $SpawnLiveDeck);
      }
      return $Live;
    } else {
      return new Live();
    }
  }
  private function getDeckButtons($Live)
  {
    $str  = '';
    $str .= '<div class="btn-group-vertical live-spawn-selection" role="group">';
    $deckKey = $Live->getDeckKey();
    $str .= $this->getButtonDiv('btnDisabled1', $deckKey, 'Actions disponibles :', 'btn-dark disabled');
    $label = 'Piocher une carte (<span id="nbCardInDeck">'.$Live->getNbCardsInDeck().'</span>)';
    $str .= $this->getButtonDiv('btnDrawSpawnCard', $deckKey, $label);
    $str .= $this->getButtonDiv('btnDiscardSpawnActive', $deckKey, 'Défausser les cartes piochées');
    $str .= $this->getButtonDiv('btnShowDiscardSpawn', $deckKey, 'Afficher la défausse');
    $label = 'Remélanger la défausse (<span id="nbCardInDiscard">'.$Live->getNbCardsInDiscard().'</span>)';
    $str .= $this->getButtonDiv('btnShuffleDiscardSpawn', $deckKey, $label);
    $str .= $this->getButtonDiv('btnLeaveSpawnDeck', $deckKey, 'Quitter cette pioche');
    $str .= $this->getButtonDiv('btnDisabled2', $deckKey, 'Attention, action irréversible :', 'btn-dark disabled');
    $str .= $this->getButtonDiv('btnDeleteSpawnDeck', $deckKey, 'Supprimer cette pioche', 'btn-danger');
    return $str.'</div>';
  }
  private function getButtonDiv($id, $deckKey, $label, $classe='btn-dark')
  {
    return '<div type="button" id="'.$id.'" class="btn '.$classe.'" data-keyaccess="'.$deckKey.'">'.$label.'</div>';
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
      $_SESSION[self::CST_DECKKEY] = $deckKey;
      $showSelection = 'hidden';
      // deckKey est renseigné. On doit vérifier que cette clef existe ou non.
      // Si elle existe, on va reprendre les données en cours.
      // Sinon, on va devoir créer la pioche complète
      $args = array('deckKey'=>$deckKey);
      $Lives = $this->LiveServices->getLivesWithFilters(__FILE__, __LINE__, $args);
      if (empty($Lives)) {
        $Live = $this->createSpawnLiveDeck($invasionSpanSelection, $matches);
      } else {
        $Live = array_shift($Lives);
      }
      $blocExpansions = $this->getDeckButtons($Live);
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
