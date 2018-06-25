<?php
if (!defined('ABSPATH') ) { die('Forbidden' ); }
/**
 * Classe MissionExpansionDaoImpl
 * @author Hugues.
 * @version 1.0.00
 * @since 1.0.00
 */
class MissionExpansionDaoImpl extends LocalDaoImpl {
  /**
   * Corps de la requête de sélection
   * @var string $selectRequest
   */
  protected $selectRequest = "SELECT id, missionId, expansionId ";
  /**
   * Table concernée
   * @var string $fromRequest
   */
  protected $fromRequest = "FROM wp_11_zombicide_mission_expansion ";
  /**
   * Recherche avec filtres
   * @var string $whereFilters
   */
  protected $whereFilters = "WHERE missionId LIKE '%s' AND expansionId LIKE '%s' ";
  /**
   * Requête d'insertion en base
   * @var string $insert
   */
  protected $insert = "INSERT INTO wp_11_zombicide_mission_expansion (missionId, expansionId) VALUES ('%s', '%s');";
  /**
   * Requête de mise à jour en base
   * @var string $update
   */
  protected $update = "UPDATE wp_11_zombicide_mission_expansion SET missionId='%s', expansionId='%s' ";
  
  public function __construct() {}
  /**
   * @param array $rows
   * @return array
   */
  protected function convertToArray($rows) { return $this->globalConvertToArray('MissionExpansion', $rows); }
  /**
   * @param string $file
   * @param int $line
   * @param array $arrParams
   * @return array|MissionExpansion
   */
  public function select($file, $line, $arrParams) {
    $MissionExpansions = $this->selectEntry($file, $line, $arrParams);
    return (empty($MissionExpansions) ? new MissionExpansion() : array_shift($MissionExpansions));
  }
  
}
?>