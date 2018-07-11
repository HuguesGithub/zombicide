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
    } else {
      return 'Impossible de créer quoi que ce soit, les cartes sélectionnées ne correspondent pas au format attendu ([1-46]...)';
    }
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
      $showSelection = 'hidden';
      // deckKey est renseigné. On doit vérifier que cette clef existe ou non.
      // Si elle existe, on va reprendre les données en cours.
      // Sinon, on va devoir créer la pioche complète
      $args = array('deckKey'=>$deckKey);
      $Lives = $this->LiveServices->getLivesWithFilters(__FILE__, __LINE__, $args);
      if (empty($Lives)) {
        $strSpawns = $this->createSpawnLiveDeck($invasionSpanSelection, $matches);
      } else {
        $strSpawns = 'à récupérer';
      }
      
      /*
      if (!empty($matches) && !empty($matches[0])) {
        //$_SESSION[self::CST_DECKKEY] = $deckKey;
      } else {
        unset($_SESSION[self::CST_DECKKEY]);
      }
      */
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
