<?php
if (!defined('ABSPATH')) {
  die('Forbidden');
}
/**
 * Classe NodeZombiePath
 * @since 1.0.01
 * @version 1.0.01
 * @author Hugues
 */
class NodeZombiePath extends LocalDomain
{
  /**
   * @var $MissionZone
   */
  protected $MissionZone;
  /**
   * @var $depth
   */
  protected $depth;
  /**
   * @var $orientation
   */
  protected $orientation;
  /**
   * @var $arrChildren
   */
  protected $arrChildren = array();
  
  /**
   * @param MissionZone $MissionZone
   * @param int $depth
   * @param string $orientation
   */
  public function __construct($MissionZone, $depth=0, $orientation='')
  {
    $this->MissionZone = $MissionZone;
    $this->depth = $depth;
    $this->orientation = $orientation;
  }
  /**
   * @param int $num
   * @return boolean
   */
  public function isNum($num)
  { return ($this->MissionZone->getZoneNum()==$num); }
  /**
   * @return boolean
   */
  public function hasChildren()
  { return !empty($this->arrChildren); }
  /**
   * @return array $arrChildren
   */
  public function getChildren()
  { return $this->arrChildren; }
  /**
   * @return MissionZone $MissionZone
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
    if ($this->MissionZone==null) {
      return '[]';
    }
    return str_pad('', $this->depth, '-').'['.$this->MissionZone->getZoneNum().'-'.$this->depth.'-'.$this->orientation."]<br>";
  }
}
