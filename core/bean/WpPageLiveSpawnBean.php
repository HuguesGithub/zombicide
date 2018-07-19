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
class WpPageLiveSpawnBean extends WpPageBean
{
  private $labelDraw = 'Piocher une carte (<span id="nbCardInDeck">%1$s</span>)';
  private $labelShuffle = 'Remélanger la défausse (<span id="nbCardInDiscard">%3$s</span>)';
  
  /**
   * Class Constructor
   */
  public function __construct($WpPage='')
  {
    parent::__construct($WpPage);
    $this->ExpansionServices     = new ExpansionServices();
    $this->LiveServices          = new  LiveServices();
    $this->SpawnServices         = new SpawnServices();
    $this->SpawnLiveDeckServices = new SpawnLiveDeckServices();
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
  private function createSpawnLiveDeck($args)
  {
    // On vérifie l'existence éventuelle d'un Live avec cette clé.
    $Lives = $this->LiveServices->getLivesWithFilters(__FILE__, __LINE__, $args);
    if (empty($Lives)) {
      // S'il n'en existe pas, on en créé un.
      $args['dateUpdate'] = date(self::CST_FORMATDATE);
      $Live = new Live($args);
      $this->LiveServices->insert(__FILE__, __LINE__, $Live);
      $Live->setId(MySQL::getLastInsertId());
    } else {
      // Sinon, on récupère celui existant.
      $Live = array_shift($Lives);
    }
    $invasionSpanSelection = $this->initVar('invasionSpanSelection');
    $pattern = "/([0-9]*-[0-9]*)/";
    if (preg_match_all($pattern, $invasionSpanSelection, $matches)) {
      // On va créer les cartes associées.
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
      $SpawnLiveDeck = new SpawnLiveDeck(array(self::CST_LIVEID=>$Live->getId(), self::CST_STATUS=>'P'));
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
    $str .= $this->getButtonDiv('btnDisabled1', $deckKey, '', 'Actions disponibles :', '', 'btn-dark disabled');
    $label = vsprintf($this->labelDraw, $Live->getNbCardsInDeck());
    $str .= $this->getButtonDiv('btnDrawSpawnCard', $deckKey, 'drawSpawnCard', $label);
    $str .= $this->getButtonDiv('btnDiscardSpawnActive', $deckKey, 'discardSpawnActive', 'Défausser les cartes piochées');
    $str .= $this->getButtonDiv('btnShowDiscardSpawn', $deckKey, 'showSpawnDiscard', 'Afficher la défausse');
    $label = vsprintf($this->labelShuffle, $Live->getNbCardsInDiscard());
    $str .= $this->getButtonDiv('btnShuffleDiscardSpawn', $deckKey, 'shuffleSpawnDiscard', $label);
    $str .= $this->getButtonDiv('btnLeaveSpawnDeck', $deckKey, 'leaveSpawnDeck', 'Quitter cette pioche');
    $str .= $this->getButtonDiv('btnDisabled2', $deckKey, '', 'Attention, action irréversible :', '', 'btn-dark disabled');
    $str .= $this->getButtonDiv('btnDeleteSpawnDeck', $deckKey, 'deleteSpawnDeck', 'Supprimer cette pioche', 'reload', 'btn-danger');
    return $str.'</div>';
  }
  private function getButtonDiv($id, $deckKey, $action, $label, $type='insert', $classe='btn-dark')
  {
    $str = '<div type="button" id="'.$id.'" class="btn '.$classe.' withSpawnAction" data-type="'.$type.'" data-action="';
    return $str.$action.'" data-keyaccess="'.$deckKey.'">'.$label.'</div>';
  }
  /**
   * @return string
   */
  public function getSpawnDeckContent()
  {
    $blocExpansions = '';
    $showSelection = '';
    $strSpawns = '';
    if (isset($_SESSION[self::CST_DECKKEY]) && $_SESSION[self::CST_DECKKEY]!='') {
      // On a un KeyAccess en Session
      $deckKey = $_SESSION[self::CST_DECKKEY];
      $args = array(self::CST_DECKKEY=>$deckKey);
      $Lives = $this->LiveServices->getLivesWithFilters(__FILE__, __LINE__, $args);
      $Live = array_shift($Lives);
      if ($Live==null) {
        unset($_SESSION[self::CST_DECKKEY]);
        $blocExpansions = $this->nonLoggedInterface();
      } else {
        if ($Live->getNbCardsInDeck()+$Live->getNbCardsInDiscard()==0) {
          if ($deckKey!='') {
            // On a un KeyAccess en Formulaire
            $args = array(self::CST_DECKKEY=>$deckKey);
            $Live = $this->createSpawnLiveDeck($args);
            $blocExpansions = $this->getDeckButtons($Live);
            $showSelection = self::CST_HIDDEN;
            $_SESSION[self::CST_DECKKEY] = $deckKey;
          } else {
            // On n'a rien
            $blocExpansions = $this->nonLoggedInterface();
          }
        } else {
          // On a une Session et des cartes.
          $blocExpansions = $this->getDeckButtons($Live);
          $showSelection = self::CST_HIDDEN;
        }
      }
    } else {
      // On n'a pas de Session, en a-t-on un en POST ?
      $deckKey = $this->initVar(self::CST_KEYACCESS);
      if ($deckKey!='') {
        // On a un KeyAccess en Formulaire
        $args = array(self::CST_DECKKEY=>$deckKey);
        $Live = $this->createSpawnLiveDeck($args);
        $blocExpansions = $this->getDeckButtons($Live);
        $showSelection = self::CST_HIDDEN;
        $_SESSION[self::CST_DECKKEY] = $deckKey;
      } else {
        // On n'a rien
        $blocExpansions = $this->nonLoggedInterface();
      }
    }
    if ($showSelection==self::CST_HIDDEN) {
      // On a des cartes, par défaut, on affiche les cartes "actives".
      $arrFilters = array(self::CST_LIVEID=>$Live->getId(), self::CST_STATUS=>'A');
      $SpawnLiveDecks = $this->SpawnLiveDeckServices->getSpawnLiveDecksWithFilters(__FILE__, __LINE__, $arrFilters);
      $strSpawns = self::getStaticSpawnCardActives($SpawnLiveDecks);
    }
    $args = array(
      $blocExpansions,
      $showSelection,
      $strSpawns,
      $deckKey,
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
  /**
   * @param array $SpawnLiveDecks
   * @return string
   */
  public static function getStaticSpawnCardActives($SpawnLiveDecks)
  {
    $strSpawns = '';
    if (!empty($SpawnLiveDecks)) {
      foreach ($SpawnLiveDecks as $SpawnLiveDeck) {
        $SpawnCard = $SpawnLiveDeck->getSpawnCard();
        $strSpawns .= '<div class="card spawn set"><img width="320" height="440" src="'.$SpawnCard->getImgUrl();
        $strSpawns .= '" alt="#'.$SpawnCard->getSpawnNumber().'"></div>';
      }
    }
    return $strSpawns;
  }
}
