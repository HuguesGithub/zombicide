<?php
if (!defined('ABSPATH')) {
  die('Forbidden');
}
/**
 * Classe SpawnLiveDeck
 * @author Hugues.
 * @version 1.0.00
 * @since 1.0.00
 */
class SpawnLiveDeck extends LocalDomain
{
  /**
   * Id technique de la donnée
   * @var int $id
   */
  protected $id;
  /**
   * Clé étrangère vers Live
   * @var int $liveId
   */
  protected $liveId;
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
  public function __construct($attributes=array())
  {
    parent::__construct($attributes);
    $this->LiveServices  = new LiveServices();
    $this->SpawnServices = new SpawnServices();
  }
  /**
   * @return int
   */
  public function getId()
  { return $this->id; }
  /**
   * @return int
   */
  public function getLiveId()
  { return $this->liveId; }
  /**
   * @return int
   */
  public function getSpawnCardId()
  { return $this->spawnCardId; }
  /**
   * @return int
   */
  public function getRank()
  { return $this->rank; }
  /**
   * @return string
   */
  public function getStatus()
  { return $this->status; }
  /**
   * @param int $id
   */
  public function setId($id)
  { $this->id = $id; }
  /**
   * @param int $liveId
   */
  public function setLiveId($liveId)
  { $this->liveId = $liveId; }
  /**
   * @param int $spawnCardId
   */
  public function setSpawnCardId($spawnCardId)
  { $this->spawnCardId = $spawnCardId; }
  /**
   * @param int $rank
   */
  public function setRank($rank)
  { $this->rank = $rank; }
  /**
   * @param string $status
   */
  public function setStatus($status)
  { $this->status = $status; }
  /**
   * @return array
   */
  public function getClassVars()
  { return get_class_vars('SpawnLiveDeck'); }
  /**
   * @param array $row
   * @param string $a
   * @param string $b
   * @return SpawnLiveDeck
   */
  public static function convertElement($row, $a='', $b='')
  { return parent::convertElement(new SpawnLiveDeck(), self::getClassVars(), $row); }
  /**
   * @return Spawn
   */
  public function getSpawnCard()
  {
    if ($this->Spawn==null) {
     $this->Spawn = $this->SpawnServices->select(__FILE__, __LINE__, $this->spawnCardId);
    }
    return $this->Spawn;
  }
}
