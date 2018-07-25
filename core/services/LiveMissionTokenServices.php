<?php
if (!defined('ABSPATH')) {
  die('Forbidden');
}
/**
 * Classe LiveMissionTokenServices
 * @author Hugues.
 * @version 1.0.00
 * @since 1.0.00
 */
class LiveMissionTokenServices extends LocalServices
{
  /**
   * L'objet Dao pour faire les requêtes
   * @var LiveMissionTokenDaoImpl $Dao
   */
  protected $Dao;
  /**
   * Class Constructor
   */
  public function __construct()
  {
    parent::__construct();
    $this->Dao = new LiveMissionTokenDaoImpl();
  }

  /**
   * @param string $file
   * @param string $line
   * @param array $arrFilters
   * @param string $orderby
   * @param string $order
   * @return array
   */
  public function getLiveMissionTokensWithFilters($file, $line, $arrFilters=array(), $orderby='id', $order='asc')
  {
    $arrParams = $this->buildOrderAndLimit($orderby, $order);
    return $this->Dao->selectEntriesWithFilters($file, $line, $arrParams);
  }
  
}
