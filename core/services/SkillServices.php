<?php
if (!defined('ABSPATH')) {
  die('Forbidden');
}
/**
 * Classe SkillServices
 * @author Hugues.
 * @version 1.0.00
 * @since 1.0.00
 */
class SkillServices extends LocalServices
{
  /**
   * L'objet Dao pour faire les requêtes
   * @var SkillDaoImpl $Dao
   */
  protected $Dao;
  /**
   * Class Constructor
   */
  public function __construct()
  { $this->Dao = new SkillDaoImpl(); }

  private function buildFilters($arrFilters)
  {
    $arrParams = array();
    array_push($arrParams, (!empty($arrFilters['code']) && !is_array($arrFilters['code'])) ? $arrFilters['code'] : '%');
    array_push($arrParams, (!empty($arrFilters['name']) && !is_array($arrFilters['name'])) ? '%'.$arrFilters['name'].'%' : '%');
    array_push($arrParams, ($this->isNonEmptyAndNoArray($arrFilters, self::CST_DESCRIPTION]) ? '%'.$arrFilters[self::CST_DESCRIPTION].'%' : '%');
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
  public function getSkillsWithFilters($file, $line, $arrFilters=array(), $orderby='name', $order='asc')
  {
    $arrParams = $this->buildOrderAndLimit($orderby, $order);
    $arrParams[SQL_PARAMS_WHERE] = $this->buildFilters($arrFilters);
    return $this->Dao->selectEntriesWithFilters($file, $line, $arrParams);
  }
}
