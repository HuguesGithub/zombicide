<?php
if (!defined('ABSPATH')) {
  die('Forbidden');
}
/**
 * Classe MissionRuleServices
 * @author Hugues.
 * @version 1.0.00
 * @since 1.0.00
 */
class MissionRuleServices extends LocalServices
{
  /**
   * L'objet Dao pour faire les requêtes
   * @var MissionRuleDaoImpl $Dao
   */
  protected $Dao;
  /**
   * Class Constructor
   */
  public function __construct()
  {
    $this->Dao = new MissionRuleDaoImpl();
    parent::__construct();
  }

  private function buildFilters($arrFilters)
  {
    $arrParams = array();
    array_push($arrParams, !empty($arrFilters['missionId']) ? $arrFilters['missionId'] : '%');
    array_push($arrParams, $arrFilters['ruleId']!='' ? $arrFilters['ruleId'] : '%');
    array_push($arrParams, !empty($arrFilters['title']) ? $arrFilters['title'] : '%');
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
  public function getMissionRulesWithFilters($file, $line, $arrFilters=array(), $orderby='id', $order='asc')
  {
    $arrParams = $this->buildOrderAndLimit($orderby, $order);
    $arrParams[SQL_PARAMS_WHERE] = $this->buildFilters($arrFilters);
    return $this->Dao->selectEntriesWithFilters($file, $line, $arrParams);
  }
}
