<?php
if (!defined('ABSPATH') ) { die('Forbidden' ); }
/**
 * Classe SurvivorDaoImpl
 * @author Hugues.
 * @version 1.0.00
 * @since 1.0.00
 */
class SurvivorDaoImpl extends LocalDaoImpl {
  /**
   * Corps de la requête de sélection
   * @var string $selectRequest
   */
  protected $selectRequest = "SELECT id, name, zombivor, ultimate, expansionId, background, altImgName ";
  /**
   * Table concernée
   * @var string $fromRequest
   */
  protected $fromRequest = "FROM wp_11_zombicide_survivor ";
  /**
   * Recherche avec filtres
   * @var string $whereFilters
   */
  protected $whereFilters = "WHERE name LIKE '%s' AND zombivor LIKE '%s' AND ultimate LIKE '%s' AND expansionId LIKE '%s' and background LIKE '%s' ";
  /**
   * Requête d'insertion en base
   * @var string $insert
   */
  protected $insert = "INSERT INTO wp_11_zombicide_survivor (name, zombivor, ultimate, expansionId, background, altImgName) VALUES ('%s', '%s', '%s', '%s', '%s', '%s');";
  /**
   * Requête de mise à jour en base
   * @var string $update
   */
  protected $update = "UPDATE wp_11_zombicide_survivor SET name='%s', zombivor='%s', ultimate='%s', expansionId='%s', background='%s', altImgName='%s' ";

  public function __construct() {}
  /**
   * @param array $rows
   * @return array
   */
  protected function convertToArray($rows) { return $this->globalConvertToArray('Survivor', $rows); }
  /**
   * @param string $file
   * @param int $line
   * @param array $arrParams
   * @return array|Skill
   */
  public function select($file, $line, $arrParams) {
    $Objs = $this->selectEntry($file, $line, $arrParams);
    return (empty($Objs) ? new Survivor() : array_shift($Objs));
  }
  
}
?>