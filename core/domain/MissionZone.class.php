<?php
if (!defined('ABSPATH')) {
  die('Forbidden');
}
/**
 * Classe MissionZone
 * @author Hugues.
 * @version 1.0.00
 * @since 1.0.00
 */
class MissionZone extends LocalDomain
{
    /**
     * Id technique de la donnée
     * @var int $id
     */
    protected $id;
    /**
     * Identifiant technique de la Mission
     * @var int $missionId
     */
    protected $missionId;
    /**
     * Numéro de la Zone dan la Mission
     * @var int $zoneNum
     */
    protected $zoneNum;
    /**
     * Positions en abscisses des sommets
     * @var string $coordsX
     */
    protected $coordsX;
    /**
     * Positions en ordonnées des sommets
     * @var string $coordsY
     */
    protected $coordsY;
    /**
     * Type de la Zone
     * @var string $type
     */
    protected $type;
    /**
     * Zone accessible depuis celle-ci
     * @var string $reachZone
     */
    protected $reachZone;
    /**
     * @return int
     */
    public function getId()
    { return $this->id; }
    /**
     * @return int
     */
    public function getMissionId()
    { return $this->missionId; }
    /**
     * @return int
     */
    public function getZoneNum()
    { return $this->zoneNum; }
    /**
     * @return string
     */
    public function getCoordsX()
    { return $this->coordsX; }
    /**
     * @return string
     */
    public function getCoordsY()
    { return $this->coordsY; }
    /**
     * @return string
     */
    public function getType()
    { return $this->type; }
    /**
     * @return string
     */
    public function getReachZone()
    { return $this->reachZone; }
  /**
     * @param int $id
     */
    public function setId($id)
    { $this->id = $id; }
    /**
     * @param int $missionId
     */
    public function setMissionId($missionId)
    { $this->missionId = $missionId; }
    /**
     * @param int $zoneNum
     */
    public function setZoneNum($zoneNum)
    { $this->zoneNum = $zoneNum; }
    /**
     * @param string $coordsX
     */
    public function setCoordsX($coordsX)
    { $this->coordsX = $coordsX; }
    /**
     * @param string $coordsY
     */
    public function setCoordsY($coordsY)
    { $this->coordsY = $coordsY; }
    /**
     * @param string $type
     */
    public function setType($type)
    { $this->type = $type; }
    /**
     * @param string $reachZone
     */
    public function setReachZone($reachZone)
    { $this->reachZone = $reachZone; }
    /**
     * @return array
     */
    public function getClassVars()
    { return get_class_vars('MissionZone'); }
    /**
     * @param array $row
     * @param string $a
     * @param string $b
     * @return MissionZone
     */
    public static function convertElement($row, $a='', $b='')
    { return parent::convertElement(new MissionZone(), self::getClassVars(), $row); }
}
