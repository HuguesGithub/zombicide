<?php
if (!defined('ABSPATH')) {
  die('Forbidden');
}
/**
 * Classe MarketDaoImpl
 * @author Hugues.
 * @version 1.0.00
 * @since 1.0.00
 */
class MarketDaoImpl extends LocalDaoImpl
{
  /**
   * Corps de la requête de sélection
   * @var string $selectRequest
   */
  protected $selectRequest = "SELECT id, name, description, quantity, price, imgProduct, universId, lang ";
  /**
   * Table concernée
   * @var string $fromRequest
   */
  protected $fromRequest = "FROM wp_11_zombicide_market ";
  /**
   * Recherche avec filtres
   * @var string $whereFilters
   */
  protected $whereFilters = "WHERE 1=1 ";
  /**
   * Requête d'insertion en base
   * @var string $insert
   */
  protected $insert = "INSERT INTO wp_11_zombicide_market (name, description, quantity, price, imgProduct, universId, lang) VALUES ('%s', '%s', '%s', '%s', '%s', '%s', '%s');";
  /**
   * Requête de mise à jour en base
   * @var string $update
   */
  protected $update = "UPDATE wp_11_zombicide_market SET name='%s', description='%s', quantity='%s', price='%s', imgProduct='%s', universId='%s', lang='%s' ";

  public function __construct() {}
  /**
   * @param array $rows
   * @return array
   */
  protected function convertToArray($rows)
  { return $this->globalConvertToArray('Market', $rows); }
  /**
   * @param string $file
   * @param int $line
   * @param array $arrParams
   * @return array|Market
   */
  public function select($file, $line, $arrParams)
  {
    $Objs = $this->selectEntry($file, $line, $arrParams);
    return (empty($Objs) ? new Market() : array_shift($Objs));
  }
  
}
