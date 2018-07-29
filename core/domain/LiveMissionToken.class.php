<?php
if (!defined('ABSPATH')) {
  die('Forbidden');
}
/**
 * Classe LiveMissionToken
 * @author Hugues.
 * @version 1.0.00
 * @since 1.0.00
 */
class LiveMissionToken extends LocalDomain
{
    /**
     * Id technique de la donnée
     * @var int $id
     */
    protected $id;
    /**
     * Identifiant technique du Live
     * @var int $liveId
     */
    protected $liveId;
    /**
     * Identifiant technique du MissionToken
     * @var int $missionTokenId
     */
    protected $missionTokenId;
    /**
     * Statut du Token
     * @var string $status
     */
    protected $status;
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
    public function getMissionTokenId()
    { return $this->missionTokenId; }
    /**
     * @return string
     */
    public function getStatus()
    { return $this->status; }
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
     * @param int $missionTokenId
     */
    public function setMissionTokenId($missionTokenId)
    { $this->missionTokenId = $missionTokenId; }
    /**
     * @param string $status
     */
    public function setStatus($status)
    { $this->status = $status; }
    /**
     * @return array
     */
    public function getClassVars()
    { return get_class_vars('LiveMissionToken'); }
    /**
     * @param array $row
     * @param string $a
     * @param string $b
     * @return LiveMissionToken
     */
    public static function convertElement($row, $a='', $b='')
    { return parent::convertElement(new LiveMissionToken(), self::getClassVars(), $row); }

}
