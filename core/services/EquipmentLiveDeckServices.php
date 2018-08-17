<?php
if (!defined('ABSPATH')) {
  die('Forbidden');
}
/**
 * Classe EquipmentLiveDeckServices
 * @author Hugues
 * @since 1.0.00
 * @version 1.0.02
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
    $arrParams[] = (isset($arrFilters['liveSurvivorId']) ? $arrFilters['liveSurvivorId'] : '%');
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
  /**
   * @param string $file
   * @param string $line
   * @param array $arrFilters
   * @param string $orderby
   * @param string $order
   * @return array
   */
  public function getLiveDecksWithFilters($file, $line, $arrFilters=array(), $orderby='id', $order='asc')
  { return $this->getEquipmentLiveDecksWithFilters($file, $line, $arrFilters, $orderby, $order); }
  /**
   * @param EquipmentLiveDeck $EquipmentLiveDeck
   * @param array $arrEE
   */
  public function createDeck($EquipmentLiveDeck, $arrEE)
  {
    $cpt = 1;
    while (!empty($arrEE)) {
      $id = array_shift($arrEE);
      $EquipmentLiveDeck->setEquipmentCardId($id);
      $EquipmentLiveDeck->setRank($cpt);
      $cpt++;
      $this->insert(__FILE__, __LINE__, $EquipmentLiveDeck);
    }
  }
}
