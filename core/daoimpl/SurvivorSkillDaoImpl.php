<?php
if (!defined('ABSPATH')) {
  die('Forbidden');
}
/**
 * Classe SurvivorSkillDaoImpl
 * @author Hugues.
 * @version 1.0.00
 * @since 1.0.00
 */
class SurvivorSkillDaoImpl extends LocalDaoImpl
{
  /**
   * Corps de la requête de sélection
   * @var string $selectRequest
   */
  protected $selectRequest = "SELECT id, survivorId, skillId, survivorTypeId, tagLevelId ";
  /**
   * Table concernée
   * @var string $fromRequest
   */
  protected $fromRequest = "FROM wp_11_zombicide_survivor_skill ";
  /**
   * Recherche avec filtres
   * @var string $whereFilters
   */
  protected $whereFilters = "WHERE survivorId LIKE '%s' AND skillId LIKE '%s' AND survivorTypeId LIKE '%s' AND tagLevelId LIKE '%s' ";
  /**
   * Requête d'insertion en base
   * @var string $insert
   */
  protected $insert = "INSERT INTO wp_11_zombicide_survivor_skill (survivorId, skillId, survivorTypeId, tagLevelId) VALUES ('%s', '%s', '%s', '%s');";
  /**
   * Requête de mise à jour en base
   * @var string $update
   */
  protected $update = "UPDATE wp_3_z_survivor_skill SET survivorId='%s', skillId='%s', survivorTypeId='%s', tagLevelId='%s' ";

  public function __construct() {}
  /**
   * @param array $rows
   * @return array
   */
  protected function convertToArray($rows)
  { return $this->globalConvertToArray('SurvivorSkill', $rows); }
  /**
   * @param string $file
   * @param int $line
   * @param array $arrParams
   * @return array|SurvivorSkill
   */
  public function select($file, $line, $arrParams)
  {
    $Objs = $this->selectEntry($file, $line, $arrParams);
    return (empty($Objs) ? new SurvivorSkill() : array_shift($Objs));
  }

}
