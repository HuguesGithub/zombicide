<?php
if ( !defined( 'ABSPATH') ) die( 'Forbidden' );
/**
 * Classe SurvivorSkillServices
 * @author Hugues.
 * @version 1.0.00
 * @since 1.0.00
 */
class SurvivorSkillServices extends GlobalServices {
	/**
	 * L'objet Dao pour faire les requêtes
	 * @var SurvivorDaoImpl $Dao
	 */
	protected $Dao;
	
	public function __construct() { $this->Dao = new SurvivorSkillDaoImpl(); }

	private function buildFilters($arrFilters) {
		$arrParams = array();
		$arrParams[] = ( isset($arrFilters['survivorId']) ? $arrFilters['survivorId'] : '%');
		$arrParams[] = ( isset($arrFilters['skillId']) ? $arrFilters['skillId'] : '%');
		$arrParams[] = ( isset($arrFilters['survivorTypeId']) ? $arrFilters['survivorTypeId'] : '%');
		$arrParams[] = ( isset($arrFilters['tagLevelId']) ? $arrFilters['tagLevelId'] : '%');
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
	public function getSurvivorSkillsWithFilters($file, $line, $arrFilters=array(), $orderby=array('survivorTypeId', 'tagLevelId'), $order=array('ASC', 'ASC')) {
		$arrParams = $this->buildOrderAndLimit($orderby, $order);
		$arrParams[_SQL_PARAMS_WHERE_] = $this->buildFilters($arrFilters);
		return $this->Dao->selectEntriesWithFilters($file, $line, $arrParams);
	}
	
}
?>