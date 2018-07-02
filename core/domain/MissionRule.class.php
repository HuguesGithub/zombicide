
<?php
if (!defined('ABSPATH')) {
  die('Forbidden');
}
/**
 * Classe MissionRule
 * @author Hugues.
 * @version 1.0.00
 * @since 1.0.00
 */
class MissionRule extends LocalDomain
{
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
   * Id technique de la Règle
   * @var int $ruleId
   */
  protected $ruleId;
  /**
   * titre de la règle
   * @var string $title
   */
  protected $title;
  /**
   * @param array $attributes
   */
  public function __construct($attributes=array())
  {
    parent::__construct($attributes, array('Rule'));
  }
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
  public function getRuleId()
  { return $this->ruleId; }
  /**
   * @return string
   */
  public function getTitle()
  { return $this->title; }
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
   * @param int $ruleId
   */
  public function setRuleId($ruleId)
  { $this->ruleId = $ruleId; }
  /**
   * @param string $title
   */
  public function setTitle($title)
  { $this->title = $title; }
  /**
   * @return array
   */
  public function getClassVars()
  { return get_class_vars('MissionRule'); }
  /**
   * @param array $row
   * @param string $a
   * @param string $b
   * @return MissionRule
   */
  public static function convertElement($row, $a='', $b='')
  { return parent::convertElement(new MissionRule(), self::getClassVars(), $row); }

  /**
   * @return int
   */
  public function getRuleSetting()
  { return $this->getRule()->getSetting(); }
  /**
   * @return string
   */
  public function getRuleCode()
  { return $this->getRule()->getCode(); }
  /**
   * @return string
   */
  public function getRuleDescription()
  { return $this->getRule()->getDescription(); }
  /**
   * @return string
   */
  public function getDescription()
  { return $this->getRuleDescription(); }
}
