<?php
if ( !defined( 'ABSPATH') ) { die( 'Forbidden' ); }
/**
 * Classe SurvivorServices
 * @author Hugues.
 * @version 1.0.00
 * @since 1.0.00
 */
class SurvivorServices extends LocalServices {
	/**
	 * L'objet Dao pour faire les requêtes
	 * @var SurvivorDaoImpl $Dao
	 */
	protected $Dao;
	
	public function __construct() {	$this->Dao = new SurvivorDaoImpl(); }

	private function buildFilters($arrFilters) {
		$arrParams = array();
		array_push($arrParams, (!empty($arrFilters['name']) && !is_array($arrFilters['name'])) ? '%'.$arrFilters['name'].'%' : '%');
		array_push($arrParams, (!empty($arrFilters['zombivor']) && !is_array($arrFilters['zombivor'])) ? $arrFilters['zombivor'] : '%');
		array_push($arrParams, (!empty($arrFilters['ultimate']) && !is_array($arrFilters['ultimate'])) ? '%'.$arrFilters['ultimate'].'%' : '%');
		array_push($arrParams, (!empty($arrFilters['expansionId']) && !is_array($arrFilters['expansionId'])) ? $arrFilters['expansionId'] : '%');
		array_push($arrParams, (!empty($arrFilters['background']) && !is_array($arrFilters['background'])) ? $arrFilters['background'] : '%');
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
	public function getSurvivorsWithFilters($file, $line, $arrFilters=array(), $orderby='name', $order='asc') {
		$arrParams = $this->buildOrderAndLimit($orderby, $order);
		$arrParams[_SQL_PARAMS_WHERE_] = $this->buildFilters($arrFilters);
		return $this->Dao->selectEntriesWithFilters($file, $line, $arrParams);
	}

  
}
?>