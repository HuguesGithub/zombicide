<?php
if ( !defined( 'ABSPATH') ) { die( 'Forbidden' ); }
/**
 * Classe TileServices
 * @author Hugues.
 * @version 1.0.00
 * @since 1.0.00
 */
class TileServices extends LocalServices {
  /**
   * L'objet Dao pour faire les requêtes
   * @var TileDaoImpl $Dao
   */
  protected $Dao;
  
  public function __construct() { $this->Dao = new TileDaoImpl(); }

  private function buildFilters($arrFilters) {
    $arrParams = array();
    $arrParams[] = ( isset($arrFilters['code']) ? $arrFilters['code'] : '%');
    $arrParams[] = ( isset($arrFilters['expansionId']) ? $arrFilters['expansionId'] : '%');
    $arrParams[] = ( isset($arrFilters['active']) ? $arrFilters['active'] : '%');
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
  public function getTilesWithFilters($file, $line, $arrFilters=array(), $orderby='code', $order='asc') {
    $arrParams = $this->buildOrderAndLimit($orderby, $order);
    $arrParams[SQL_PARAMS_WHERE] = $this->buildFilters($arrFilters);
    return $this->Dao->selectEntriesWithFilters($file, $line, $arrParams);
  }
  /**
   * @param string $file
   * @param string $line
   * @param string $value
   * @param string $prefix
   * @param string $classe
   * @param int $multiple
   * @param string $defaultLabel
   * @return string
   */
  public function getTilesSelect($file, $line, $value='', $prefix='', $classe='form-control', $multiple=FALSE, $defaultLabel='') {
  if ( empty($this->MissionTiles) ) {
      $MissionTiles = $this->getTilesWithFilters($file, $line, array(), 'id', 'asc');
    $this->MissionTiles = $MissionTiles;
  }
    $arrSetLabels = array();
    foreach ( $this->MissionTiles as $MissionTile ) {
      $arrSetLabels[$MissionTile->getId()] = $MissionTile->getCode();
    }
    return $this->getSetSelect($file, $line, $arrSetLabels, $prefix.'id', $value, $defaultLabel, $classe, $multiple);
  }
  
}
?>