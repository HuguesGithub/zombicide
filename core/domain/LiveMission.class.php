<?php
if (!defined('ABSPATH')) {
  die('Forbidden');
}
/**
 * Classe LiveMission
 * @author Hugues.
 * @version 1.0.00
 * @since 1.0.00
 */
class LiveMission extends LocalDomain
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
   * Id technique de la Mission
   * @var int $missionId
   */
  protected $missionId;
  /**
   * Id technique du LiveSurvivor actif. Potentiellement nul.
   * @var int $activeLiveSurvivorId
   */
  protected $activeLiveSurvivorId;
  /**
   * @param array $attributes
   */
  public function __construct($attributes=array())
  {
    parent::__construct($attributes);
    $this->LiveServices         = new LiveServices();
    $this->LiveSurvivorServices = new LiveSurvivorServices();
    $this->MissionServices      = new MissionServices();
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
  public function getMissionId()
  { return $this->missionId; }
  /**
   * @return int
   */
  public function getActiveLiveSurvivorId()
  { return $this->activeLiveSurvivorId; }
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
   * @param int $missionId
   */
  public function setMissionId($missionId)
  { $this->missionId = $missionId; }
  /**
   * @param int $activeLiveSurvivorId
   */
  public function setActiveLiveSurvivorId($activeLiveSurvivorId)
  { $this->activeLiveSurvivorId = $activeLiveSurvivorId; }
  /**
   * @return array
   */
  public function getClassVars()
  { return get_class_vars('LiveMission'); }
  /**
   * @param array $row
   * @param string $a
   * @param string $b
   * @return LiveMission
   */
  public static function convertElement($row, $a='', $b='')
  { return parent::convertElement(new LiveMission(), self::getClassVars(), $row); }
  
  public function getLive()
  {
    if ($this->Live==null) {
      $this->Live = $this->LiveServices->select(__FILE__, __LINE__, $this->liveId);
    }
    return $this->Live;
  }
  
  public function getMission()
  {
    if ($this->Mission==null) {
      $this->Mission = $this->MissionServices->select(__FILE__, __LINE__, $this->missionId);
    }
    return $this->Mission;
  }
  
  public function getActiveLiveSurvivor()
  {
    if ($this->ActiveLiveSurvivor==null) {
      $this->ActiveLiveSurvivor = $this->LiveSurvivorServices->select(__FILE__, __LINE__, $this->activeLiveSurvivorId);
    }
    return $this->ActiveLiveSurvivor;
  }
}
