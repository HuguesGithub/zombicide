<?php
if ( !defined( 'ABSPATH') ) { die( 'Forbidden' ); }
/**
 * Classe RuleServices
 * @author Hugues.
 * @version 1.0.00
 * @since 1.0.00
 */
class RuleServices extends LocalServices {
	/**
	 * L'objet Dao pour faire les requêtes
	 * @var RuleDaoImpl $Dao
	 */
	protected $Dao;
	
	public function __construct() { $this->Dao = new RuleDaoImpl(); }

	private function buildFilters($arrFilters) {
		$arrParams = array();
		$arrParams[] = ( isset($arrFilters[CST_SETTING]) ? $arrFilters[CST_SETTING] : '%');
		$arrParams[] = ( isset($arrFilters['code']) ? $arrFilters['code'] : '%');
		$arrParams[] = ( isset($arrFilters[CST_DESCRIPTION]) ? $arrFilters[CST_DESCRIPTION] : '%');
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
	public function getRulesWithFilters($file, $line, $arrFilters=array(), $orderby='code', $order='asc') {
		$arrParams = $this->buildOrderAndLimit($orderby, $order);
		$arrParams[SQL_PARAMS_WHERE] = $this->buildFilters($arrFilters);
		return $this->Dao->selectEntriesWithFilters($file, $line, $arrParams);
	}
	/**
	 * @param string $file
	 * @param string $line
	 * @param string $value
	 * @param string $prefix
	 * @param string $classe
	 * @param bool $multiple
	 * @param string $defaultLabel
	 * @return string
	 */
	public function getRuleSelect($file, $line, $value='', $prefix='id', $classe=CST_FORMCONTROL, $multiple=FALSE, $defaultLabel='') {
		$Rules = $this->getRulesWithFilters($file, $line);
		$arrSetLabels = array();
		foreach ( $Rules as $Rule ) {
			$arrSetLabels[$Rule->getId()] = $Rule->getCode();
		}
		return $this->getSetSelect($file, $line, $arrSetLabels, $prefix.'ruleId', $value, $defaultLabel, $classe, $multiple);
	}
	/**
	 * @param string $file
	 * @param string $line
	 * @param string $value
	 * @param string $prefix
	 * @param string $classe
	 * @param string $multiple
	 * @param string $defaultLabel
	 * @return string
	 */
	public function getRuleNoSettingSelect($file, $line, $value='', $prefix='id', $classe=CST_FORMCONTROL, $multiple=FALSE, $defaultLabel='---') {
		$Rules = $this->getRulesWithFilters($file, $line, array(CST_SETTING=>0));
		$arrSetLabels = array();
		foreach ( $Rules as $Rule ) {
			$arrSetLabels[$Rule->getId()] = $Rule->getCode();
		}
		return $this->getSetSelect($file, $line, $arrSetLabels, $prefix.'ruleId', $value, $defaultLabel, $classe, $multiple);
	}
	/**
	 * @param string $file
	 * @param string $line
	 * @param string $value
	 * @param string $prefix
	 * @param string $classe
	 * @param string $multiple
	 * @param string $defaultLabel
	 * @return string
	 */
	public function getRuleSettingSelect($file, $line, $value='', $prefix='id', $classe=CST_FORMCONTROL, $multiple=FALSE, $defaultLabel='---') {
		$Rules = $this->getRulesWithFilters($file, $line, array(CST_SETTING=>1));
		$arrSetLabels = array();
		foreach ( $Rules as $Rule ) {
			$arrSetLabels[$Rule->getId()] = $Rule->getCode();
		}
		return $this->getSetSelect($file, $line, $arrSetLabels, $prefix.'settingId', $value, $defaultLabel, $classe, $multiple);
	}
  
}
?>