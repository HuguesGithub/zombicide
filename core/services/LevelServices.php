<?php
if (!defined('ABSPATH')) {
  die('Forbidden');
}
/**
 * Classe LevelServices
 * @author Hugues.
 * @version 1.0.00
 * @since 1.0.00
 */
class LevelServices extends LocalServices
{
  /**
   * L'objet Dao pour faire les requêtes
   * @var LevelDaoImpl $Dao
   */
  protected $Dao;
  /**
   * Class Constructor
   */
  public function __construct()
  {
    parent::__construct();
    $this->Dao = new LevelDaoImpl();
  }

  /**
   * @param string $file
   * @param string $line
   * @param array $arrFilters
   * @param string $orderby
   * @param string $order
   * @return array
   */
  public function getLevelsWithFilters($file, $line, $arrFilters=array(), $orderby='id', $order='asc')
  {
    $arrParams = $this->buildOrderAndLimit($orderby, $order);
    return $this->Dao->selectEntriesWithFilters($file, $line, $arrParams);
  }
  /**
   * @param string $file
   * @param string $line
   * @param string $value
   * @param string $prefix
   * @param string $classe
   * @param bool $multiple
   * @param string $defaultLabel
   * @return string
   */
  public function getLevelsSelect($file, $line, $value='', $prefix='', $classe='form-control', $multiple=false, $defaultLabel='')
  {
    $Levels = $this->getLevelsWithFilters($file, $line);
    $this->labelDefault = $defaultLabel;
    $this->classe = $classe;
    $this->multiple = $multiple;
    return $this->getLevelsSelectAlreadyRequested($file, $line, $Levels, $value, $prefix);
  }
  /**
   * @param string $file
   * @param string $line
   * @param array $Levels
   * @param string $value
   * @param string $prefix
   * @param string $classe
   * @param string $multiple
   * @param string $defaultLabel
   * @return string
   */
  public function getLevelsSelectAlreadyRequested($file, $line, $Levels, $value, $prefix)
  {
    $arrSetLabels = array();
    foreach ($Levels as $Level) {
      $arrSetLabels[$Level->getId()] = $Level->getName();
    }
    $this->labelDefault = $defaultLabel;
    $this->classe = $classe;
    $this->multiple = $multiple;
    return $this->getSetSelect($file, $line, $arrSetLabels, $prefix, $value);
  }
}
