<?php
if (!defined('ABSPATH')) {
  die('Forbidden');
}
/**
 * Classe SpawnLiveDeckServices
 * @author Hugues.
 * @version 1.0.00
 * @since 1.0.00
 */
class SpawnLiveDeckServices extends LocalServices
{
  /**
   * L'objet Dao pour faire les requêtes
   * @var LiveDeckDaoImpl $Dao
   */
  protected $Dao;
  /**
   * Class Constructor
   */
  public function __construct()
  {
    parent::__construct();
    $this->Dao = new SpawnLiveDeckDaoImpl();
  }

  private function buildFilters($arrFilters)
  {
    $arrParams = array();
    $arrParams[] = (isset($arrFilters['liveId']) ? $arrFilters['liveId'] : '%');
    $arrParams[] = (isset($arrFilters['spawnCardId']) ? $arrFilters['spawnCardId'] : '%');
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
  public function getSpawnLiveDecksWithFilters($file, $line, $arrFilters=array(), $orderby='id', $order='asc')
  {
    $arrParams = $this->buildOrderAndLimit($orderby, $order);
    $arrParams[SQL_PARAMS_WHERE] = $this->buildFilters($arrFilters);
    return $this->Dao->selectEntriesWithFilters($file, $line, $arrParams);
  }
  /**
   * @param SpawnLiveDeck $SpawnLiveDeck
   * @param array $arrNumbers
   */
  public function createDeck($SpawnLiveDeck, $arrNumbers)
  {
    $cpt = 1;
    while (!empty($arrNumbers)) {
      $id = array_shift($arrNumbers);
      $SpawnLiveDeck->setSpawnCardId($id);
      $SpawnLiveDeck->setRank($cpt);
      $cpt++;
      $this->insert(__FILE__, __LINE__, $SpawnLiveDeck);
    }
  }
  
}
