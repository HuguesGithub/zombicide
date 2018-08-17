<?php
if (!defined('ABSPATH')) {
  die('Forbidden');
}
/**
 * Classe NodeZombiePath
 * @author Hugues.
 * @version 1.0.01
 * @since 1.0.01
 */
class NodeZombiePath extends LocalDomain
{
  /**
   * Missionzone
   * @var $MissionZone
   */
  protected $MissionZone;
  /**
   * Profondeur dans le chemin
   * @var $depth
   */
  protected $depth;
  /**
   * Direction du Parent vers celui-ci
   * @var $orientation
   */
  protected $orientation;
  /**
   * Liste des enfants
   * @var $arrChildren
   */
  protected $arrChildren = array();
  /**
   * Class Constructor
   * @param MissionZone $MissionZone
   * @param number $depth
   * @param string $orientation
   */
  public function __construct($MissionZone, $depth=0, $orientation='')
  {
    $this->MissionZone = $MissionZone;
    $this->depth = $depth;
    $this->orientation = $orientation;
  }
  /**
   * @return boolean
   * @param unknown $num
   */
  public function isNum($num)
  { return ($this->MissionZone->getZoneNum()==$num); }
  /**
   * @return boolean
   */
  public function hasChildren()
  { return !empty($this->arrChildren); }
  /**
   * @return NodeZombiePath
   */
  public function getChildren()
  { return $this->arrChildren; }
  /**
   * @return MissionZone
   */
  public function getMissionZone()
  { return $this->MissionZone; }
  /**
   * @param NodeZombiePath $NodeZombiePath
   */
  public function addChild($NodeZombiePath)
  { array_push($this->arrChildren, $NodeZombiePath); }
  /**
   * @return int
   */
  public function getDepth()
  { return $this->depth; }
  /**
   * @return string
   */
  public function displayNodeZombiePath()
  { 
  	$str = str_pad('', $this->depth, '-').'['.$this->MissionZone->getZoneNum().'-'.$this->depth.'-'.$this->orientation."]<br>";
  	return ($this->MissionZone==null ? '[]' : $str); }
}
