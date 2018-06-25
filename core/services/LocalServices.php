<?php
if (!defined('ABSPATH') ) { die('Forbidden' ); }
/**
 * Classe LocalServices
 * @author Hugues.
 * @version 1.0.00
 * @since 1.0.00
 */
class LocalServices extends GlobalServices implements iConstants {

  /**
   * @param array $services
   */
  public function __construct($services=array()) {
    if (!empty($services) ) {
      foreach ($services as $service ) {
        switch ($service ) {
          case 'Expansion'         : $this->ExpansionServices = FactoryServices::getExpansionServices(); break;
          default : break;
        }
      }
    }
  }
  
  /**
   * @param string $file
   * @param string $line
   * @param array $arrSetLabels
   * @param string $name
   * @param string $value
   * @param string $labelDefault
   * @param string $classe
   * @param bool $multiple
   */
  protected function getSetSelect($file, $line, $arrSetLabels, $name, $value, $labelDefault='', $classe='form-control', $multiple=FALSE) {
    $strSelect = '';
    $selName = $name;
    if ($labelDefault!='' ) { $strSelect .= '<label class="screen-reader-text" for="'.$name.'">'.$labelDefault.'</label>'; }
    $strSelect .= '<select id="'.$name.'" name="'.$selName.'" class="'.$classe.'"'.($multiple?' multiple':'').'>';
    if (!$multiple && $labelDefault!='' ) { $strSelect .= '<option value="">'.$labelDefault.'</option>'; }
    if (!empty($arrSetLabels) ) {
      foreach ($arrSetLabels as $key=>$labelValue ) {
        if ($key=='' ) { continue; }
        $strSelect .= '<option value="'.$key.'"';
        $strSelect .= ($this->isKeySelected($key, $value) ? ' selected="selected"' : '');
        $strSelect .= '>'.$labelValue.'</option>';
      }
    }
    return $strSelect.'</select>';
  }
  private function isKeySelected($key, $values) {
    if (!is_array($values) ) { return $key==$values; }
    $isSelected = FALSE;
    if (!empty($values) ) {
      foreach ($values as $_=>$value ) {
        if ($key==$value ) { $isSelected = TRUE; }
      }
    }
    return $isSelected;
  }
  
}
?>