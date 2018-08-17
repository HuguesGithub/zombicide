<?php
if (!defined('ABSPATH')) {
  die('Forbidden');
}
/**
 * Classe LiveSurvivorSkill
 * @author Hugues.
 * @version 1.0.00
 * @since 1.0.00
 */
class LiveSurvivorSkill extends LocalDomain
{
  /**
   * Id technique de la donnée
   * @var int $id
   */
  protected $id;
  /**
   * Id technique du Survivant
   * @var int $liveSurvivorId
   */
  protected $liveSurvivorId;
  /**
   * Id technique du Skill
   * @var int $skillId
   */
  protected $skillId;
  /**
   * Id technique du TagLevel
   * @var int $tagLevelId
   */
  protected $tagLevelId;
  /**
   * Débloquée ou non ?
   * @var int $locked
   */
  protected $locked;
  /**
   * @param array $attributes
   */
  public function __construct($attributes=array())
  {
    parent::__construct($attributes);
    $this->SkillServices = new SkillServices();
  }
  /**
   * @return int
   */
  public function getId()
  { return $this->id; }
  /**
   * @return int
   */
  public function getLiveSurvivorId()
  { return $this->liveSurvivorId; }
  /**
   * @return int
   */
  public function getSkillId()
  { return $this->skillId; }
  /**
   * @return int
   */
  public function getTagLevelId()
  { return $this->tagLevelId; }
  /**
   * @return boolean
   */
  public function isLocked()
  { return ($this->locked==1); }
  /**
   * @param int $id
   */
  public function setId($id)
  { $this->id = $id; }
  /**
   * @param int $liveSurvivorId
   */
  public function setLiveSurvivorId($liveSurvivorId)
  { $this->liveSurvivorId = $liveSurvivorId; }
  /**
   * @param int $skillId
   */
  public function setSkillId($skillId)
  { $this->skillId = $skillId; }
  /**
   * @param int $tagLevelId
   */
  public function setTagLevelId($tagLevelId)
  { $this->tagLevelId = $tagLevelId; }
  /**
   * @param int $locked
   */
  public function setLocked($locked)
  { $this->locked = $locked; }
  /**
   * @return array
   */
  public function getClassVars()
  { return get_class_vars('LiveSurvivorSkill'); }
  /**
   * @param array $row
   * @param string $a
   * @param string $b
   * @return LiveSurvivorSkill
   */
  public static function convertElement($row, $a='', $b='')
  { return parent::convertElement(new LiveSurvivorSkill(), self::getClassVars(), $row); }
  /**
   * @return Skill
   */
  public function getSkill()
  {
    if ($this->Skill==null) {
      $this->Skill = $this->SkillServices->select(__FILE__, __LINE__, $this->skillId);
    }
    return $this->Skill;
  }
  /**
   * @return boolean
   */
  public function isStartsWith()
  { return (strpos($this->getSkill()->getCode(), 'STARTS_WITH')!==false); }
  /**
   * @return string
   */
  public function getSkillName()
  { return $this->getSkill()->getName(); }
}
