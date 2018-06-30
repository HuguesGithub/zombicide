<?php
if (!defined('ABSPATH')) { die('Forbidden'); }
/**
 * Classe LiveServices
 * @author Hugues.
 * @version 1.0.00
 * @since 1.0.00
 */
class LiveServices extends LocalServices {
  /**
   * L'objet Dao pour faire les requêtes
   * @var LiveDeckDaoImpl $Dao
   */
  protected $Dao;
  
  public function __construct() { $this->Dao = new LiveDaoImpl(); }

  private function buildFilters($arrFilters) {
    $arrParams = array();
    $arrParams[] = (isset($arrFilters['deckKey']) ? $arrFilters['deckKey'] : '%');
    $arrParams[] = (isset($arrFilters['dateUpdate']) ? $arrFilters['dateUpdate'] : '9999-99-99');
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
  public function getLivesWithFilters($file, $line, $arrFilters=array(), $orderby='id', $order='asc') {
    $arrParams = $this->buildOrderAndLimit($orderby, $order);
    $arrParams[SQL_PARAMS_WHERE] = $this->buildFilters($arrFilters);
    return $this->Dao->selectEntriesWithFilters($file, $line, $arrParams);
  }
  
}
?>