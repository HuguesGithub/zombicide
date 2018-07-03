<?php
if (!defined('ABSPATH')) {
  die('Forbidden');
}
/**
 * Classe SurvivorServices
 * @author Hugues.
 * @version 1.0.00
 * @since 1.0.00
 */
class SurvivorServices extends LocalServices
{
  /**
   * L'objet Dao pour faire les requêtes
   * @var SurvivorDaoImpl $Dao
   */
  protected $Dao;
  /**
   * Class Constructor
   */
  public function __construct()
  { $this->Dao = new SurvivorDaoImpl(); }

  private function buildFilters($arrFilters)
  {
    $arrParams = array();
    array_push($arrParams, (!empty($arrFilters['name']) && !is_array($arrFilters['name'])) ? '%'.$arrFilters['name'].'%' : '%');
    array_push($arrParams, (!empty($arrFilters[self::CST_ZOMBIVOR]) && !is_array($arrFilters[self::CST_ZOMBIVOR])) ? $arrFilters[self::CST_ZOMBIVOR] : '%');
    array_push($arrParams, (!empty($arrFilters[self::CST_ULTIMATE]) && !is_array($arrFilters[self::CST_ULTIMATE])) ? '%'.$arrFilters[self::CST_ULTIMATE].'%' : '%');
    array_push($arrParams, (!empty($arrFilters[self::CST_EXPANSIONID]) && !is_array($arrFilters[self::CST_EXPANSIONID])) ? $arrFilters[self::CST_EXPANSIONID] : '%');
    array_push($arrParams, (!empty($arrFilters[self::CST_BACKGROUND]) && !is_array($arrFilters[self::CST_BACKGROUND])) ? $arrFilters[self::CST_BACKGROUND] : '%');
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
  public function getSurvivorsWithFilters($file, $line, $arrFilters=array(), $orderby='name', $order='asc')
  {
    $arrParams = $this->buildOrderAndLimit($orderby, $order);
    $arrParams[SQL_PARAMS_WHERE] = $this->buildFilters($arrFilters);
    return $this->Dao->selectEntriesWithFilters($file, $line, $arrParams);
  }
}
