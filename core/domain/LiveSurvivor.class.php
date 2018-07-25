<?php
if (!defined('ABSPATH')) {
  die('Forbidden');
}
/**
 * Classe LiveSurvivor
 * @author Hugues.
 * @version 1.0.00
 * @since 1.0.00
 */
class LiveSurvivor extends LocalDomain
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
   * Id technique du Survivant
   * @var int $survivorId
   */
  protected $survivorId;
  /**
   * Id technique de la MissionZone
   * @var int $missionZoneId
   */
  protected $missionZoneId;
  /**
   * Id technique du SurvivorType
   * @var int $survivorTypeId
   */
  protected $survivorTypeId;
  /**
   * Points d'expérience
   * @var int $experiencePoints
   */
  protected $experiencePoints;
  /**
   * Points de vie
   * @var int $hitPoints
   */
  protected $hitPoints;
  /**
   * @param array $attributes
   */
  public function __construct($attributes=array())
  {
    parent::__construct($attributes);
    $this->EquipmentLiveDeckServices = new EquipmentLiveDeckServices();
    $this->SurvivorServices          = new SurvivorServices();
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
  public function getSurvivorId()
  { return $this->survivorId; }
  /**
   * @return int
   */
  public function getMissionZoneId()
  { return $this->missionZoneId; }
  /**
   * @return int
   */
  public function getSurvivorTypeId()
  { return $this->survivorTypeId; }
  /**
   * @return int
   */
  public function getExperiencePoints()
  { return $this->experiencePoints; }
  /**
   * @return int
   */
  public function getHitPoints()
  { return $this->hitPoints; }
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
   * @param int $survivorId
   */
  public function setSurvivorId($survivorId)
  { $this->survivorId = $survivorId; }
  /**
   * @param int $missionZoneId
   */
  public function setMissionZoneId($missionZoneId)
  { $this->missionZoneId = $missionZoneId; }
  /**
   * @param int $survivorTypeId
   */
  public function setSurvivorTypeId($survivorTypeId)
  { $this->survivorTypeId = $survivorTypeId; }
  /**
   * @param int $experiencePoints
   */
  public function setExperiencePoints($experiencePoints)
  { $this->experiencePoints = $experiencePoints; }
  /**
   * @param int $hitPoints
   */
  public function setHitPoints($hitPoints)
  { $this->hitPoints = $hitPoints; }
  /**
   * @return array
   */
  public function getClassVars()
  { return get_class_vars('LiveSurvivor'); }
  /**
   * @param array $row
   * @param string $a
   * @param string $b
   * @return LiveSurvivor
   */
  public static function convertElement($row, $a='', $b='')
  { return parent::convertElement(new LiveSurvivor(), self::getClassVars(), $row); }

  public function removeStartingEquipmentFromDeckAndEquip($Live, $EquipmentExpansions)
  {
    $cpt = 0;
    while (!empty($EquipmentExpansions)) {
      $EquipmentExpansion = array_shift($EquipmentExpansions);
      $args = array('liveId'=>$Live->getId(), 'equipmentCardId'=>$EquipmentExpansion->getId(), 'status'=>'P');
      $EquipmentLiveDecks = $this->EquipmentLiveDeckServices->getEquipmentLiveDecksWithFilters(__FILE__, __LINE__, $args);
      if (!empty($EquipmentLiveDecks)) {
        shuffle($EquipmentLiveDecks);
        for ($i=0; $i<$EquipmentExpansion->getQuantity(); $i++) {
          $EquipmentLiveDeck = array_shift($EquipmentLiveDecks);
          $EquipmentLiveDeck->setStatus('E');
          $EquipmentLiveDeck->setRank($cpt);
          $EquipmentLiveDeck->setLiveSurvivorId($this->id);
          $this->EquipmentLiveDeckServices->update(__FILE__, __LINE__, $EquipmentLiveDeck);
          $cpt++;
        }
      }
    }
  }
  
  public function getBean()
  { return new LiveSurvivorBean($this); }
  
  public function getSurvivor()
  {
    if ($this->Survivor==null) {
      $this->Survivor = $this->SurvivorServices->select(__FILE__, __LINE__, $this->survivorId);
    }
    return $this->Survivor;
  }
}
