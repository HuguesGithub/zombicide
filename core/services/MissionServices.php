<?php
if ( !defined( 'ABSPATH') ) die( 'Forbidden' );
/**
 * Classe MissionServices
 * @author Hugues.
 * @version 1.0.00
 * @since 1.0.00
 */
class MissionServices extends LocalServices {
	/**
	 * L'objet Dao pour faire les requêtes
	 * @var MissionDaoImpl $Dao
	 */
	protected $Dao;
	
	public function __construct() {	$this->Dao = new MissionDaoImpl(); }

	private function buildFilters($arrFilters) {
		$arrParams = array();
		array_push($arrParams, (!empty($arrFilters['levelId']) && !is_array($arrFilters['levelId'])) ? $arrFilters['levelId'] : '%');
		array_push($arrParams, ($arrFilters['durationId']!='' && !is_array($arrFilters['durationId'])) ? $arrFilters['durationId'] : '%');
		array_push($arrParams, (!empty($arrFilters['playerId']) && !is_array($arrFilters['playerId'])) ? $arrFilters['playerId'] : '%');
		array_push($arrParams, (!empty($arrFilters['origineId']) && !is_array($arrFilters['origineId'])) ? $arrFilters['origineId'] : '%');
		array_push($arrParams, (isset($arrFilters['published']) && !is_array($arrFilters['published'])) ? $arrFilters['published'] : '%');
    
		/*
		array_push($arrParams, !empty($arrFilters['missionliveactive']) ? $arrFilters['missionliveactive'] : '%');
		array_push($arrParams, !empty($arrFilters['active']) ? $arrFilters['active'] : '%');
		if ( !empty($arrFilters['dimension']) ) {
			$arrDim = explode('x', $arrFilters['dimension']);
			array_push($arrParams, $arrDim[0]);
			array_push($arrParams, $arrDim[1]);
		} else {
			array_push($arrParams, '%');
			array_push($arrParams, '%');
		}
		array_push($arrParams, !empty($arrFilters['search']) ? '%'.$arrFilters['search'].'%' : '%');
		array_push($arrParams, !empty($arrFilters['search']) ? '%'.$arrFilters['search'].'%' : '%');
		array_push($arrParams, !empty($arrFilters['search']) ? '%'.$arrFilters['search'].'%' : '%');
    */
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
	public function getMissionsWithFilters($file, $line, $arrFilters=array(), $orderby='title', $order='asc') {
		$arrParams = $this->buildOrderAndLimit($orderby, $order);
		$arrParams[_SQL_PARAMS_WHERE_] = $this->buildFilters($arrFilters);
		return $this->Dao->selectEntriesWithFilters($file, $line, $arrParams);
	}
	/**
	 * @param string $file
	 * @param string $line
	 * @param array $arrFilters
	 * @param string $orderby
	 * @param string $order
	 * @return array
	 */
	public function getMissionsWithFiltersIn($file, $line, $arrFilters=array(), $orderby='title', $order='asc') {
		$arrParams = $this->buildOrderAndLimit($orderby, $order);
		$arrParams[_SQL_PARAMS_WHERE_] = $this->buildFilters($arrFilters);
		return $this->Dao->selectEntriesWithFiltersIn($file, $line, $arrParams, $arrFilters);
	}
	/**
	 * @param string $file
	 * @param string $line
	 * @param string $value
	 * @param string $prefix
	 * @return string
	 */
	public function getDifficultySelect($file, $line, $value='', $prefix='') {
		$arrDifficulties = array('TUTO'=>'Tutoriel', 'EASY'=>'Facile', 'MED'=>'Moyenne', 'HARD'=>'Difficile', 'VHARD'=>'Très Difficile', 'PVP'=>'Compétitive', 'BLUE'=>'Bleue', 'YELLOW'=>'Jaune', 'ORANGE'=>'Orange', 'RED'=>'Rouge');
		$arrSetValues = $this->getSetValues($file, $line, 'difficulty', FALSE);
		$arrSetLabels = array();
		foreach ( $arrSetValues as $setValue ) { $arrSetLabels[$setValue] = $arrDifficulties[$setValue]; }
		return $this->getSetSelect($file, $line, $arrSetLabels, $prefix.'difficulty', $value, 'Difficultés');
	}
	/**
	 * @param string $file
	 * @param string $line
	 * @param string $field
	 * @param string $isSet
	 * @return array
	 */
	public function getSetValues($file, $line, $field, $isSet=TRUE) { return $this->Dao->getSetValues($file, $line, $field, $isSet); }
	/**
	 * @param string $file
	 * @param string $line
	 * @param string $value
	 * @param string $prefix
	 * @return string
	 */
	public function getNbPlayersSelect($file, $line, $value='', $prefix='') {
		$arrSetValues = $this->getSetValues($file, $line, 'nbPlayers', FALSE);
		$arrSetLabels = array();
		foreach ( $arrSetValues as $setValue ) { 
			if ( strpos($setValue, '+')!==FALSE ) { $arrSetLabels[$setValue] = $setValue[0].' Survivants et +'; }
			else {
				list($min, $max) = explode('-', $setValue);
				$arrSetLabels[$setValue] = $min.' à '.$max.' Survivants';
			}
		}
		return $this->getSetSelect($file, $line, $arrSetLabels, $prefix.'nbPlayers', $value, 'Survivants');
	}
	/**
	 * @param string $file
	 * @param string $line
	 * @param string $field
	 * @return array
	 */
	public function getDistinctValues($file, $line, $field) { return $this->Dao->getDistinctValues($file, $line, $field); }
	/**
	 * @param string $file
	 * @param string $line
	 * @param string $value
	 * @param string $prefix
	 * @return string
	 */
	public function getDimensionsSelect($file, $line, $value='', $prefix='') {
		$arrParams = $this->buildOrderAndLimit(array('width', 'height'), array('ASC', 'ASC'));
		$arrSetValues = $this->Dao->selectDistinctDimensions($file, $line, $arrParams);
		$arrSetLabels = array();
		foreach ( $arrSetValues as $setValue ) { $arrSetLabels[$setValue->label] = $setValue->label; }
		return $this->getSetSelect($file, $line, $arrSetLabels, $prefix.'dimension', $value, 'Dimensions');
	}
	/**
	 * @param string $file
	 * @param string $line
	 * @param string $value
	 * @param string $prefix
	 * @return string
	 */
	public function getDurationSelect($file, $line, $value='', $prefix='') {
		$arrSetValues = $this->getDistinctValues($file, $line, 'duration');
		$arrSetLabels = array();
		foreach ( $arrSetValues as $setValue ) { $arrSetLabels[$setValue] = $setValue.' minutes'; }
		return $this->getSetSelect($file, $line, $arrSetLabels, $prefix.'duration', $value, 'Durées');
	}
	/**
	 * @param int $width
	 * @return string
	 */
	public function getWidthSelect($width) {
		$widthSelect  = '<select name="width">';
		$widthSelect .= '<option value="0">0</option>';
		for ( $i=1; $i<=6; $i++ ) { $widthSelect .= '<option value="'.$i.'"'.($width==$i?' selected="selected"':'').'>'.$i.'</option>'; }
		$widthSelect .= '</select>';
		return $widthSelect;
	}
	/**
	 * @param int $height
	 */
	public function getHeightSelect($height) {
		$heightSelect  = '<select name="height">';
		$heightSelect .= '<option value="0">0</option>';
		for ( $i=1; $i<=6; $i++ ) { $heightSelect .= '<option value="'.$i.'"'.($height==$i?' selected="selected"':'').'>'.$i.'</option>'; }
		$heightSelect .= '</select>';
		return $heightSelect;
	}

}
?>