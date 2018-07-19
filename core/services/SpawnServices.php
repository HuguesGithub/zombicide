<?php
if (!defined('ABSPATH')) {
  die('Forbidden');
}
/**
 * Classe SpawnServices
 * @author Hugues.
 * @version 1.0.00
 * @since 1.0.00
 */
class SpawnServices extends LocalServices
{
  /**
   * L'objet Dao pour faire les requêtes
   * @var SpawnDaoImpl $Dao
   */
  protected $Dao;
  /**
   * Class Constructor
   */
  public function __construct()
  {
    parent::__construct();
    $this->Dao = new SpawnDaoImpl();
  }

  private function buildFilters($arrFilters)
  {
    $arrParams = array();
    array_push($arrParams, ($this->isNonEmptyAndNoArray($arrFilters, self::CST_EXPANSIONID) ? $arrFilters[self::CST_EXPANSIONID] : '%'));
    array_push($arrParams, ($this->isNonEmptyAndNoArray($arrFilters, self::CST_SPAWNNUMBER) ? $arrFilters[self::CST_SPAWNNUMBER] : '%'));
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
  public function getSpawnsWithFilters($file, $line, $arrFilters=array(), $orderby=self::CST_SPAWNNUMBER, $order='asc')
  {
    $arrParams = $this->buildOrderAndLimit($orderby, $order);
    $arrParams[SQL_PARAMS_WHERE] = $this->buildFilters($arrFilters);
    return $this->Dao->selectEntriesWithFilters($file, $line, $arrParams);
  }
}
