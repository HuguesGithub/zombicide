<?php
if ( !defined( 'ABSPATH') ) die( 'Forbidden' );
/**
 * Classe EquipmentDaoImpl
 * @author Hugues.
 * @version 1.0.00
 * @since 1.0.00
 */
class EquipmentDaoImpl extends LocalDaoImpl {
	/**
	 * Corps de la requête de sélection
	 * @var string $selectRequest
	 */
	protected $selectRequest = "SELECT id, name, textAbility ";
	/**
	 * Table concernée
	 * @var string $fromRequest
	 */
	protected $fromRequest = "FROM wp_11_zombicide_equipmentcards ";
	/**
	 * Recherche avec filtres
	 * @var string $whereFilters
	 */
	protected $whereFilters = "WHERE 1=1 ";
	/**
	 * Requête d'insertion en base
	 * @var string $insert
	 */
	protected $insert = "INSERT INTO wp_11_zombicide_equipmentcards (name, textAbility) VALUES ('%s', '%s');";
	/**
	 * Requête de mise à jour en base
	 * @var string $update
	 */
	protected $update = "UPDATE wp_11_zombicide_equipmentcards SET name='%s', textAbility='%s' ";

	public function __construct() {}
	/**
	 * @param array $rows
	 * @return array
	 */
	protected function convertToArray($rows) { return $this->globalConvertToArray('Equipment', $rows); }
	/**
	 * @param string $file
	 * @param int $line
	 * @param array $arrParams
	 * @return array|Equipment
	 */
	public function select($file, $line, $arrParams) {
		$Objs = $this->selectEntry($file, $line, $arrParams);
		return ( empty($Objs) ? new Equipment() : array_shift($Objs));
	}
  
}
?>