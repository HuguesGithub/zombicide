<?php
if ( !defined( 'ABSPATH') ) { die( 'Forbidden' ); }
/**
 * Classe LiveDeck
 * @author Hugues.
 * @version 1.0.00
 * @since 1.0.00
 */
class LiveDeck extends LocalDomain {
  /**
   * Id technique de la donnée
   * @var int $id
   */
  protected $id;
  /**
   * Code alphanumérique 
   * @var string $deckKey
   */
  protected $deckKey;
  /**
   * Date de dernière mise à jour
   * @var datetime $dateUpdate
   */
  protected $dateUpdate;
  /**
   * @param array $attributes
   */
  public function __construct($attributes=array()) {
    $services = array('SpawnLiveDeck');
    parent::__construct($attributes, $services);
  }
  /**
   * @return int
   */
  public function getId() { return $this->id; }
  /**
   * @return string
   */
  public function getDeckKey() { return $this->deckKey; }
  /**
   * @return string
   */
  public function getDateUpdate() { return $this->dateUpdate; }
  /**
   * @param int $id
   */
  public function setId($id) { $this->id = $id; }
  /**
   * @param string $deckKey
   */
  public function setDeckKey($deckKey) { $this->deckKey = $deckKey; }
  /**
   * @param string $dateUpdate
   */
  public function setDateUpdate($dateUpdate) { $this->dateUpdate = $dateUpdate; }
  /**
   * @return array
   */
  public function getClassVars() { return get_class_vars('LiveDeck'); }
  /**
   * @param array $row
   * @param string $a
   * @param string $b
   * @return LiveDeck
   */
  public static function convertElement($row, $a='', $b='') { return parent::convertElement(new LiveDeck(), self::getClassVars(), $row); }
  /**
   * @return int
   */
  public function getNbCardsInDeck() {
    $SpawnLiveDecks = $this->getSpawnLiveDecks();
    $nb = 0;
    if ( !empty($SpawnLiveDecks) ) {
      foreach ( $SpawnLiveDecks as $SpawnLiveDeck ) {
        if ( $SpawnLiveDeck->getStatus()=='P' ) { $nb++; }
      }
    }
    return $nb;
  }
  /**
   * @return int
   */
  public function getNbCardsInDiscard() {
    $SpawnLiveDecks = $this->getSpawnLiveDecks();
    $nb = 0;
    if ( !empty($SpawnLiveDecks) ) {
      foreach ( $SpawnLiveDecks as $SpawnLiveDeck ) {
        if ( $SpawnLiveDeck->getStatus()=='D' ) { $nb++; }
      }
    }
    return $nb;
  }
  /**
   * @param array $SpawnLiveDecks
   */
  public function setSpawnLiveDecks($SpawnLiveDecks) { $this->SpawnLiveDecks = $SpawnLiveDecks; }
  
}
?>