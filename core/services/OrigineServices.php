<?php
if (!defined('ABSPATH')) {
  die('Forbidden');
}
/**
 * Classe OrigineServices
 * @author Hugues.
 * @version 1.0.00
 * @since 1.0.00
 */
class OrigineServices extends LocalServices
{
  /**
   * L'objet Dao pour faire les requêtes
   * @var OrigineDaoImpl $Dao
   */
  protected $Dao;
  /**
   * Class Constructor
   */
  public function __construct()
  {
    $this->Dao = new OrigineDaoImpl();
    parent::__construct();
  }

  private function buildFilters($arrFilters)
  {
    $arrParams = array();
    $arrParams[] = (isset($arrFilters['name']) ? $arrFilters['name'] : '%');
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
  public function getOriginesWithFilters($file, $line, $arrFilters=array(), $orderby='name', $order='asc')
  {
    $arrParams = $this->buildOrderAndLimit($orderby, $order);
    $arrParams[SQL_PARAMS_WHERE] = $this->buildFilters($arrFilters);
    return $this->Dao->selectEntriesWithFilters($file, $line, $arrParams);
  }
  /**
   * @param string $file
   * @param string $line
   * @param string $value
   * @param string $prefix
   * @param string $classe
   * @param string $multiple
   * @param string $defaultLabel
   * @return string
   */
  public function getOriginesSelect($file, $line, $value='', $prefix='', $classe='form-control', $multiple=false, $defaultLabel='')
  {
    $Origines = $this->getOriginesWithFilters($file, $line, array(), 'name', 'ASC');
    $arrSetLabels = array();
    foreach ($Origines as $Origine) {
      $arrSetLabels[$Origine->getId()] = $Origine->getName();
    }
    return $this->getSetSelect($file, $line, $arrSetLabels, $prefix.'origineId', $value, $defaultLabel, $classe, $multiple);
  }
}
