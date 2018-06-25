<?php
if ( !defined( 'ABSPATH') ) { die( 'Forbidden' ); }
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
  
  public function __construct() {  $this->Dao = new MissionDaoImpl(); }

  private function buildFilters($arrFilters) {
    $arrParams = array();
    array_push($arrParams, (!empty($arrFilters[CST_LEVELID]) && !is_array($arrFilters[CST_LEVELID])) ? $arrFilters[CST_LEVELID] : '%');
    array_push($arrParams, ($arrFilters[CST_DURATIONID]!='' && !is_array($arrFilters[CST_DURATIONID])) ? $arrFilters[CST_DURATIONID] : '%');
    array_push($arrParams, (!empty($arrFilters[CST_PLAYERID]) && !is_array($arrFilters[CST_PLAYERID])) ? $arrFilters[CST_PLAYERID] : '%');
    array_push($arrParams, (!empty($arrFilters[CST_ORIGINEID]) && !is_array($arrFilters[CST_ORIGINEID])) ? $arrFilters[CST_ORIGINEID] : '%');
    array_push($arrParams, (isset($arrFilters[CST_PUBLISHED]) && !is_array($arrFilters[CST_PUBLISHED])) ? $arrFilters[CST_PUBLISHED] : '%');
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
    $arrParams[SQL_PARAMS_WHERE] = $this->buildFilters($arrFilters);
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
    $arrParams[SQL_PARAMS_WHERE] = $this->buildFilters($arrFilters);
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
    return $widthSelect.'</select>';
  }
  /**
   * @param int $height
   */
  public function getHeightSelect($height) {
    $heightSelect  = '<select name="height">';
    $heightSelect .= '<option value="0">0</option>';
    for ( $i=1; $i<=6; $i++ ) { $heightSelect .= '<option value="'.$i.'"'.($height==$i?' selected="selected"':'').'>'.$i.'</option>'; }
    return $heightSelect.'</select>';
  }

}
?>