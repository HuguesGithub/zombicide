<?php
if (!defined('ABSPATH')) { die('Forbidden'); }
/**
 * Classe SpawnLiveDeckServices
 * @author Hugues.
 * @version 1.0.00
 * @since 1.0.00
 */
class SpawnLiveDeckServices extends LocalServices {
  /**
   * L'objet Dao pour faire les requêtes
   * @var LiveDeckDaoImpl $Dao
   */
  protected $Dao;
  
  public function __construct() { $this->Dao = new SpawnLiveDeckDaoImpl(); }

  private function buildFilters($arrFilters) {
    $arrParams = array();
    $arrParams[] = (isset($arrFilters['liveDeckId']) ? $arrFilters['liveDeckId'] : '%');
    $arrParams[] = (isset($arrFilters['spawnCardId']) ? $arrFilters['spawnCardId'] : '%');
    $arrParams[] = (isset($arrFilters['status']) ? $arrFilters['status'] : '%');
    return $arrParams;
  }
  /**
   * @param string $file
   * @param string $line
   * @param array $arrFilters
   * @param string $orderby
   * @param string $order
   * @return array
   */
  public function getSpawnLiveDecksWithFilters($file, $line, $arrFilters=array(), $orderby='id', $order='asc') {
    $arrParams = $this->buildOrderAndLimit($orderby, $order);
    $arrParams[SQL_PARAMS_WHERE] = $this->buildFilters($arrFilters);
    return $this->Dao->selectEntriesWithFilters($file, $line, $arrParams);
  }
  
}
?>