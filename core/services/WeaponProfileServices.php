<?php
if ( !defined( 'ABSPATH') ) { die( 'Forbidden' ); }
/**
 * Classe WeaponProfileServices
 * @author Hugues.
 * @version 1.0.00
 * @since 1.0.00
 */
class WeaponProfileServices extends LocalServices {
	/**
	 * L'objet Dao pour faire les requêtes
	 * @var DurationDaoImpl $Dao
	 */
	protected $Dao;
	
	public function __construct() { $this->Dao = new WeaponProfileDaoImpl(); }

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
	public function getWeaponProfilesWithFilters($file, $line, $arrFilters=array(), $orderby='id', $order='asc') {
		$arrParams = $this->buildOrderAndLimit($orderby, $order);
		$arrParams[SQL_PARAMS_WHERE] = $this->buildFilters($arrFilters);
		return $this->Dao->selectEntriesWithFilters($file, $line, $arrParams);
	}
	
}
?>