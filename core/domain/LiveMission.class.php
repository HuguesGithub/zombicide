<?php
if (!defined('ABSPATH')) {
  die('Forbidden');
}
/**
 * Classe LiveMission
 * @since 1.0.00
 * @version 1.0.01
 * @author Hugues
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
   * Nombre de Survivants dans la Mission.
   * @var int $nbSurvivors
   */
  protected $nbSurvivors;
  /**
   * Tour actuel.
   * @var int $turn
   */
  protected $turn;
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
   * @return int
   */
  public function getNbSurvivors()
  { return $this->nbSurvivors; }
  /**
   * @return int
   */
  public function getTurn()
  { return $this->turn; }
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
   * @param int $nbSurvivors
   */
  public function setNbSurvivors($nbSurvivors)
  { $this->nbSurvivors = $nbSurvivors; }
  /**
   * @param int $turn
   */
  public function setTurn($turn)
  { $this->turn = $turn; }
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
  
  public function getLiveSurvivors()
  {
    if ($this->LiveSurvivors==null) {
      $args = array(self::CST_LIVEID=>$this->liveId);
      $this->LiveSurvivors = $this->LiveSurvivorServices->getLiveSurvivorsWithFilters(__FILE__, __LINE__, $args);
    }
    return $this->LiveSurvivors;
  }
  
  public function getActiveLiveSurvivor()
  {
    if ($this->ActiveLiveSurvivor==null) {
      $this->ActiveLiveSurvivor = $this->LiveSurvivorServices->select(__FILE__, __LINE__, $this->activeLiveSurvivorId);
    }
    return $this->ActiveLiveSurvivor;
  }
  public function getFirstLiveSurvivor()
  {
    $rk = ($this->turn%$this->nbSurvivors);
    if ($rk==0) {
      $rk = $this->nbSurvivors;
    }
    $args = array(self::CST_LIVEID=>$this->liveId, 'turnRank'=>$rk);
    $LiveSurvivors = $this->LiveSurvivorServices->getLiveSurvivorsWithFilters(__FILE__, __LINE__, $args);
    return array_shift($LiveSurvivors);
  }
  public function getNextLiveSurvivor()
  {
    $turnRank = $this->getActiveLiveSurvivor()->getTurnRank();
    $args = array(self::CST_LIVEID=>$this->liveId, 'playedThisTurn'=>0);
    $LiveSurvivors = $this->LiveSurvivorServices->getLiveSurvivorsWithFilters(__FILE__, __LINE__, $args);
    // Si on n'en a qu'un, c'est l'actif, c'est la fin du tour,
    if (count($LiveSurvivors)==1) {
      return false;
    }
    // Sinon, on prend celui qui suit
    $nextTurnRank = ($turnRank+1)%$this->nbSurvivors;
    if ($nextTurnRank==0) {
      $nextTurnRank = $this->nbSurvivors;
    }
    $args = array(self::CST_LIVEID=>$this->liveId, 'turnRank'=>$nextTurnRank);
    $LiveSurvivors = $this->LiveSurvivorServices->getLiveSurvivorsWithFilters(__FILE__, __LINE__, $args);
    // En gardant à l'esprit que durant le tour 1, faut le faire à la main...
    if (empty($LiveSurvivors)) {
      return new LiveSurvivor();
    } else {
      return array_shift($LiveSurvivors);
    }
  }
}
