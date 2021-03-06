<?php
if (!defined('ABSPATH')) {
  die('Forbidden');
}
/**
 * Classe TokenServices
 * @author Hugues.
 * @version 1.0.00
 * @since 1.0.00
 */
class TokenServices extends LocalServices
{
  /**
   * L'objet Dao pour faire les requêtes
   * @var TileDaoImpl $Dao
   */
  protected $Dao;
  /**
   * Class Constructor
   */
  public function __construct()
  {
    parent::__construct();
    $this->Dao = new TokenDaoImpl();
  }

  private function buildFilters($arrFilters)
  {
    $arrParams = array();
    $arrParams[] = (isset($arrFilters['code']) ? $arrFilters['code'] : '%');
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
  public function getTokensWithFilters($file, $line, $arrFilters=array(), $orderby='code', $order='asc')
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
   * @param int $multiple
   * @param string $defaultLabel
   * @return string
   */
  public function getTokensSelect($file, $line, $value='', $prefix='', $classe='form-control', $multiple=false, $defaultLabel='')
  {
    $Tokens = $this->getTokensWithFilters($file, $line, array(), 'id', 'asc');
    $arrSetLabels = array();
    foreach ($Tokens as $Token) {
      $arrSetLabels[$Token->getId()] = $Token->getCode();
    }
    $this->labelDefault = $defaultLabel;
    $this->classe = $classe;
    $this->multiple = $multiple;
    return $this->getSetSelect($file, $line, $arrSetLabels, $prefix.'id', $value);
  }
}
