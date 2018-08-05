<?php
if (!defined('ABSPATH')) {
  die('Forbidden');
}
/**
 * Classe LiveZombie
 * @since 1.0.00
 * @version 1.0.01
 * @author Hugues
 */
class LiveZombie extends LocalDomain
{
  /**
   * Id technique de la donnée
   * @var int $id
   */
  protected $id;
  /**
   * Id technique du Live
   * @var int $liveId
   */
  protected $liveId;
  /**
   * Id technique de la MissionZone
   * @var int $missionZoneId
   */
  protected $missionZoneId;
  /**
   * Id technique du ZombieType
   * @var int $zombieTypeId
   */
  protected $zombieTypeId;
  /**
   * Id technique de la ZombieCategory
   * @var int $zombieCategoryId
   */
  protected $zombieCategoryId;
  /**
   * Quantité
   * @var int $quantity
   */
  protected $quantity;
  public function __construct($attributes=array())
  {
    parent::__construct($attributes);
    $this->MissionZoneServices = new MissionZoneServices();
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
  public function getMissionZoneId()
  { return $this->missionZoneId; }
  /**
   * @return int
   */
  public function getZombieTypeId()
  { return $this->zombieTypeId; }
  /**
   * @return int
   */
  public function getZombieCategoryId()
  { return $this->zombieCategoryId; }
  /**
   * @return int
   */
  public function quantity()
  { return $this->quantity; }
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
   * @param int $missionZoneId
   */
  public function setMissionZoneId($missionZoneId)
  { $this->missionZoneId = $missionZoneId; }
  /**
   * @param int $zombieTypeId
   */
  public function setZombieTypeId($zombieTypeId)
  { $this->zombieTypeId = $zombieTypeId; }
  /**
   * @param int $zombieCategoryId
   */
  public function setZombieCategoryId($zombieCategoryId)
  { $this->zombieCategoryId = $zombieCategoryId; }
  /**
   * @param int $quantity
   */
  public function setQuantity($quantity)
  { $this->quantity = $quantity; }
  /**
   * @return array
   */
  public function getClassVars()
  { return get_class_vars('LiveZombie'); }
  /**
   * @param array $row
   * @param string $a
   * @param string $b
   * @return LiveZombie
   */
  public static function convertElement($row, $a='', $b='')
  { return parent::convertElement(new LiveZombie(), self::getClassVars(), $row); }
  
  public function getMissionZone()
  {
    if ($this->MissionZone==null) {
      $this->MissionZone = $this->MissionZoneServices->select(__FILE__, __LINE__, $this->missionZoneId);
    }
    return $this->MissionZone;
  }
}
