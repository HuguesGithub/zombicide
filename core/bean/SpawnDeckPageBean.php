<?php
if ( !defined( 'ABSPATH') ) { die( 'Forbidden' ); }
/**
 * Classe SpawnDeckPageBean
 * @author Hugues.
 * @version 1.0.00
 * @since 1.0.00
 */
class SpawnDeckPageBean extends PagePageBean {

	public function __construct($WpPage='') {
		$services = array('Expansion', 'LiveDeck', 'Spawn', 'SpawnLiveDeck');
		parent::__construct($WpPage, $services);
	}
	/**
	 * @param array $SpawnLiveDecks
	 */
	public static function getStaticSpawnCardActives($SpawnLiveDecks) {
		$strSpawns = '';
		if ( !empty($SpawnLiveDecks) ) {
			foreach ( $SpawnLiveDecks as $SpawnLiveDeck ) {
				$SpawnCard = $SpawnLiveDeck->getSpawnCard();
				$strSpawns .= '<div class="card spawn set-'.$id.'"><img width="320" height="440" src="'.$SpawnCard->getImgUrl().'" alt="#'.$SpawnCard->getSpawnNumber().'"></div>';
			}
		}
		return $strSpawns;
	}
	/**
	 * @param WpPage $WpPage
	 * @return string
	 */
	public static function getStaticSpawnDeckContent($WpPage='') {
		$Bean = new SpawnDeckPageBean($WpPage);
		return $Bean->getSpawnDeckContent();
	}
	/**
	 * @return string
	 */
	public function getSpawnDeckContent() {
    $strSpawns = '';
    $showSelection = '';
		$invasionSpanSelection = $this->initVar('invasionSpanSelection');
		$keyAccess = $this->initVar('keyAccess');
    if ( $keyAccess == '' && isset($_SESSION['wp_11_keyAccess']) && $_SESSION['wp_11_keyAccess']!='' ) { $keyAccess = $_SESSION['wp_11_keyAccess']; }
		// Si $keyAccess est défini    
    if ( $keyAccess!='' ) {
      $LiveDecks = $this->LiveDeckServices->getLiveDecksWithFilters(__FILE__, __LINE__, array('deckKey'=>$keyAccess));
      // On a un LiveDeck qui correspond à $keyAccess, on va l'utiliser
      if ( !empty($LiveDecks) ) {
        $LiveDeck = array_shift($LiveDecks);
        $SpawnLiveDecks = $this->SpawnLiveDeckServices->getSpawnLiveDecksWithFilters(__FILE__, __LINE__, array('liveDeckId'=>$LiveDeck->getId(), 'status'=>'A'), 'rank', 'ASC');
				$strSpawns = self::getStaticSpawnCardActives($SpawnLiveDecks);
      } // Sinon, on va devoir créer la pioche complète
      else {
        $pattern = "/([0-9]*-[0-9]*)/";
        preg_match_all($pattern, $invasionSpanSelection, $matches);
        // On a repérer un intervalle (ou plus) de cartes à insérer dans la pioche.
        if ( !empty($matches) && !empty($matches[0]) ) {
          $_SESSION['wp_11_keyAccess'] = $keyAccess;
          $args = array(
            'deckKey'=>$keyAccess,
            'dateUpdate'=>date('Y-m-d H:i:s', time()),
          );
          $LiveDeck = new LiveDeck($args);
          $this->LiveDeckServices->insert(__FILE__, __LINE__, $LiveDeck);
          $idLiveDeck = MySQL::getLastInsertId();
          $LiveDeck->setId($id);
          
          $arrToParse = array_shift($matches);
          $arrNumbers = array();
          foreach ( $arrToParse as $key=>$value ) {
            list($min, $max) = explode('-', $value);
            for ( $i=$min; $i<=$max; $i++ ) {
              array_push($arrNumbers, $i);
            }
          }
          shuffle($arrNumbers);
          
          $SpawnLiveDecks = array();
          $SpawnLiveDeck = new SpawnLiveDeck(array('liveDeckId'=>$idLiveDeck, 'status'=>'P'));
          foreach ( $arrNumbers as $key=>$value) {
            $Spawns = $this->SpawnServices->getSpawnsWithFilters(__FILE__, __LINE__, array('spawnNumber'=>$value));
            $Spawn = array_shift($Spawns);
            $SpawnLiveDeck->setSpawnCardId($Spawn->getId());
            $SpawnLiveDeck->setRank($key+1);
            $this->SpawnLiveDeckServices->insert(__FILE__, __LINE__, $SpawnLiveDeck);
            array_push($SpawnLiveDecks, $SpawnLiveDeck);
          }
          $LiveDeck->setSpawnLiveDecks($SpawnLiveDecks);
        }
      }
      $str = $this->getDeckButtons($LiveDeck);
      $showSelection = 'hidden';
    } else {
      $str = $this->getExpansionsButtons();
    }
		$args = array(
			$str,
      $showSelection,
      $strSpawns
		);
		$str = file_get_contents( PLUGIN_PATH.'web/pages/public/public-page-live-spawn-deck.php' );
		return vsprintf($str, $args);
	}
  private function getDeckButtons($LiveDeck) {
    $str  = '';
    $str .= '<div class="btn-group-vertical live-spawn-selection" role="group">';
    $keyAccess = $LiveDeck->getDeckKey();
    $str .= $this->getButtonDiv('btnDisabled1', $keyAccess, 'Actions disponibles :', 'btn-dark disabled');
    $str .= $this->getButtonDiv('btnDrawSpawnCard', $keyAccess, 'Piocher une carte (<span id="nbCardInDeck">'.$LiveDeck->getNbCardsInDeck().'</span>)');
    $str .= $this->getButtonDiv('btnDiscardSpawnActive', $keyAccess, 'Défausser les cartes piochées');
    $str .= $this->getButtonDiv('btnShowDiscardSpawn', $keyAccess, 'Afficher la défausse');
    $str .= $this->getButtonDiv('btnShuffleDiscardSpawn', $keyAccess, 'Remélanger la défausse (<span id="nbCardInDiscard">'.$LiveDeck->getNbCardsInDiscard().'</span>)');
    $str .= $this->getButtonDiv('btnLeaveSpawnDeck', $keyAccess, 'Quitter cette pioche');
    $str .= $this->getButtonDiv('btnDisabled2', $keyAccess, 'Attention, action irréversible :', 'btn-dark disabled');
    $str .= $this->getButtonDiv('btnDeleteSpawnDeck', $keyAccess, 'Supprimer cette pioche', 'btn-danger');
    $str .= '</div>';
    return $str;
  }
  private function getButtonDiv($id, $keyAccess, $label, $classe='btn-dark') {
    return '<div type="button" id="'.$id.'" class="btn '.$classe.'" data-keyaccess="'.$keyAccess.'">'.$label.'</div>';
  }
  private function getExpansionsButtons() {
    $Expansions = $this->ExpansionServices->getExpansionsWithFilters(__FILE__, __LINE__, array(), array('displayRank'), array('ASC'));
    if ( !empty($Expansions) ) {
      $str .= '<div class="btn-group-vertical live-spawn-selection" role="group">';
      foreach ( $Expansions as $Expansion ) {
        $id = $Expansion->getId();
        $Spawns = $this->SpawnServices->getSpawnsWithFilters(__FILE__, __LINE__, array('expansionId'=>$id), 'spawnNumber', 'ASC');
        if ( empty($Spawns) ) { continue; }
        $FirstSpawn = array_shift($Spawns);
        $LastSpawn = array_pop($Spawns);
        $spawnSpan = ' ['.$FirstSpawn->getSpawnNumber().'-'.$LastSpawn->getSpawnNumber().']';
        $str .= '<div type="button" class="btn btn-dark btn-expansion" data-expansion-id="'.$id.'"><span data-spawnspan="'.$spawnSpan;
        $str .= '"><i class="far fa-square"></i></span> '.$Expansion->getName().$spawnSpan.'</div>';
      }
      $str .= '</div>';
    }
    return $str;
  }
}
?>
