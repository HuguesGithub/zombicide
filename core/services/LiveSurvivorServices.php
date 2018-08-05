<?php
if (!defined('ABSPATH')) {
  die('Forbidden');
}
/**
 * Classe LiveSurvivorServices
 * @since 1.0.00
 * @version 1.0.01
 * @author Hugues
 */
class LiveSurvivorServices extends LocalServices
{
  /**
   * L'objet Dao pour faire les requêtes
   * @var LiveSurvivorDaoImpl $Dao
   */
  protected $Dao;
  /**
   * Clas Constructor
   */
  public function __construct()
  {
    parent::__construct();
    $this->Dao = new LiveSurvivorDaoImpl();
  }

  private function buildFilters($arrFilters)
  {
    $arrParams = array();
    $arrParams[] = (isset($arrFilters[self::CST_LIVEID]) ? $arrFilters[self::CST_LIVEID] : '%');
    $arrParams[] = (isset($arrFilters['survivorId']) ? $arrFilters['survivorId'] : '%');
    $arrParams[] = (isset($arrFilters['missionZoneId']) ? $arrFilters['missionZoneId'] : '%');
    $arrParams[] = (isset($arrFilters['playedThisTurn']) ? $arrFilters['playedThisTurn'] : '%');
    $arrParams[] = (isset($arrFilters['turnRank']) ? $arrFilters['turnRank'] : '%');
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
  public function getLiveSurvivorsWithFilters($file, $line, $arrFilters=array(), $orderby='id', $order='asc')
  {
    $arrParams = $this->buildOrderAndLimit($orderby, $order);
    $arrParams[SQL_PARAMS_WHERE] = $this->buildFilters($arrFilters);
    return $this->Dao->selectEntriesWithFilters($file, $line, $arrParams);
  }

}
