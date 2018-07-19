<?php
if (!defined('ABSPATH')) {
  die('Forbidden');
}
/**
 * Classe DurationServices
 * @author Hugues.
 * @version 1.0.00
 * @since 1.0.00
 */
class DurationServices extends LocalServices
{
  /**
   * L'objet Dao pour faire les requêtes
   * @var DurationDaoImpl $Dao
   */
  protected $Dao;
  
  public function __construct()
  {
    parent::__construct();
    $this->Dao = new DurationDaoImpl();
  }

  private function buildFilters($arrFilters)
  {
    $arrParams = array();
    $arrParams[] = (isset($arrFilters[self::CST_MINDURATION]) ? $arrFilters[self::CST_MINDURATION] : '%');
    $arrParams[] = (isset($arrFilters['maxDuration']) ? $arrFilters['maxDuration'] : '%');
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
  public function getDurationsWithFilters($file, $line, $arrFilters=array(), $orderby='id', $order='asc')
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
   * @param bool $multiple
   * @param string $defaultValue
   * @return string
   */
  public function getDurationsSelect($file, $line, $value='', $prefix='', $classe='form-control', $multiple=false, $defaultValue='')
  {
    $Durations = $this->getDurationsWithFilters($file, $line, array(), self::CST_MINDURATION, 'asc');
    $arrSetLabels = array();
    foreach ($Durations as $Duration) {
      $arrSetLabels[$Duration->getId()] = $Duration->getStrDuree();
    }
    $this->labelDefault = $defaultValue;
    $this->classe = $classe;
    $this->multiple = $multiple;
    return $this->getSetSelect($file, $line, $arrSetLabels, $prefix.self::CST_DURATIONID, $value);
  }
  
}
