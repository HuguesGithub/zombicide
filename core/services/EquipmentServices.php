<?php
if ( !defined( 'ABSPATH') ) die( 'Forbidden' );
/**
 * Classe EquipmentServices
 * @author Hugues.
 * @version 1.0.00
 * @since 1.0.00
 */
class EquipmentServices extends LocalServices {
	/**
	 * L'objet Dao pour faire les requêtes
	 * @var EquipmentDaoImpl $Dao
	 */
	protected $Dao;
	
	public function __construct() {	$this->Dao = new EquipmentDaoImpl(); }

	private function buildFilters($arrFilters) {
		$arrParams = array();
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
	public function getEquipmentsWithFilters($file, $line, $arrFilters=array(), $orderby='name', $order='asc') {
		$arrParams = $this->buildOrderAndLimit($orderby, $order);
		$arrParams[_SQL_PARAMS_WHERE_] = $this->buildFilters($arrFilters);
		return $this->Dao->selectEntriesWithFilters($file, $line, $arrParams);
	}

  
}
?>