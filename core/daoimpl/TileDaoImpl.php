<?php
if (!defined('ABSPATH')) {
  die('Forbidden');
}
/**
 * Classe TileDaoImpl
 * @author Hugues.
 * @version 1.0.00
 * @since 1.0.00
 */
class TileDaoImpl extends LocalDaoImpl
{
  /**
   * Corps de la requête de sélection
   * @var string $selectRequest
   */
  protected $selectRequest = "SELECT id, expansionId, code, coordPoly, zoneType, zoneAcces, activeTile ";
  /**
   * Table concernée
   * @var string $fromRequest
   */
  protected $fromRequest = "FROM wp_11_zombicide_tile ";
  /**
   * Recherche unitaire
   * @var string $whereId
   */
  protected $whereId = "WHERE id='%s' ";
  /**
   * Recherche avec filtres
   * @var string $whereFilters
   */
  protected $whereFilters = "WHERE code LIKE '%s' AND expansionId LIKE '%s' AND activeTile LIKE '%s' ";
  /**
   * Règle de tri
   * @var string $orderBy
   */
  protected $orderBy = SQL_PARAMS_ORDERBY;
  /**
   * Requête d'insertion en base
   * @var string $insert
   */
  protected $insert = "INSERT INTO wp_11_zombicide_tile (expansionId, code, coordPoly, zoneType, zoneAcces, activeTile) VALUES ('%s', '%s', '%s', '%s', '%s', '%s');";
  /**
   * Requête de mise à jour en base
   * @var string $update
   */
  protected $update = "UPDATE wp_11_zombicide_tile SET expansionId='%s', code='%s', coordPoly='%s', zoneType='%s', zoneAcces='%s', activeTile='%s' ";
  /**
   * Requête de suppression en base
   * @var string $delete
   */
  protected $delete = "DELETE ";
  
  public function __construct() {}
  /**
   * @param array $rows
   * @return array
   */
  protected function convertToArray($rows)
  { return $this->globalConvertToArray('Tile', $rows); }
  /**
   * @param string $file
   * @param int $line
   * @param array $arrParams
   * @return array|Tile
   */
  public function select($file, $line, $arrParams)
  {
    $Tiles = $this->selectEntry($file, $line, $arrParams);
    return (empty($Tiles) ? new Tile() : array_shift($Tiles));
  }

}
