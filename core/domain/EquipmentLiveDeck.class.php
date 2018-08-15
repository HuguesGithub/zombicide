<?php
if (!defined('ABSPATH')) {
  die('Forbidden');
}
/**
 * Classe EquipmentLiveDeck
 * @author Hugues.
 * @version 1.0.00
 * @since 1.0.00
 */
class EquipmentLiveDeck extends LocalDomain
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
   * @var int $equipmentCardId
   */
  protected $equipmentCardId;
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
   * Clé étrangère vers LiveSurvivor si status=='E', 0 sinon
   * @var int $liveSurvivorId
   */
  protected $liveSurvivorId;
  /**
   * @param array $attributes
   */
  public function __construct($attributes=array())
  {
    parent::__construct($attributes);
    $this->LiveServices = new LiveServices();
    $this->EquipmentServices = new EquipmentServices();
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
  public function getEquipmentCardId()
  { return $this->equipmentCardId; }
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
   * @return int
   */
  public function getLiveSurvivorId()
  { return $this->liveSurvivorId; }
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
  public function setEquipmentCardId($equipmentCardId)
  { $this->equipmentCardId = $equipmentCardId; }
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
   * @param int $liveSurvivorId
   */
  public function setLiveSurvivorId($liveSurvivorId)
  { $this->liveSurvivorId = $liveSurvivorId; }
  /**
   * @return array
   */
  public function getClassVars()
  { return get_class_vars('EquipmentLiveDeck'); }
  /**
   * @param array $row
   * @param string $a
   * @param string $b
   * @return EquipmentLiveDeck
   */
  public static function convertElement($row, $a='', $b='')
  { return parent::convertElement(new EquipmentLiveDeck(), self::getClassVars(), $row); }
}
