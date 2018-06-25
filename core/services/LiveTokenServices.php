<?php
if (!defined('ABSPATH') ) { die('Forbidden' ); }
/**
 * Classe LiveTokenServices
 * @author Hugues.
 * @version 1.0.00
 * @since 1.0.00
 */
class LiveTokenServices extends LocalServices {
  /**
   * L'objet Dao pour faire les requêtes
   * @var DurationDaoImpl $Dao
   */
  protected $Dao;
  
  public function __construct() { $this->Dao = new LiveTokenDaoImpl(); }

  /**
   * @param string $file
   * @param string $line
   * @param array $arrFilters
   * @param string $orderby
   * @param string $order
   * @return array
   */
  public function getLiveTokensWithFilters($file, $line, $arrFilters=array(), $orderby='id', $order='asc') {
    $arrParams = $this->buildOrderAndLimit($orderby, $order);
    return $this->Dao->selectEntriesWithFilters($file, $line, $arrParams);
  }
  
}
?>