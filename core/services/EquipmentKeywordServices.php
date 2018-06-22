<?php
if ( !defined( 'ABSPATH') ) { die( 'Forbidden' ); }
/**
 * Classe EquipmentKeywordServices
 * @author Hugues.
 * @version 1.0.00
 * @since 1.0.00
 */
class EquipmentKeywordServices extends LocalServices {
	/**
	 * L'objet Dao pour faire les requêtes
	 * @var EquipmentKeywordDaoImpl $Dao
	 */
	protected $Dao;
	
	public function __construct() {
		$this->Dao = new EquipmentKeywordDaoImpl();
		parent::__construct();
	}

	private function buildFilters($arrFilters) {
		$arrParams = array();
		$arrParams[] = ( isset($arrFilters['equipmentCardId']) ? $arrFilters['equipmentCardId'] : '%' );
		$arrParams[] = ( isset($arrFilters['keywordId']) ? $arrFilters['keywordId'] : '%' );
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
	public function getEquipmentKeywordsWithFilters($file, $line, $arrFilters=array(), $orderby='id', $order='asc') {
		$arrParams = $this->buildOrderAndLimit($orderby, $order);
		$arrParams[_SQL_PARAMS_WHERE_] = $this->buildFilters($arrFilters);
		return $this->Dao->selectEntriesWithFilters($file, $line, $arrParams);
	}
	
}
?>