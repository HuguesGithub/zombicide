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
   * @param array $attributes
   */
  public function __construct($attributes=array())
  {
  	$services = array('LiveMission');
    parent::__construct($attributes, $services);
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
   * @return array
   */
  public function getClassVars()
  { return get_class_vars('LiveMission'); }
  /**
   * @param array $row
   * @param string $a
   * @param string $b
   * @return MissionRule
   */
  public static function convertElement($row, $a='', $b='')
  { return parent::convertElement(new LiveMission(), self::getClassVars(), $row); }
}
