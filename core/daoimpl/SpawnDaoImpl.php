<?php
if (!defined('ABSPATH') ) { die('Forbidden' ); }
/**
 * Classe SpawnDaoImpl
 * @author Hugues.
 * @version 1.0.00
 * @since 1.0.00
 */
class SpawnDaoImpl extends LocalDaoImpl {
  /**
   * Corps de la requête de sélection
   * @var string $selectRequest
   */
  protected $selectRequest = "SELECT id, expansionId, spawnNumber, spawnTypeId, zombieCategoryId, blueZombieTypeId, blueQuantity, yellowZombieTypeId, yellowQuantity, orangeZombieTypeId, orangeQuantity, redZombieTypeId, redQuantity ";
  /**
   * Table concernée
   * @var string $fromRequest
   */
  protected $fromRequest = "FROM wp_11_zombicide_spawncards ";
  /**
   * Recherche avec filtres
   * @var string $whereFilters
   */
  protected $whereFilters = "WHERE expansionId LIKE '%s' AND spawnNumber LIKE '%s' ";
  /**
   * Requête d'insertion en base
   * @var string $insert
   */
  protected $insert = "INSERT INTO wp_11_zombicide_spawncards (expansionId, spawnNumber, spawnTypeId, zombieCategoryId, blueZombieTypeId, blueQuantity, yellowZombieTypeId, yellowQuantity, orangeZombieTypeId, orangeQuantity, redZombieTypeId, redQuantity) VALUES ('%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s');";
  /**
   * Requête de mise à jour en base
   * @var string $update
   */
  protected $update = "UPDATE wp_11_zombicide_spawncards SET expansionId='%s', spawnNumber='%s', spawnTypeId='%s', zombieCategoryId='%s', blueZombieTypeId='%s', blueQuantity='%s', yellowZombieTypeId='%s', yellowQuantity='%s', orangeZombieTypeId='%s', orangeQuantity='%s', redZombieTypeId='%s', redQuantity='%s' ";

  public function __construct() {}
  /**
   * @param array $rows
   * @return array
   */
  protected function convertToArray($rows) { return $this->globalConvertToArray('Spawn', $rows); }
  /**
   * @param string $file
   * @param int $line
   * @param array $arrParams
   * @return array|Spawn
   */
  public function select($file, $line, $arrParams) {
    $Objs = $this->selectEntry($file, $line, $arrParams);
    return (empty($Objs) ? new Spawn() : array_shift($Objs));
  }
  
}
?>