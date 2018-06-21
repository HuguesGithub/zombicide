<?php
if ( !defined( 'ABSPATH') ) die( 'Forbidden' );
/**
 * Classe EquipmentExpansionDaoImpl
 * @author Hugues.
 * @version 1.0.00
 * @since 1.0.00
 */
class EquipmentExpansionDaoImpl extends LocalDaoImpl {
	/**
	 * Corps de la requête de sélection
	 * @var string $selectRequest
	 */
	protected $selectRequest = "SELECT id, equipmentCardId, expansionId, quantity ";
	/**
	 * Table concernée
	 * @var string $fromRequest
	 */
	protected $fromRequest = "FROM wp_11_zombicide_equipment_expansion ";
	/**
	 * Recherche avec filtres
	 * @var string $whereFilters
	 */
	protected $whereFilters = "WHERE equipmentCardId LIKE '%s' AND expansionId LIKE '%s' ";
	/**
	 * Requête d'insertion en base
	 * @var string $insert
	 */
	protected $insert = "INSERT INTO wp_11_zombicide_equipment_expansion (equipmentCardId, expansionId, quantity) VALUES ('%s', '%s', '%s');";
	/**
	 * Requête de mise à jour en base
	 * @var string $update
	 */
	protected $update = "UPDATE wp_11_zombicide_equipment_expansion SET equipmentCardId='%s', expansionId='%s', quantity='%s' ";
	
	public function __construct() {}
	/**
	 * @param array $rows
	 * @return array
	 */
	protected function convertToArray($rows) { return $this->globalConvertToArray('EquipmentExpansion', $rows); }
	/**
	 * @param string $file
	 * @param int $line
	 * @param array $arrParams
	 * @return array|EquipmentExpansion
	 */
	public function select($file, $line, $arrParams) {
		$Objs = $this->selectEntry($file, $line, $arrParams);
		return ( empty($Objs) ? new EquipmentExpansion() : array_shift($Objs));
	}
	
}
?>