<?php
if ( !defined( 'ABSPATH') ) { die( 'Forbidden' ); }
/**
 * Classe SpawnLiveDeck
 * @author Hugues.
 * @version 1.0.00
 * @since 1.0.00
 */
class SpawnLiveDeck extends LocalDomain {
  /**
   * Id technique de la donnée
   * @var int $id
   */
  protected $id;
  /**
   * Clé étrangère vers LiveDeck
   * @var int $liveDeckId
   */
  protected $liveDeckId;
  /**
   * Clé étrangère vers SpawnCard
   * @var int $spawnCardId
   */
  protected $spawnCardId;
  /**
   * Rang de la carte dans la pioche
   * @var int $rank
   */
  protected $rank;
  /**
   * Statut de la carte :
   * P: Pioche
   * D: Défausse
   * R: Retirée
   * A: Active
   * @var string $status
   */
  protected $status;
  /**
   * @param array $attributes
   */
  public function __construct($attributes=array()) {
  $services = array('LiveDeck', 'Spawn');
    parent::__construct($attributes, $services);
  }
  /**
   * @return int
   */
  public function getId() { return $this->id; }
  /**
   * @return int
   */
  public function getLiveDeckId() { return $this->liveDeckId; }
  /**
   * @return int
   */
  public function getSpawnCardId() { return $this->spawnCardId; }
  /**
   * @return int
   */
  public function getRank() { return $this->rank; }
  /**
   * @return string
   */
  public function getStatus() { return $this->status; }
  /**
   * @param int $id
   */
  public function setId($id) { $this->id = $id; }
  /**
   * @param int $liveDeckId
   */
  public function setLiveDeckId($liveDeckId) { $this->liveDeckId = $liveDeckId; }
  /**
   * @param int $spawnCardId
   */
  public function setSpawnCardId($spawnCardId) { $this->spawnCardId = $spawnCardId; }
  /**
   * @param int $rank
   */
  public function setRank($rank) { $this->rank = $rank; }
  /**
   * @param string $status
   */
  public function setStatus($status) { $this->status = $status; }
  /**
   * @return array
   */
  public function getClassVars() { return get_class_vars('SpawnLiveDeck'); }
  /**
   * @param array $row
   * @param string $a
   * @param string $b
   * @return SpawnLiveDeck
   */
  public static function convertElement($row, $a='', $b='') { return parent::convertElement(new SpawnLiveDeck(), self::getClassVars(), $row); }
}
?>