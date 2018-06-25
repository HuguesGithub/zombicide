<?php
if ( !defined( 'ABSPATH') ) { die( 'Forbidden' ); }
/**
 * Classe MissionObjective
 * @author Hugues.
 * @version 1.0.00
 * @since 1.0.00
 */
class MissionObjective extends LocalDomain {
  /**
   * Id technique de la donnée
   * @var int $id
   */
  protected $id;
  /**
   * Id technique de la Mission
   * @var int $missionId
   */
  protected $missionId;
  /**
   * Id technique de l'Objectif
   * @var int $objectiveId
   */
  protected $objectiveId;
  /**
   * titre de la règle
   * @var string $title
   */
  protected $title;
  /**
   * @param array $attributes
   */
  public function __construct($attributes=array()) {
    parent::__construct($attributes, array('Objective'));
  }
  /**
   * @return int
   */
  public function getId() { return $this->id; }
  /**
   * @return int
   */
  public function getMissionId() { return $this->missionId; }
  /**
   * @return int
   */
  public function getObjectiveId() { return $this->objectiveId; }
  /**
   * @return string
   */
  public function getTitle() { return $this->title; }
  /**
   * @param int $id
   */
  public function setId($id) { $this->id = $id; }
  /**
   * @param int $missionId
   */
  public function setMissionId($missionId) { $this->missionId = $missionId; }
  /**
   * @param int $objectiveId
   */
  public function setObjectiveId($objectiveId) { $this->objectiveId = $objectiveId; }
  /**
   * @param string $title
   */
  public function setTitle($title) { $this->title = $title; }
  /**
   * @return array
   */
  public function getClassVars() { return get_class_vars('MissionObjective'); }
  /**
   * @param array $row
   * @param string $a
   * @param string $b
   * @return MissionRule
   */
  public static function convertElement($row, $a='', $b='') { return parent::convertElement(new MissionObjective(), self::getClassVars(), $row); }

  /**
   * @return string
   */
  public function getObjectiveCode() { return $this->getObjective()->getCode(); }
  /**
   * @return string
   */
  public function getObjectiveDescription() { return $this->getObjective()->getDescription(); }
  /**
   * @return string
   */
  public function getDescription() { return $this->getObjectiveDescription(); }
}
?>