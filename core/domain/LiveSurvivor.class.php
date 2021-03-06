<?php
if (!defined('ABSPATH')) {
  die('Forbidden');
}
/**
 * Classe LiveSurvivor
 * @author Hugues
 * @since 1.0.01
 * @version 1.0.02
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
   * A-t-il joué ce tour ?
   * @var int $playedThisTurn
   */
  protected $playedThisTurn;
  /**
   * Quelle position dans le premier Tour
   * @var int $turnRank
   */
  protected $turnRank;
  /**
   * A-t-il fouillé ce tour ?
   * @var int $searchedThisTurn
   */
  protected $searchedThisTurn;
  /**
   * @param array $attributes
   */
  public function __construct($attributes=array())
  {
    parent::__construct($attributes);
    $this->EquipmentLiveDeckServices  = new EquipmentLiveDeckServices();
    $this->LiveServices               = new LiveServices();
    $this->LiveMissionServices        = new LiveMissionServices();
    $this->LiveSurvivorActionServices = new LiveSurvivorActionServices();
    $this->LiveSurvivorSkillServices  = new LiveSurvivorSkillServices();
    $this->MissionZoneServices        = new MissionZoneServices();
    $this->SurvivorServices           = new SurvivorServices();
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
   * @return boolean
   */
  public function hasPlayedThisTurn()
  { return ($this->playedThisTurn==1); }
  /**
   * @return int
   */
  public function getTurnRank()
  { return $this->turnRank; }
  /**
   * @return boolean
   */
  public function hasSearchedThisTurn()
  { return ($this->searchedThisTurn==1); }
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
   * @param int $playedThisTurn
   */
  public function setPlayedThisTurn($playedThisTurn)
  { $this->playedThisTurn = $playedThisTurn; }
  /**
   * @param int $turnRank
   */
  public function setTurnRank($turnRank)
  { $this->turnRank = $turnRank; }
  /**
   * @param int $searchedThisTurn
   */
  public function setSearchedThisTurn($searchedThisTurn)
  { $this->searchedThisTurn = $searchedThisTurn; }
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
  /**
   * @param Live $Live
   * @param array $EquipmentExpansions
   */
  public function removeStartingEquipmentFromDeckAndEquip($Live, $EquipmentExpansions)
  {
    $cpt = 0;
    while (!empty($EquipmentExpansions)) {
      $EquipmentExpansion = array_shift($EquipmentExpansions);
      $args = array(
        self::CST_LIVEID=>$Live->getId(),
        self::CST_EQUIPMENTCARDID=>$EquipmentExpansion->getId(),
        self::CST_STATUS=>'P'
      );
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
  /**
   * @return LiveSurvivorBean
   */
  public function getBean()
  { return new LiveSurvivorBean($this); }
  /**
   * @return Live
   */
  public function getLive()
  {
    if ($this->Live==null) {
      $this->Live = $this->LiveServices->select(__FILE__, __LINE__, $this->liveId);
    }
    return $this->Live;
  }
  /**
   * @return LiveMission
   */
  public function getLiveMission()
  {
    if ($this->LiveMission==null) {
      $LiveMissions = $this->LiveMissionServices->getLiveMissionsWithFilters(__FILE__, __LINE__, array(self::CST_LIVEID=>$this->liveId));
      $this->LiveMission = array_shift($LiveMissions);
    }
    return $this->LiveMission;
  }
  /**
   * @return MissionZone
   */
  public function getMissionZone()
  {
    if ($this->MissionZone==null) {
      $this->MissionZone = $this->MissionZoneServices->select(__FILE__, __LINE__, $this->missionZoneId);
    }
    return $this->MissionZone;
  }
  /**
   * @return Survivor
   */
  public function getSurvivor()
  {
    if ($this->Survivor==null) {
      $this->Survivor = $this->SurvivorServices->select(__FILE__, __LINE__, $this->survivorId);
    }
    return $this->Survivor;
  }
  /**
   * @return array
   */
  public function getLiveSurvivorActions()
  {
    if ($this->LiveSurvivorActions==null) {
      $args = array(self::CST_LIVESURVIVORID=>$this->id);
      $this->LiveSurvivorActions = $this->LiveSurvivorActionServices->getLiveSurvivorActionsWithFilters(__FILE__, __LINE__, $args);
    }
    return $this->LiveSurvivorActions;
  }
  /**
   * @return string
   */
  public function getPortraitUrl()
  {
    switch ($this->survivorTypeId) {
      case 2 :
        $type = 'z';
      break;
      case 3 :
        $type = 'u';
      break;
      case 4 :
        $type = 'uz';
      break;
      case 1 :
      default :
        $type = '';
      break;
    }
    return $this->getSurvivor()->getPortraitUrl($type);
  }
  /**
   * @param string $type
   * @return string
   */
  public function getUlSkills($type='')
  {
    $LiveSurvivorSkills = $this->getLiveSurvivorSkills();
    $str = '';
    $strTmp = '';
    while (!empty($LiveSurvivorSkills)) {
      $LiveSurvivorSkill = array_shift($LiveSurvivorSkills);
      switch ($LiveSurvivorSkill->getTagLevelId()) {
        case 20 :
        case 30 :
        case 40 :
          $str .= '<ul class="">'.$strTmp.'</ul>';
          $strTmp = '';
        break;
        default :
        break;
      }
      $strTmp .= $this->getSkillLi($LiveSurvivorSkill);
    }
    return $str.'<ul class="">'.$strTmp.'</ul>';
  }
  /**
   * @return array SurvivorSkill
   */
  public function getLiveSurvivorSkills()
  {
    if ($this->LiveSurvivorSkills == null) {
      $arrFilters = array(self::CST_LIVESURVIVORID=>$this->id);
      $this->LiveSurvivorSkills = $this->LiveSurvivorSkillServices->getLiveSurvivorSkillsWithFilters(__FILE__, __LINE__, $arrFilters);
    }
    return $this->LiveSurvivorSkills;
  }
  /**
   **/
  private function getSkillLi($LiveSurvivorSkill)
  {
    switch ($LiveSurvivorSkill->getTagLevelId()) {
      case 10 :
      case 11 :
        $strColor = 'blue';
      break;
      case 20 :
        $strColor = self::CST_YELLOW;
      break;
      case 30 :
      case 31 :
        $strColor = self::CST_ORANGE;
      break;
      case 40 :
      case 41 :
      case 42 :
        $strColor = 'red';
      break;
      default :
        $strColor = '';
      break;
    }
    $str = '<li><span class="badge badge-'.$strColor.'-skill'.($LiveSurvivorSkill->isLocked()?' disabled':'').'">';
    return $str.$LiveSurvivorSkill->getSkillName().'</span></li>';
  }

}
