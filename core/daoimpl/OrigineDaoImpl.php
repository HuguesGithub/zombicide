<?php
if ( !defined( 'ABSPATH') ) { die( 'Forbidden' ); }
/**
 * Classe OrigineDaoImpl
 * @author Hugues.
 * @version 1.0.00
 * @since 1.0.00
 */
class OrigineDaoImpl extends LocalDaoImpl {
  /**
   * Corps de la requête de sélection
   * @var string $selectRequest
   */
  protected $selectRequest = "SELECT id, name ";
  /**
   * Table concernée
   * @var string $fromRequest
   */
  protected $fromRequest = "FROM wp_11_zombicide_origine ";
  /**
   * Recherche avec filtres
   * @var string $whereFilters
   */
  protected $whereFilters = "WHERE name LIKE '%s' ";
  /**
   * Requête d'insertion en base
   * @var string $insert
   */
  protected $insert = "INSERT INTO wp_11_zombicide_origine (name) VALUES ('%s');";
  /**
   * Requête de mise à jour en base
   * @var unknown $update
   */
  protected $update = "UPDATE wp_11_zombicide_origine SET name='%s' ";
  
  public function __construct() {}
  /**
   * @param array $rows
   * @return array
   */
  protected function convertToArray($rows) { return $this->globalConvertToArray('Origine', $rows); }
  /**
   * @param string $file
   * @param string $line
   * @param array $arrParams
   * @return Origine
   */
   public function select($file, $line, $arrParams) {
    $Objs = $this->selectEntry($file, $line, $arrParams);
    return ( empty($Objs) ? new Origine() : array_shift($Objs));
  }

}
?>