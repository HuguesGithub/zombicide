<?php
if (!defined('ABSPATH')) {
  die('Forbidden');
}
/**
 * Classe LiveSurvivorSkillServices
 * @author Hugues.
 * @version 1.0.00
 * @since 1.0.00
 */
class LiveSurvivorSkillServices extends LocalServices
{
  /**
   * L'objet Dao pour faire les requêtes
   * @var LiveSurvivorSkillDaoImpl $Dao
   */
  protected $Dao;
  /**
   * Clas Constructor
   */
  public function __construct()
  {
    parent::__construct();
    $this->Dao = new LiveSurvivorSkillDaoImpl();
  }

  private function buildFilters($arrFilters)
  {
    $arrParams = array();
    $arrParams[] = (isset($arrFilters['survivorId']) ? $arrFilters['survivorId'] : '%');
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
  public function getLiveSurvivorSkillsWithFilters($file, $line, $arrFilters=array(), $orderby='id', $order='asc')
  {
    $arrParams = $this->buildOrderAndLimit($orderby, $order);
    $arrParams[SQL_PARAMS_WHERE] = $this->buildFilters($arrFilters);  
    return $this->Dao->selectEntriesWithFilters($file, $line, $arrParams);
  }

}
