<?php
if ( !defined( 'ABSPATH') ) { die( 'Forbidden' ); }
/**
 * Classe SpawnServices
 * @author Hugues.
 * @version 1.0.00
 * @since 1.0.00
 */
class SpawnServices extends LocalServices {
	/**
	 * L'objet Dao pour faire les requêtes
	 * @var SpawnDaoImpl $Dao
	 */
	protected $Dao;
	
	public function __construct() {	$this->Dao = new SpawnDaoImpl(); }

	private function buildFilters($arrFilters) {
		$arrParams = array();
		array_push($arrParams, (!empty($arrFilters['expansionId']) && !is_array($arrFilters['expansionId'])) ? $arrFilters['expansionId'] : '%');
		array_push($arrParams, (!empty($arrFilters['spawnNumber']) && !is_array($arrFilters['spawnNumber'])) ? $arrFilters['spawnNumber'] : '%');
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
	public function getSpawnsWithFilters($file, $line, $arrFilters=array(), $orderby='spawnNumber', $order='asc') {
		$arrParams = $this->buildOrderAndLimit($orderby, $order);
		$arrParams[_SQL_PARAMS_WHERE_] = $this->buildFilters($arrFilters);
		return $this->Dao->selectEntriesWithFilters($file, $line, $arrParams);
	}

  
}
?>