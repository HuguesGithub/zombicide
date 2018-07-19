<?php
if (!defined('ABSPATH')) {
  die('Forbidden');
}
/**
 * Classe EquipmentLiveDeckServices
 * @author Hugues.
 * @version 1.0.00
 * @since 1.0.00
 */
class EquipmentLiveDeckServices extends LocalServices
{
  /**
   * L'objet Dao pour faire les requêtes
   * @var EquipmentDeckDaoImpl $Dao
   */
  protected $Dao;
  /**
   * Class Constructor
   */
  public function __construct()
  {
    parent::__construct();
    $this->Dao = new EquipmentLiveDeckDaoImpl();
  }

  private function buildFilters($arrFilters)
  {
    $arrParams = array();
    $arrParams[] = (isset($arrFilters['liveId']) ? $arrFilters['liveId'] : '%');
    $arrParams[] = (isset($arrFilters['equipmentCardId']) ? $arrFilters['equipmentCardId'] : '%');
    $arrParams[] = (isset($arrFilters['status']) ? $arrFilters['status'] : '%');
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
  public function getEquipmentLiveDecksWithFilters($file, $line, $arrFilters=array(), $orderby='id', $order='asc')
  {
    $arrParams = $this->buildOrderAndLimit($orderby, $order);
    $arrParams[SQL_PARAMS_WHERE] = $this->buildFilters($arrFilters);
    return $this->Dao->selectEntriesWithFilters($file, $line, $arrParams);
  }
}
