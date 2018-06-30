<?php
if (!defined('ABSPATH')) {
  die('Forbidden');
}
/**
 * Classe LiveDaoImpl
 * @author Hugues.
 * @version 1.0.00
 * @since 1.0.00
 */
class LiveDaoImpl extends LocalDaoImpl
{
  /**
   * Corps de la requête de sélection
   * @var string $selectRequest
   */
  protected $selectRequest = "SELECT id, deckKey, dateUpdate ";
  /**
   * Table concernée
   * @var string $fromRequest
   */
  protected $fromRequest = "FROM wp_11_zombicide_live ";
  /**
   * Recherche avec filtres
   * @var string $whereFilters
   */
  protected $whereFilters = "WHERE deckKey LIKE '%s' AND dateUpdate <= '%s' ";
  /**
   * Requête d'insertion en base
   * @var string $insert
   */
  protected $insert = "INSERT INTO wp_11_zombicide_live (deckKey, dateUpdate) VALUES ('%s', '%s');";
  /**
   * Requête de mise à jour en base
   * @var string $update
   */
  protected $update = "UPDATE wp_11_zombicide_live SET deckKey='%s', dateUpdate='%s' ";

  public function __construct() {}
  /**
   * @param array $rows
   * @return array
   */
  protected function convertToArray($rows)
  { return $this->globalConvertToArray('Live', $rows); }
  /**
   * @param string $file
   * @param int $line
   * @param array $arrParams
   * @return array|LiveDeck
   */
  public function select($file, $line, $arrParams)
  {
    $Objs = $this->selectEntry($file, $line, $arrParams);
    return (empty($Objs) ? new Live() : array_shift($Objs));
  }
  
}
