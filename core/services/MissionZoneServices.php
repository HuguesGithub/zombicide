<?php
if (!defined('ABSPATH')) {
  die('Forbidden');
}
/**
 * Classe MissionZoneServices
 * @author Hugues
 * @since 1.0.01
 * @version 1.0.01
 */
class MissionZoneServices extends LocalServices
{
  /**
   * L'objet Dao pour faire les requêtes
   * @var MissionZoneDaoImpl $Dao
   */
  protected $Dao;
  /**
   * Class Constructor
   */
  public function __construct()
  {
    parent::__construct();
    $this->Dao = new MissionZoneDaoImpl();
  }

  private function buildFilters($arrFilters)
  {
    $arrParams = array();
    $arrParams[] = (isset($arrFilters[self::CST_MISSIONID]) ? $arrFilters[self::CST_MISSIONID] : '%');
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
  public function getMissionZonesWithFilters($file, $line, $arrFilters=array(), $orderby='id', $order='asc')
  {
    $arrParams = $this->buildOrderAndLimit($orderby, $order);
    $arrParams[SQL_PARAMS_WHERE] = $this->buildFilters($arrFilters);
    return $this->Dao->selectEntriesWithFilters($file, $line, $arrParams);
  }
}
