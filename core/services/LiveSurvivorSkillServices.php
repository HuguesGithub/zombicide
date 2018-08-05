<?php
if (!defined('ABSPATH')) {
  die('Forbidden');
}
/**
 * Classe LiveSurvivorSkillServices
 * @since 1.0.00
 * @version 1.0.01
 * @author Hugues
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
    $arrParams[] = (isset($arrFilters[self::CST_LIVESURVIVORID]) ? $arrFilters[self::CST_LIVESURVIVORID] : '%');
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
