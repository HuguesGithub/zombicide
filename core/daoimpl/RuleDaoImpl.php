<?php
if (!defined('ABSPATH') ) { die('Forbidden' ); }
/**
 * Classe RuleDaoImpl
 * @author Hugues.
 * @version 1.0.00
 * @since 1.0.00
 */
class RuleDaoImpl extends LocalDaoImpl {
  /**
   * Corps de la requête de sélection
   * @var string $selectRequest
   */
  protected $selectRequest = "SELECT id, setting, code, description ";
  /**
   * Table concernée
   * @var string $fromRequest
   */
  protected $fromRequest = "FROM wp_11_zombicide_rule ";
  /**
   * Recherche avec filtres
   * @var string $whereFilters
   */
  protected $whereFilters = "WHERE setting LIKE '%s' AND code LIKE '%s' AND description LIKE '%s' ";
  /**
   * Requête d'insertion en base
   * @var string $insert
   */
  protected $insert = "INSERT INTO wp_11_zombicide_rule (setting, code, description) VALUES ('%s', '%s', '%s');";
  /**
   * Requête de mise à jour en base
   * @var string $update
   */
  protected $update = "UPDATE wp_11_zombicide_rule SET setting='%s', code='%s', description='%s' ";
  
  public function __construct() {}
  /**
   * @param array $rows
   * @return array
   */
  protected function convertToArray($rows) { return $this->globalConvertToArray('Rule', $rows); }
  /**
   * @param string $file
   * @param int $line
   * @param array $arrParams
   * @return array|Rule
   */
  public function select($file, $line, $arrParams) {
    $Objs = $this->selectEntry($file, $line, $arrParams);
    return (empty($Objs) ? new Rule() : array_shift($Objs));
  }

}
?>