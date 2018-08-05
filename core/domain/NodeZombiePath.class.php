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
  protected $MissionZone;
  protected $depth;
  protected $orientation;
  protected $arrChildren = array();
  
  /**
   */
  public function __construct($MissionZone, $depth=0, $orientation='')
  {
    $this->MissionZone = $MissionZone;
    $this->depth = $depth;
    $this->orientation = $orientation;
  }
  
  public function isNum($num)
  { return ($this->MissionZone->getZoneNum()==$num); }
  
  public function hasChildren()
  { return !empty($this->arrChildren); }
  
  public function getChildren()
  { return $this->arrChildren; }
  
  public function getMissionZone()
  { return $this->MissionZone; }
  
  public function addChild($NodeZombiePath)
  { array_push($this->arrChildren, $NodeZombiePath); }
  
  public function getDepth()
  { return $this->depth; }
  
  public function displayNodeZombiePath()
  {
    if ($this->MissionZone==null) {
      return '[]';
    }
    return str_pad('', $this->depth, '-').'['.$this->MissionZone->getZoneNum().'-'.$this->depth.'-'.$this->orientation."]<br>";
  }
}
