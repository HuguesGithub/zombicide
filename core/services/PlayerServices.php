<?php
if ( !defined( 'ABSPATH') ) { die( 'Forbidden' ); }
/**
 * Classe PlayerServices
 * @author Hugues.
 * @version 1.0.00
 * @since 1.0.00
 */
class PlayerServices extends LocalServices {
	/**
	 * L'objet Dao pour faire les requêtes
	 * @var PlayerDaoImpl $Dao
	 */
	protected $Dao;
	
	public function __construct() { $this->Dao = new PlayerDaoImpl(); }

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
	public function getPlayersWithFilters($file, $line, $arrFilters=array(), $orderby='id', $order='asc') {
		$arrParams = $this->buildOrderAndLimit($orderby, $order);
		$arrParams[_SQL_PARAMS_WHERE_] = $this->buildFilters($arrFilters);
		return $this->Dao->selectEntriesWithFilters($file, $line, $arrParams);
	}
	/**
	 * @param string $file
	 * @param string $line
	 * @param string $value
	 * @param string $prefix
	 * @param string $classe
	 * @param bool $multiple
	 * @param string $defaultValue
	 * @return string
	 */
	public function getNbPlayersSelect($file, $line, $value='', $prefix='', $classe='form-control', $multiple=FALSE, $defaultValue='') {
		$Players = $this->getPlayersWithFilters($file, $line, array(), 'name', 'asc');
		$arrSetLabels = array();
		foreach ( $Players as $Player ) {
			$arrSetLabels[$Player->getId()] = $Player->getNbJoueurs();
		}
		return $this->getSetSelect($file, $line, $arrSetLabels, $prefix.'playerId', $value, $defaultValue, $classe, $multiple);
	}

  
}
?>