<?php
if (!defined('ABSPATH')) {
  die('Forbidden');
}
/**
 * Classe SkillDaoImpl
 * @author Hugues.
 * @version 1.0.00
 * @since 1.0.00
 */
class SkillDaoImpl extends LocalDaoImpl
{
  /**
   * Class constructor
   */
  public function __construct()
  {
    parent::__construct('Skill');
  }
  /**
   * @param array $rows
   * @return array
   */
  protected function convertToArray($rows)
  { return $this->globalConvertToArray('Skill', $rows); }
  /**
   * @param string $file
   * @param int $line
   * @param array $arrParams
   * @return array|Skill
   */
  public function select($file, $line, $arrParams)
  {
    $Skills = $this->selectEntry($file, $line, $arrParams);
    return (empty($Skills) ? new Skill() : array_shift($Skills));
  }
  
}
