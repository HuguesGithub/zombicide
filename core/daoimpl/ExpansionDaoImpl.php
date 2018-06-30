<?php
if (!defined('ABSPATH')) {
  die('Forbidden');
}
/**
 * Classe ExpansionDaoImpl
 * @author Hugues.
 * @version 1.0.00
 * @since 1.0.00
 */
class ExpansionDaoImpl extends LocalDaoImpl
{
  /**
   * Corps de la requête de sélection
   * @var string $selectRequest
   */
  protected $selectRequest = "SELECT id, code, name, displayRank ";
  /**
   * Table concernée
   * @var string $fromRequest
   */
  protected $fromRequest = "FROM wp_11_zombicide_expansion ";
  /**
   * Recherche avec filtres
   * @var string $whereFilters
   */
  protected $whereFilters = "WHERE code LIKE '%s' AND nbMissions >= '%s' ";
  /**
   * Requête d'insertion en base
   * @var string $insert
   */
  protected $insert = "INSERT INTO wp_11_zombicide_expansion (code, name, displayRank) VALUES ('%s', '%s', '%s');";
  /**
   * Requête de mise à jour en base
   * @var string $update
   */
  protected $update = "UPDATE wp_11_zombicide_expansion SET code='%s', name='%s', displayRank='%s' ";
  
  public function __construct() {}
  /**
   * @param array $rows
   * @return array
   */
  protected function convertToArray($rows)
  { return $this->globalConvertToArray('Expansion', $rows); }
  /**
   * @param string $file
   * @param int $line
   * @param array $arrParams
   * @return array|Expansion
   */
  public function select($file, $line, $arrParams)
  {
    $Expansions = $this->selectEntry($file, $line, $arrParams);
    return (empty($Expansions) ? new Expansion() : array_shift($Expansions));
  }

  private function updateNbMissions($file, $line)
  {
    $subRequest = 'SELECT COUNT(*) FROM wp_11_zombicide_mission_expansion me WHERE me.expansionId=e.id';
    $requete = 'UPDATE wp_11_zombicide_expansion e SET nbMissions = ('.$subRequest.');';
  }
  
}
