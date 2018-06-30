<?php
if (!defined('ABSPATH')) {
  die('Forbidden');
}
/**
 * Classe MissionExpansion
 * @author Hugues.
 * @version 1.0.00
 * @since 1.0.00
 */
class MissionExpansion extends LocalDomain
{
  /**
   * Id technique de la jointure
   * @var int $id
   */
  protected $id;
  /**
   * Id technique de la Mission
   * @var int $missionId
   */
  protected $missionId;
  /**
   * Id technique de l'Expansion
   * @var int $expansionId
   */
  protected $expansionId;
  /**
   * @param array $attributes
   */
  public function __construct($attributes=array())
  {
    $services = array('Expansion', 'Mission');
    parent::__construct($attributes, $services);
  }
  /**
   * @return int
   */
  public function getId()
  {return $this->id; }
  /**
   * @ return int
   */
  public function getMissionId()
  { return $this->MissionId; }
  /**
   * @ return int
   */
  public function getExpansionId()
  { return $this->expansionId; }
  /**
   * @param int $id
   */
  public function setId($id)
  { $this->id=$id; }
  /**
   * @param int $missionId
   */
  public function setMissionId($missionId)
  { $this->missionId = $missionId; }
  /**
   * @param int $expansionId
   */
  public function setExpansionId($expansionId)
  { $this->expansionId = $expansionId; }
  /**
   * @return array
   */
  public function getClassVars()
  { return get_class_vars('MissionExpansion'); }
  /**
   * @param array $row
   * @param string $a
   * @param string $b
   * @return MissionExpansion
   */
  public static function convertElement($row, $a='', $b='')
  { return parent::convertElement(new MissionExpansion(), self::getClassVars(), $row); }
  /**
   * @return string
   */
  public function getExpansionCode()
  { return $this->getExpansion()->getCode(); }
  /**
   * @return string
   */
  public function getExpansionName()
  { return $this->getExpansion()->getName(); }
  /**
   * @return string
   */
  public function getExpansionImageSuffixe()
  { return $this->getExpansion()->getImageSuffixe(); }
  /**
   * @param Expansion $Expansion
   */
  public function setExpansion($Expansion)
  { $this->Expansion=$Expansion; }

}
