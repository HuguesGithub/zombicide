<?php
if ( !defined( 'ABSPATH') ) { die( 'Forbidden' ); }
/**
 * Classe MissionExpansionServices
 * @author Hugues.
 * @version 1.0.00
 * @since 1.0.00
 */
class MissionExpansionServices extends LocalServices {
	/**
	 * L'objet Dao pour faire les requêtes
	 * @var MissionExpansionDaoImpl $Dao
	 */
	protected $Dao;
	
	public function __construct() {
		$this->Dao = new MissionExpansionDaoImpl();
		parent::__construct(array('Expansion'));
	}

	private function buildFilters($arrFilters) {
		$arrParams = array();
		$arrParams[] = ( isset($arrFilters['missionId']) ? $arrFilters['missionId'] : '%' );
		$arrParams[] = ( isset($arrFilters['expansionId']) ? $arrFilters['expansionId'] : '%' );
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
	public function getMissionExpansionsWithFilters($file, $line, $arrFilters=array(), $orderby='id', $order='asc') {
		$arrParams = $this->buildOrderAndLimit($orderby, $order);
		$arrParams[_SQL_PARAMS_WHERE_] = $this->buildFilters($arrFilters);
		return $this->Dao->selectEntriesWithFilters($file, $line, $arrParams);
	}
	/**
	 * @param string $file
	 * @param string $line
	 * @param Mission $Mission
	 * @param string $prefix
	 * @param string $classe
	 * @param bool $multiple
	 * @param string $defaultValue
	 * @return string
	 */
	public function getMissionExpansionsSelect($file, $line, $Mission, $prefix='', $classe='form-control', $multiple=FALSE, $defaultValue='') {
		$Expansions = $this->ExpansionServices->getExpansionsWithFilters($file, $line);
		$arrSetLabels = array();
		foreach ( $Expansions as $Expansion ) {
			$arrSetLabels[$Expansion->getId()] = $Expansion->getName();
		}
		$MissionExpansions = $Mission->getMissionExpansions();
		$arrSelValues = array();
		if ( !empty($MissionExpansions) ) {
			foreach ( $MissionExpansions as $MissionExpansion ) {
				array_push($arrSelValues, $MissionExpansion->getExpansionId());
			}
		}
		return $this->getSetSelect($file, $line, $arrSetLabels, $prefix.'expansionId', $arrSelValues, $defaultValue, $classe, $multiple);
	}
	
}
?>