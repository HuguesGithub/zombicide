<?php
if ( !defined( 'ABSPATH') ) { die( 'Forbidden' ); }
/**
 * Classe LiveDeckServices
 * @author Hugues.
 * @version 1.0.00
 * @since 1.0.00
 */
class LiveDeckServices extends LocalServices {
	/**
	 * L'objet Dao pour faire les requêtes
	 * @var LiveDeckDaoImpl $Dao
	 */
	protected $Dao;
	
	public function __construct() { $this->Dao = new LiveDeckDaoImpl(); }

	private function buildFilters($arrFilters) {
		$arrParams = array();
		$arrParams[] = ( isset($arrFilters['deckKey']) ? $arrFilters['deckKey'] : '%' );
		$arrParams[] = ( isset($arrFilters['dateUpdate']) ? $arrFilters['dateUpdate'] : '9999-99-99' );
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
	public function getLiveDecksWithFilters($file, $line, $arrFilters=array(), $orderby='id', $order='asc') {
		$arrParams = $this->buildOrderAndLimit($orderby, $order);
		$arrParams[_SQL_PARAMS_WHERE_] = $this->buildFilters($arrFilters);
		return $this->Dao->selectEntriesWithFilters($file, $line, $arrParams);
	}
	
}
?>